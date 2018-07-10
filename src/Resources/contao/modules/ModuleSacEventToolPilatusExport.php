<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2018
 * @link https://sac-kurse.kletterkader.com
 */

namespace Markocupic\SacEventToolBundle;

use Contao\BackendTemplate;
use Contao\Calendar;
use Contao\CalendarEventsJourneyModel;
use Contao\Controller;
use Contao\Database;
use Contao\Date;
use Contao\Config;
use Contao\Environment;
use Contao\Input;
use Contao\Module;
use Contao\StringUtil;
use Haste\Form\Form;
use Patchwork\Utf8;

/**
 * Class ModuleSacEventToolPilatusExport
 * @package Markocupic\SacEventToolBundle
 */
class ModuleSacEventToolPilatusExport extends Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_sac_event_tool_event_pilatus_export';

    /**
     * @var
     */
    protected $objForm;

    /**
     * @var
     */
    protected $startDate;

    /**
     * @var
     */
    protected $endDate;

    /**
     * @var
     */
    protected $dateFormat = 'j.';

    /**
     * @var null
     */
    protected $allEventsTable = null;

    /**
     * @var null
     */
    protected $courses = null;

    /**
     * @var null
     */
    protected $tours = null;

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['eventToolEventToolPilatusExport'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }


        if(Input::post('FORM_SUBMIT') === 'edit-event')
        {
            $set = array();
            foreach(explode(';', Input::post('submitted_fields')) as $field)
            {
                $set[$field] = Input::post($field);
            }
            $objUpdateStmt = Database::getInstance()->prepare('UPDATE tl_calendar_events %s WHERE id=?')->set($set)->execute(Input::post('id'));
            if ($objUpdateStmt->affectedRows)
            {
                $arrReturn = array('status' => 'success', 'message' => 'Saved changes successfully to the Database.');
            }else{
                $arrReturn = array('status' => 'error', 'message' => 'Error during the upload process.');
            }

            die(\json_encode($arrReturn));
        }

        Controller::loadLanguageFile('tl_calendar_events');



        return parent::generate();
    }


    /**
     * Generate the module
     */
    protected function compile()
    {

        // Load language file
        Controller::loadLanguageFile('tl_calendar_events');

        $this->generateForm();

        $this->Template->form = $this->objForm;
        $this->Template->allEventsTable = $this->allEventsTable;
        $this->Template->courses = $this->courses;
        $this->Template->tours = $this->tours;


    }


    /**
     * @return Form
     */
    protected function generateForm()
    {

        $objForm = new Form('form-pilatus-export', 'POST', function ($objHaste) {
            return Input::post('FORM_SUBMIT') === $objHaste->getFormId();
        });
        $objForm->setFormActionFromUri(Environment::get('uri'));


        $range = array();
        $range[0] = '---';

        $now = Date::parse('n');
        $start = $now % 2 > 0 ? -11 : -10;

        for ($i = $start; $i < $start + 30; $i += 2)
        {
            // echo Date::parse('Y-m-d',strtotime(Date::parse("Y-m-1", strtotime($i . " month"))));
            //echo "<br>";
            $key = Date::parse("Y-m-1", strtotime($i . " month")) . '|' . Date::parse("Y-m-t", strtotime($i + 1 . "  month"));
            $range[$key] = Date::parse("1.m.Y", strtotime($i . " month")) . '-' . Date::parse("t.m.Y", strtotime($i + 1 . "  month"));
        }




        // Now let's add form fields:
        $objForm->addFormField('timeRange', array(
            'label'     => 'Zeitspanne',
            'inputType' => 'select',
            'options'   => $range,
            //'default'   => $this->User->emergencyPhone,
            'eval'      => array('mandatory' => true),
        ));



        // Let's add  a submit button
        $objForm->addFormField('submit', array(
            'label'     => 'Export starten',
            'inputType' => 'submit',
        ));

        // validate() also checks whether the form has been submitted
        if ($objForm->validate())
        {
            if (Input::post('timeRange') != 0)
            {
                $arrRange = explode('|', Input::post('timeRange'));
                $this->startDate = strtotime($arrRange[0]);
                $this->endDate = strtotime($arrRange[1]);
                $this->generateAllEventsTable();
                $this->generateCourses();

                $arrTourContainer = array();
                $objOrganizer = Database::getInstance()->prepare('SELECT * FROM tl_event_organizer ORDER BY sorting')->execute();
                while ($objOrganizer->next())
                {
                    $arrTours = array();
                    $arrTours['organizer'] = array('id' => $objOrganizer->id, 'title' => $objOrganizer->title);
                    $arrTours['events'] = $this->generateTours($objOrganizer->id);
                    $arrTourContainer[] = $arrTours;
                }
                $this->tours = $arrTourContainer;


            }
        }

        $this->objForm = $objForm;
    }


    /**
     *
     */
    protected function generateAllEventsTable()
    {
        $objDatabase = Database::getInstance();
        $arrTours = array();

        //$objTour = $objDatabase->prepare('SELECT * FROM tl_calendar_events WHERE published=? AND (eventType=? OR eventType=?) AND startDate>=? AND endDate<=? ORDER BY startDate ASC')->execute('1', 'tour', 'generalEvent', $this->startDate, $this->endDate);
        $objTour = $objDatabase->prepare('SELECT * FROM tl_calendar_events WHERE published=? AND startDate>=? AND endDate<=? ORDER BY startDate ASC')->execute('1', $this->startDate, $this->endDate);
        while ($objTour->next())
        {
            $arrRow = array(
                'week'        => Date::parse('W', $objTour->startDate) . ', ' . Date::parse('j.',$this->getFirstDayOfWeekTimestamp($objTour->startDate)) . '-' . Date::parse('j. F',$this->getLastDayOfWeekTimestamp($objTour->startDate)),
                'eventDates'  => $this->getEventPeriod($objTour->id, 'd.'),
                'weekday'     => $this->getEventPeriod($objTour->id, 'D'),
                'title'       => $objTour->title,
                'instructors' => implode(', ', CalendarEventsHelper::getInstructorNamesAsArray($objTour->id)),
                'organizers'  => implode(', ', CalendarEventsHelper::getEventOrganizersAsArray($objTour->id, 'titlePrint')),
                'id'          => $objTour->id,
            );
            // tourType
            $arrEventType = CalendarEventsHelper::getTourTypesAsArray($objTour->id, 'shortcut', false);
            if ($objTour->eventType === 'course')
            {
                // KU = Kurs
                $arrEventType[] = 'KU';
            }
            $arrRow['tourType'] = implode(', ', $arrEventType);


            // Add row to $arrTour
            $arrTours[] = $arrRow;

        }
        $this->allEventsTable = count($arrTours) > 0 ? $arrTours : null;
    }

    /**
     * @param $timestamp
     * @return int
     */
    private function getFirstDayOfWeekTimestamp($timestamp)
    {
        $date = Date::parse('d-m-Y', $timestamp);
        $day = \DateTime::createFromFormat('d-m-Y', $date);
        $day->setISODate((int)$day->format('o'), (int)$day->format('W'), 1);
        return $day->getTimestamp();
    }

    /**
     * @param $timestamp
     * @return int
     */
    private function getLastDayOfWeekTimestamp($timestamp)
    {
        return $this->getFirstDayOfWeekTimestamp($timestamp) + 6*24*3600;
    }

    /**
     * @param $id
     * @param string $dateFormat
     * @return string
     * @throws \Exception
     */
    private function getEventPeriod($id, $dateFormat = '')
    {
        if ($dateFormat == '')
        {
            $dateFormat = Config::get('dateFormat');
        }

        $dateFormatShortened = array();


        if ($dateFormat === 'd.')
        {
            $dateFormatShortened['from'] = 'd.';
            $dateFormatShortened['to'] = 'd.';
        }

        elseif ($dateFormat === 'j.m.')
        {
            $dateFormatShortened['from'] = 'j.';
            $dateFormatShortened['to'] = 'j.m.';
        }

        elseif ($dateFormat === 'j.-j. F')
        {
            $dateFormatShortened['from'] = 'j.';
            $dateFormatShortened['to'] = 'j. F';
        }
        elseif ($dateFormat === 'D')
        {
            $dateFormatShortened['from'] = 'D';
            $dateFormatShortened['to'] = 'D';
        }
        else{
            $dateFormatShortened['from'] = 'j.';
            $dateFormatShortened['to'] = 'j.m.';
        }


        $eventDuration = count(CalendarEventsHelper::getEventTimestamps($id));
        $span = Calendar::calculateSpan(CalendarEventsHelper::getStartDate($id), CalendarEventsHelper::getEndDate($id)) + 1;

        if ($eventDuration == 1)
        {
            return Date::parse($dateFormatShortened['to'], CalendarEventsHelper::getStartDate($id));
        }
        if ($eventDuration == 2 && $span != $eventDuration)
        {
            return Date::parse($dateFormatShortened['from'], CalendarEventsHelper::getStartDate($id)) . ' & ' . Date::parse($dateFormatShortened['to'] , CalendarEventsHelper::getEndDate($id));
        }
        elseif ($span == $eventDuration)
        {
            return Date::parse($dateFormatShortened['from'], CalendarEventsHelper::getStartDate($id)) . '-' . Date::parse($dateFormatShortened['to'] , CalendarEventsHelper::getEndDate($id));
        }
        else
        {
            $arrDates = array();
            $dates = CalendarEventsHelper::getEventTimestamps($id);
            foreach ($dates as $date)
            {
                $arrDates[] = Date::parse($dateFormatShortened['to'], $date);
            }

            return implode(', ', $arrDates);
        }
    }

    /**
     *
     */
    protected function generateCourses()
    {
        $objDatabase = Database::getInstance();
        $arrEvents = array();

        //$objEvent = $objDatabase->prepare('SELECT * FROM tl_calendar_events WHERE published=? AND (eventType=? OR eventType=?) AND startDate>=? AND endDate<=? ORDER BY startDate ASC')->execute('1', 'tour', 'generalEvent', $this->startDate, $this->endDate);
        $objEvent = $objDatabase->prepare('SELECT * FROM tl_calendar_events WHERE eventType=? AND published=? AND startDate>=? AND endDate<=? ORDER BY startDate ASC')->execute('course', '1', $this->startDate, $this->endDate);
        while ($objEvent->next())
        {

            $arrRow = $objEvent->row();
            $arrRow['title'] = $objEvent->title;
            $arrRow['eventState'] = $objEvent->eventState != '' ? $GLOBALS['TL_LANG']['tl_calendar_events'][$objEvent->eventState][0] : '';
            $arrRow['teaser'] = nl2br($objEvent->teaser);
            $arrRow['issues'] = nl2br($objEvent->issues);
            $arrRow['terms'] = nl2br($objEvent->terms);
            $arrRow['requirements'] = nl2br($objEvent->requirements);
            $arrRow['location'] = nl2br($objEvent->location);
            $arrRow['journey'] = nl2br($objEvent->location);
            $arrRow['equipment'] = nl2br($objEvent->equipment);
            $arrRow['leistungen'] = nl2br($objEvent->leistungen);
            $arrRow['bookingEvent'] = nl2br($objEvent->bookingEvent);
            $arrRow['meetingPoint'] = nl2br($objEvent->meetingPoint);
            $arrRow['miscellaneous'] = nl2br($objEvent->miscellaneous);
            $arrRow['week'] = Date::parse('W', $objEvent->startDate);
            if($objEvent->setRegistrationPeriod)
            {
                $arrRow['registrationPeriod'] = Date::parse('j.m.Y', $objEvent->registrationStartDate) . ' bis ' . Date::parse('j.m.Y', $objEvent->registrationEndDate);
            }
            $arrRow['eventDates'] = $this->getEventPeriod($objEvent->id, $this->dateFormat);
            $arrRow['weekday'] = $this->getEventPeriod($objEvent->id, 'D');
            $arrRow['instructors'] = implode(', ', CalendarEventsHelper::getInstructorNamesAsArray($objEvent->id));
            $arrRow['organizers'] = implode(', ', CalendarEventsHelper::getEventOrganizersAsArray($objEvent->id, 'title'));
            $arrRow['meetingPoint'] = nl2br($objEvent->meetingPoint);

            $arrRow['id'] = $objEvent->id;

            // MinMaxMembers
            $arrMinMaxMembers = array();
            if ($objEvent->addMinAndMaxMembers && $objEvent->minMembers > 0)
            {
                $arrMinMaxMembers[] = 'min. ' . $objEvent->minMembers;
            }
            if ($objEvent->addMinAndMaxMembers && $objEvent->maxMembers > 0)
            {
                $arrMinMaxMembers[] = 'max. ' . $objEvent->maxMembers;
            }
            $arrRow['minMaxMembers'] = implode('/', $arrMinMaxMembers);

            $arrHeadline = array();
            $arrHeadline[] = $this->getEventPeriod($objEvent->id, 'j.-j. F');
            $arrHeadline[] = $this->getEventPeriod($objEvent->id, 'D');
            $arrHeadline[] = $objEvent->title;
            if (isset($GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['courseLevel'][$objEvent->courseLevel]))
            {
                $arrHeadline[] = 'Kursstufe ' . $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['courseLevel'][$objEvent->courseLevel];
            }
            if ($objEvent->courseId != '')
            {
                $arrHeadline[] = 'Kurs-Nr. ' . $objEvent->courseId;
            }
            $arrRow['headline'] = implode(' > ', $arrHeadline);


            // Add row to $arrTour
            $arrEvents[] = $arrRow;

        }
        $this->courses = count($arrEvents) > 0 ? $arrEvents : null;
    }

    /**
     * @param $organizer
     * @return array|null
     * @throws \Exception
     */
    protected function generateTours($organizer)
    {
        $objDatabase = Database::getInstance();
        $arrEvents = array();

        //$objEvent = $objDatabase->prepare('SELECT * FROM tl_calendar_events WHERE published=? AND (eventType=? OR eventType=?) AND startDate>=? AND endDate<=? ORDER BY startDate ASC')->execute('1', 'tour', 'generalEvent', $this->startDate, $this->endDate);
        $objEvent = $objDatabase->prepare('SELECT * FROM tl_calendar_events WHERE eventType=? AND published=? AND startDate>=? AND endDate<=? ORDER BY startDate ASC')->execute('tour', '1', $this->startDate, $this->endDate);
        while ($objEvent->next())
        {
            $arrOrganizers = StringUtil::deserialize($objEvent->organizers, true);
            if (!in_array($organizer, $arrOrganizers))
            {
                continue;
            }

            $arrRow = $objEvent->row();
            $arrRow['title'] = $objEvent->title;
            $arrRow['eventState'] = $objEvent->eventState != '' ? $GLOBALS['TL_LANG']['tl_calendar_events'][$objEvent->eventState][0] : '';
            $arrRow['teaser'] = nl2br($objEvent->teaser);
            $arrRow['issues'] = nl2br($objEvent->issues);
            $arrRow['terms'] = nl2br($objEvent->terms);
            $arrRow['requirements'] = nl2br($objEvent->requirements);
            $arrRow['location'] = nl2br($objEvent->location);
            $arrRow['journey'] = nl2br($objEvent->location);
            $arrRow['equipment'] = nl2br($objEvent->equipment);
            $arrRow['leistungen'] = nl2br($objEvent->leistungen);
            $arrRow['bookingEvent'] = nl2br($objEvent->bookingEvent);
            $arrRow['meetingPoint'] = nl2br($objEvent->meetingPoint);
            $arrRow['miscellaneous'] = nl2br($objEvent->miscellaneous);
            $arrRow['week'] = Date::parse('W', $objEvent->startDate);
            if($objEvent->setRegistrationPeriod)
            {
                $arrRow['registrationPeriod'] = Date::parse('j.m.Y', $objEvent->registrationStartDate) . ' bis ' . Date::parse('j.m.Y', $objEvent->registrationEndDate);
            }
            $arrRow['eventDates'] = $this->getEventPeriod($objEvent->id, $this->dateFormat);
            $arrRow['weekday'] = $this->getEventPeriod($objEvent->id, 'D');
            $arrRow['instructors'] = implode(', ', CalendarEventsHelper::getInstructorNamesAsArray($objEvent->id));
            $arrRow['organizers'] = implode(', ', CalendarEventsHelper::getEventOrganizersAsArray($objEvent->id, 'title'));
            $arrRow['tourProfile'] = implode('<br>', CalendarEventsHelper::getTourProfileAsArray($objEvent->id));
            $arrRow['tourDetailText'] = nl2br($objEvent->tourDetailText);
            $arrRow['meetingPoint'] = nl2br($objEvent->meetingPoint);
            $arrRow['journey'] = CalendarEventsJourneyModel::findByPk($objEvent->journey) !== null ? CalendarEventsJourneyModel::findByPk($objEvent->journey)->title : '';


            $arrRow['id'] = $objEvent->id;

            // MinMaxMembers
            $arrMinMaxMembers = array();
            if ($objEvent->addMinAndMaxMembers && $objEvent->minMembers > 0)
            {
                $arrMinMaxMembers[] = 'min. ' . $objEvent->minMembers;
            }
            if ($objEvent->addMinAndMaxMembers && $objEvent->maxMembers > 0)
            {
                $arrMinMaxMembers[] = 'max. ' . $objEvent->maxMembers;
            }
            $arrRow['minMaxMembers'] = implode('/', $arrMinMaxMembers);

            $arrHeadline = array();
            $arrHeadline[] = $this->getEventPeriod($objEvent->id, 'j.-j. F');
            $arrHeadline[] = $this->getEventPeriod($objEvent->id, 'D');
            $arrHeadline[] = $objEvent->title;

            $strDifficulties = implode(', ', CalendarEventsHelper::getTourTechDifficultiesAsArray($objEvent->id));
            if ($strDifficulties != '')
            {
                $arrHeadline[] = $strDifficulties;
            }
            $arrRow['headline'] = implode(' > ', $arrHeadline);


            // Add row to $arrTour
            $arrEvents[] = $arrRow;

        }
        return count($arrEvents) > 0 ? $arrEvents : null;
    }
}