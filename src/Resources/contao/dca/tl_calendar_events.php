<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017
 * @link    https://sac-kurse.kletterkader.com
 */


/**
 * Table tl_calendar_events
 */
// Keys
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['sql']['keys']['mountainguide'] = 'index';
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['sql']['keys']['eventCanceled'] = 'index';
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['sql']['keys']['eventReleaseLevel'] = 'index';


// Callbacks
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][] = array('tl_calendar_events_sac_event_tool', 'onloadCallback');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][] = array('tl_calendar_events_sac_event_tool', 'triggerGlobalOperations');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onload_callback'][] = array('tl_calendar_events_sac_event_tool', 'setPalettes');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['oncreate_callback'][] = array('tl_calendar_events_sac_event_tool', 'oncreateCallback');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['oncopy_callback'][] = array('tl_calendar_events_sac_event_tool', 'oncopyCallback');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['ondelete_callback'][] = array('tl_calendar_events_sac_event_tool', 'ondeleteCallback');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'][] = array('tl_calendar_events_sac_event_tool', 'onsubmitCallback');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'][] = array('tl_calendar_events_sac_event_tool', 'adjustEndDate');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'][] = array('tl_calendar_events_sac_event_tool', 'adjustRegistrationPeriod');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'][] = array('tl_calendar_events_sac_event_tool', 'adjustImageSize');
$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'][] = array('tl_calendar_events_sac_event_tool', 'adjustEventReleaseLevel');


// List
// Sortierung nach Datum neuste Events zu letzt
$GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['disableGrouping'] = true;
$GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['fields'] = array('startDate ASC');
$GLOBALS['TL_DCA']['tl_calendar_events']['list']['sorting']['child_record_callback'] = array('tl_calendar_events_sac_event_tool', 'listEvents');

// Palettes
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = '{event_type_legend},eventType';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['course'] = 'dashboard;{event_type_legend},eventType;{broschuere_legend:hide},singleSRCBroschuere;{title_legend},inTypo3,title,alias,author,instructor,organizers,mountainguide,courseLevel,courseTypeLevel0,courseTypeLevel1;{date_legend:hide},repeatFixedDates,durationInfo,eventCanceled;{recurring_legend},recurring;{details_legend:hide},teaser,terms,issues,location,journey,requirements,leistungen,equipment,meetingPoint,bookingEvent,miscellaneous;{min_max_member_legend},addMinAndMaxMembers;{registration_legend},disableOnlineRegistration,setRegistrationPeriod,registrationGoesTo;{deregistration_legend:hide},allowDeregistration;{image_legend:hide},addImage;{gallery_legend:hide},addGallery;{enclosure_legend:hide},addEnclosure;{source_legend:hide},source;{expert_legend:hide},cssClass,noComments;';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['tour'] = 'dashboard;{event_type_legend},eventType;{title_legend},title,alias,author,instructor,mountainguide,organizers,tourType,tourTechDifficulty,teaser;{date_legend:hide},repeatFixedDates,durationInfo,eventCanceled;{recurring_legend},recurring;{details_legend:hide},location,journey,tourDetailText,altitudeDifference,requirements,leistungen,equipment,meetingPoint,bookingEvent,miscellaneous;{min_max_member_legend},addMinAndMaxMembers;{registration_legend},disableOnlineRegistration,setRegistrationPeriod,registrationGoesTo;{deregistration_legend:hide},allowDeregistration;{gallery_legend:hide},addGallery;{enclosure_legend:hide},addEnclosure;{source_legend:hide},source;{expert_legend:hide},cssClass,noComments;';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['tour_report'] = 'tourReportDashboard;{tour_report_legend},eventCanceled,tourHasExecutedLikePredicted,tourSubstitutionText,tourWeatherConditions,tourAvalancheConditions,tourSpecialIncidents;';

$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'allowDeregistration';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'addMinAndMaxMembers';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'addGallery';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'setRegistrationPeriod';

// Subpalettes
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['allowDeregistration'] = 'deregistrationLimit';
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['addGallery'] = 'multiSRC';
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['setRegistrationPeriod'] = 'registrationStartDate,registrationEndDate';
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['addMinAndMaxMembers'] = 'minMembers,maxMembers';


// Global operations
$GLOBALS['TL_DCA']['tl_calendar_events']['list']['global_operations']['plus1year'] = array
(
    'label' => &$GLOBALS['TL_LANG']['MSC']['plus1year'],
    'href' => 'transformDates=+52weeks',
    'class' => 'global_op_icon_class',
    'icon' => 'bundles/markocupicsaceventtool/icons/calendar-plus.svg',
    'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['plus1yearConfirm'] . '\'))return false;Backend.getScrollOffset()" accesskey="e"'
);

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['global_operations']['minus1year'] = array
(
    'label' => &$GLOBALS['TL_LANG']['MSC']['minus1year'],
    'href' => 'transformDates=-52weeks',
    'class' => 'global_op_icon_class',
    'icon' => 'bundles/markocupicsaceventtool/icons/calendar-minus.svg',
    'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['minus1yearConfirm'] . '\'))return false;Backend.getScrollOffset()" accesskey="e"'
);

// Operations
$GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['toggle'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['toggle'],
    'icon' => 'visible.svg',
    'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
    'button_callback' => array('tl_calendar_events_sac_event_tool', 'toggleIcon')
);

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['typo3export'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['typo3export'],
    'href' => 'action=exportEvents2Typo3',
    'icon' => 'bundles/markocupicsaceventtool/icons/typo3.png',
);

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['registrations'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['registrations'],
    'href' => 'table=tl_calendar_events_member',
    'icon' => 'bundles/markocupicsaceventtool/icons/group.png'
);

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['releaseLevelPrev'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['releaseLevelPrev'],
    'href' => 'action=releaseLevelPrev',
    'icon' => 'bundles/markocupicsaceventtool/icons/arrow_down.png',
    // 'attributes' => 'onclick="Backend.getScrollOffset()',
    'button_callback' => array('tl_calendar_events_sac_event_tool', 'releaseLevelPrev')
);

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['releaseLevelNext'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['releaseLevelNext'],
    'href' => 'action=releaseLevelNext',
    'icon' => 'bundles/markocupicsaceventtool/icons/arrow_up.png',
    //'attributes' => 'onclick="Backend.getScrollOffset()',
    'button_callback' => array('tl_calendar_events_sac_event_tool', 'releaseLevelNext')
);

$GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['delete']['button_callback'] = array('tl_calendar_events_sac_event_tool', 'deleteIcon');


// Fields***Fields***Fields***Fields***Fields***Fields***Fields***Fields

// DoNotCopy Fields - Settings
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['author']['eval']['doNotCopy'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startDate']['eval']['doNotCopy'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['endDate']['eval']['doNotCopy'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startTime']['eval']['doNotCopy'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['endTime']['eval']['doNotCopy'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['eventReleaseLevel']['eval']['doNotCopy'] = false;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['setRegistrationPeriod']['eval']['doNotCopy'] = true;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['registrationStartDate']['eval']['doNotCopy'] = true;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['registrationEndDate']['eval']['doNotCopy'] = true;


// alias
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['alias']['input_field_callback'] = array('tl_calendar_events_sac_event_tool', 'showFieldValue');
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['alias']['eval']['tl_class'] = 'clr';

// title
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['title']['eval']['tl_class'] = 'clr';

// author
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['author']['eval']['tl_class'] = 'clr';

// startTime
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startTime']['sorting'] = false;

// startDate
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startDate']['sorting'] = true;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startDate']['flag'] = 5;

// teaser
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['teaser']['eval']['rte'] = null;
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['teaser']['eval']['mandatory'] = true;


// inTypo3
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['inTypo3'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['inTypo3'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
);


// eventType
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['eventType'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['eventType'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'select',
    'options' => $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['EVENT-TYPE'],
    //'reference' => &$GLOBALS['TL_LANG']['MSC']['kursstufenInteger'],
    'eval' => array('submitOnChange' => true, 'includeBlankOption' => true, 'doNotShow' => false, 'tl_class' => 'clr m12', 'mandatory' => true),
    'sql' => "varchar(32) NOT NULL default ''",
);

// mountainguide
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['mountainguide'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['mountainguide'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
);

// Hauptleiter (main instructor) is set automatically (the first instructor in the list is set as "Hauptleiter"
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['mainInstructor'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['mainInstructor'],
    'exclude' => true,
    'search' => true,
    'filter' => true,
    'sorting' => true,
    'inputType' => 'radio',
    'flag' => 11,
    'foreignKey' => 'tl_user.name',
    'eval' => array('mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'clr'),
    'sql' => "int(10) unsigned NOT NULL default '0'",
    'relation' => array('type' => 'hasOne', 'load' => 'eager'),
);

// instructor
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['instructor'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['instructor'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkboxWizard',
    'relation' => array('type' => 'hasOne', 'load' => 'eager'),
    'foreignKey' => 'tl_user.name',
    'eval' => array('multiple' => true, 'chosen' => true, 'orderField' => 'orderInstructor', 'mandatory' => true, 'includeBlankOption' => false, 'tl_class' => 'clr'),
    'sql' => "blob NULL",
);

// orderInstructor
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['orderInstructor'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['orderInstructor'],
    'sql' => "blob NULL",
);

// Terms/Ziele
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['terms'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['terms'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => true),
    'sql' => "text NULL",
);

// issues
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['issues'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['issues'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => true),
    'sql' => "text NULL",
);

// requirements
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['requirements'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['requirements'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => true),
    'sql' => "text NULL",
);

// leistungen
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['leistungen'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['leistungen'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);

// courseLevel
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['courseLevel'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['courseLevel'],
    'exclude' => true,
    'search' => true,
    'filter' => true,
    'inputType' => 'select',
    'options' => $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['courseLevel'],
    //'reference' => &$GLOBALS['TL_LANG']['MSC']['kursstufenInteger'],
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => true),
    'sql' => "int(10) unsigned NULL",
);

// Course Type Level_0
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['courseTypeLevel0'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['courseTypeLevel0'],
    'exclude' => true,
    'search' => true,
    'filter' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_course_main_type.name',
    'relation' => array('type' => 'hasOne', 'load' => 'lazy'),
    'eval' => array('tl_class' => 'clr m12', 'submitOnChange' => true, 'includeBlankOption' => true, 'multiple' => false, 'mandatory' => true),
    'sql' => "int(10) unsigned NOT NULL default '0'",
);

// Course Type Level_1
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['courseTypeLevel1'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['courseTypeLevel1'],
    'exclude' => true,
    'search' => true,
    'filter' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_course_sub_type.name',
    'relation' => array('type' => 'hasOne', 'load' => 'lazy'),
    'options_callback' => array('tl_calendar_events_sac_event_tool', 'optionsCbCourseSubType'),
    'eval' => array('tl_class' => 'clr m12', 'multiple' => false, 'mandatory' => true),
    'sql' => "int(10) unsigned NOT NULL default '0'",
);

// organizers
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['organizers'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['organizers'],
    'exclude' => true,
    'search' => true,
    'filter' => true,
    'sorting' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_event_organizer.title',
    'relation' => array('type' => 'hasMany', 'load' => 'lazy'),
    'eval' => array('multiple' => true, 'chosen' => true, 'mandatory' => true, 'includeBlankOption' => false, 'tl_class' => 'clr m12'),
    'sql' => "blob NULL",
);

// equipment
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['equipment'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['equipment'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);

// durationInfo
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['durationInfo'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['durationInfo'],
    'search' => true,
    'filter' => true,
    'exclude' => true,
    'search' => true,
    'inputType' => 'select',
    'options' => $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['durationInfo'],
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => true),
    'sql' => "varchar(32) NOT NULL default ''",
);

// Add minimum an maximum members
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['addMinAndMaxMembers'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['addMinAndMaxMembers'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => true),
    'sql' => "char(1) NOT NULL default ''"
);

// minMembers
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['minMembers'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['minMembers'],
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'clr m12', 'rgxp' => 'digit', 'mandatory' => true),
    'sql' => "int(3) unsigned NULL",
);

// maxMembers
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['maxMembers'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['maxMembers'],
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'clr m12', 'rgxp' => 'digit', 'mandatory' => true),
    'sql' => "int(3) unsigned NULL",
);

// bookingEvent
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['bookingEvent'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['bookingEvent'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);

// miscellaneous
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['miscellaneous'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['miscellaneous'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);

// repeatFixedDates
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['repeatFixedDates'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['repeatFixedDates'],
    'exclude' => true,
    'inputType' => 'multiColumnWizard',
    'eval' => array(
        'columnsCallback' => array('tl_calendar_events_sac_event_tool', 'listFixedDates'),
        'buttons' => array('up' => false, 'down' => false),
        'mandatory' => true,
    ),
    'sql' => "blob NULL",
);

// eventCanceled
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['eventCanceled'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['eventCanceled'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('doNotCopy' => true),
    'sql' => "char(1) NOT NULL default ''"
);

// meetingPoint
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['meetingPoint'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['meetingPoint'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => '', 'mandatory' => false),
    'sql' => "text NULL",
);

// singleSRCBroschuere
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['singleSRCBroschuere'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['singleSRCBroschuere'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'eval' => array('filesOnly' => true, 'extensions' => Config::get('validImageTypes'), 'fieldType' => 'radio', 'mandatory' => false),
    'sql' => "binary(16) NULL",
);

// Disable online registration
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['disableOnlineRegistration'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['disableOnlineRegistration'],
    'filter' => true,
    'sorting' => true,
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('doNotCopy' => false, 'submitOnChange' => false),
    'sql' => "char(1) NOT NULL default ''"
);

// registrationGoesTo
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['registrationGoesTo'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['registrationGoesTo'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'select',
    'relation' => array('type' => 'hasOne', 'load' => 'eager'),
    'foreignKey' => 'tl_user.name',
    'eval' => array('multiple' => true, 'chosen' => true, 'includeBlankOption' => true, 'tl_class' => 'clr'),
    'sql' => "blob NULL",
);

// Set registration period
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['setRegistrationPeriod'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['setRegistrationPeriod'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('doNotCopy' => false, 'submitOnChange' => true),
    'sql' => "char(1) NOT NULL default ''"
);

// Set registration start date
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['registrationStartDate'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['registrationStartDate'],
    'default' => time(),
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'date', 'mandatory' => true, 'doNotCopy' => false, 'datepicker' => true, 'tl_class' => 'w50 wizard'),
    'sql' => "int(10) unsigned NULL"
);

// Set registration end date
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['registrationEndDate'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['registrationEndDate'],
    'default' => time(),
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'date', 'mandatory' => true, 'doNotCopy' => false, 'datepicker' => true, 'tl_class' => 'w50 wizard'),
    'sql' => "int(10) unsigned NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['allowDeregistration'] = array(

    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['allowDeregistration'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => true),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['deregistrationLimit'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['deregistrationLimit'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'select',
    'options' => range(1, 720),
    'eval' => array('rgxp' => 'natural', 'nospace' => true, 'tl_class' => 'w50'),
    'sql' => "int(10) unsigned NOT NULL default '0'",
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['addGallery'] = array(

    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['addGallery'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => true),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['multiSRC'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['multiSRC'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'eval' => array('multiple' => true, 'extensions' => 'jpg,jpeg,png', 'fieldType' => 'checkbox', 'orderField' => 'orderSRC', 'files' => true, 'filesOnly' => true, 'mandatory' => true),
    'sql' => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['orderSRC'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['orderSRC'],
    'sql' => "blob NULL"
);


/***** FIELDS FOR TOURS FIELDS FOR TOURS  FIELDS FOR TOURS FIELDS FOR TOURS FIELDS FOR TOURS FIELDS FOR TOURS FIELDS FOR TOURS********/
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['dashboard'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['dashboard'],
    'input_field_callback' => array('tl_calendar_events_sac_event_tool', 'inputFieldCallbackEventDashboard'),
    'eval' => array('doNotShow' => true, 'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
    'sql' => "varchar(255) NOT NULL default ''"
);

// tour type
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourType'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourType'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_tour_type.title',
    'relation' => array('type' => 'hasMany', 'load' => 'eager'),
    'eval' => array('multiple' => true, 'chosen' => true, 'mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'clr m12'),
    'sql' => "blob NULL",
);

// tourRechDifficulty
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourTechDifficulty'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourTechDifficulty'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'select',
    'reference' => &$GLOBALS['TL_LANG']['tl_calendar_events'],
    'options_callback' => array('tl_calendar_events_sac_event_tool', 'optionsCbTourDifficulties'),
    'relation' => array('type' => 'hasMany', 'load' => 'eager'),
    'foreignKey' => 'tl_tour_difficulty.shortcut',
    'eval' => array('multiple' => true, 'chosen' => true, 'mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'clr m12'),
    'sql' => "blob NULL",
);

// altitudeDifference
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['altitudeDifference'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['altitudeDifference'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => false, 'tl_class' => 'clr m12', 'rgxp' => 'natural', 'maxlength' => 4),
    'sql' => "int(10) unsigned NULL",
);

// requirements
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourDetailText'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourDetailText'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('tl_class' => 'clr m12', 'mandatory' => true),
    'sql' => "text NULL",
);

// eventReleaseLevel
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['eventReleaseLevel'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['eventReleaseLevel'],
    'exclude' => true,
    'filter' => true,
    'sorting' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_event_release_level_policy.title',
    'relation' => array('type' => 'hasOne', 'load' => 'lazy'),
    'options_callback' => array('tl_calendar_events_sac_event_tool', 'listReleaseLevels'),
    'save_callback' => array(array('tl_calendar_events_sac_event_tool', 'saveCallbackEventReleaseLevel')),
    'eval' => array('doNotCopy' => true, 'mandatory' => true, 'tl_class' => 'clr m12'),
    'sql' => "int(10) unsigned NOT NULL default '0'",
);
if (!Input::get('act') || Input::get('act') === 'select')
{
    // Display the field correctly in the filter menu
    $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['eventReleaseLevel']['options_callback'] = null;
}


/********* tour report *******/
// This field is autofilles, if a user has filled in the event report
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['filledInEventReportForm'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['filledInEventReportForm'],
    'exclude' => false,
    'eval' => array('doNotShow' => true, 'doNotCopy' => true),
    'sql' => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourReportDashboard'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourReportDashboard'],
    'input_field_callback' => array('tl_calendar_events_sac_event_tool', 'inputFieldCallbackTourReportDashboard'),
    'eval' => array('doNotShow' => true, 'doNotCopy' => true, 'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
    //'sql' => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['journey'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['journey'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_calendar_events_journey.title',
    'relation' => array('type' => 'hasOne', 'load' => 'lazy'),
    'eval' => array('doNotCopy' => false, 'multiple' => false, 'mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'clr m12'),
    'sql' => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourHasExecutedLikePredicted'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourHasExecutedLikePredicted'],
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => array('doNotCopy' => true),
    'sql' => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourSubstitutionText'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourSubstitutionText'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('doNotCopy' => true, 'tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourWeatherConditions'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourWeatherConditions'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('doNotCopy' => true, 'tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourAvalancheConditions'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourAvalancheConditions'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('doNotCopy' => true, 'tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['tourSpecialIncidents'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_calendar_events']['tourSpecialIncidents'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('doNotCopy' => true, 'tl_class' => 'clr m12', 'mandatory' => false),
    'sql' => "text NULL",
);


// allowEdititingOnFirstReleaseLevelOnly - fields
$allowEdititingOnFirstReleaseLevelOnly = array('eventType', 'title', 'author', 'organizers', 'instructor', 'mountainguide', 'courseLevel', 'courseTypeLevel0', 'courseTypeLevel1', 'repeatFixedDates', 'durationInfo', 'tourType', 'tourTechDifficulty');
foreach ($allowEdititingOnFirstReleaseLevelOnly as $field)
{
    $GLOBALS['TL_DCA']['tl_calendar_events']['fields'][$field]['allowEdititingOnFirstReleaseLevelOnly'] = true;
}


