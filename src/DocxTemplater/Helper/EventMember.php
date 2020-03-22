<?php

declare(strict_types=1);

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2020 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2020
 * @link https://github.com/markocupic/sac-event-tool-bundle
 */

namespace Markocupic\SacEventToolBundle\DocxTemplater\Helper;

use Contao\CalendarEventsMemberModel;
use Contao\CalendarEventsModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Date;
use Contao\MemberModel;
use Contao\Model\Collection;
use Contao\UserModel;
use Markocupic\PhpOffice\PhpWord\MsWordTemplateProcessor;
use Markocupic\SacEventToolBundle\CalendarEventsHelper;

/**
 * Class EventMember
 * @package Markocupic\SacEventToolBundle\DocxTemplater\Helper
 */
class EventMember
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * EventRapport constructor.
     * @param ContaoFramework $framework
     * @param string $projectDir
     */
    public function __construct(ContaoFramework $framework, string $projectDir)
    {
        $this->framework = $framework;
        $this->projectDir = $projectDir;

        // Initialize contao framework
        $this->framework->initialize();
    }

    /**
     * @param MsWordTemplateProcessor $objPhpWord
     * @param CalendarEventsModel $objEvent
     * @param Collection $objEventMember
     */
    public function getEventMemberData(MsWordTemplateProcessor $objPhpWord, CalendarEventsModel $objEvent, Collection $objEventMember)
    {
        // Set adapters
        /** @var  UserModel $userModelAdapter */
        $userModelAdapter = $this->framework->getAdapter(UserModel::class);
        /** @var  MemberModel $memberModelAdapter */
        $memberModelAdapter = $this->framework->getAdapter(MemberModel::class);
        /** @var  $dateAdapter */
        $dateAdapter = $this->framework->getAdapter(Date::class);
        /** @var  CalendarEventsHelper $calendarEventsHelperAdapter */
        $calendarEventsHelperAdapter = $this->framework->getAdapter(CalendarEventsHelper::class);

        $i = 0;

        // TL
        $arrInstructors = $calendarEventsHelperAdapter->getInstructorsAsArray($objEvent, false);
        if (!empty($arrInstructors) && is_array($arrInstructors))
        {
            foreach ($arrInstructors as $userId)
            {
                $objUserModel = $userModelAdapter->findByPk($userId);
                if ($objUserModel !== null)
                {
                    // Check club membership
                    $isMember = false;
                    $objMember = $memberModelAdapter->findOneBySacMemberId($objUserModel->sacMemberId);
                    if ($objMember !== null)
                    {
                        if ($objMember->isSacMember && !$objMember->disable)
                        {
                            $isMember = true;
                        }
                    }
                    // Keep this var empty
                    $transportInfo = '';

                    // Phone
                    $mobile = $objUserModel->mobile != '' ? $objUserModel->mobile : '----';

                    $i++;

                    // Clone row
                    $objPhpWord->createClone('i');

                    // Push data to clone
                    $objPhpWord->addToClone('i', 'i', $i, ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'role', 'TL', ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'firstname', $this->prepareString($objUserModel->firstname), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'lastname', $this->prepareString($objUserModel->lastname), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'sacMemberId', 'Mitgl. No. ' . $objUserModel->sacMemberId, ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'isNotSacMember', $isMember ? ' ' : '!inaktiv/kein Mitglied', ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'street', $this->prepareString($objUserModel->street), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'postal', $this->prepareString($objUserModel->postal), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'city', $this->prepareString($objUserModel->city), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'emergencyPhone', $this->prepareString($objUserModel->emergencyPhone), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'emergencyPhoneName', $this->prepareString($objUserModel->emergencyPhoneName), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'mobile', $this->prepareString($mobile), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'email', $this->prepareString($objUserModel->email), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'transportInfo', $this->prepareString($transportInfo), ['multiline' => false]);
                    $objPhpWord->addToClone('i', 'dateOfBirth', $objUserModel->dateOfBirth != '' ? $dateAdapter->parse('Y', $objUserModel->dateOfBirth) : '', ['multiline' => false]);
                }
            }
        }

        // TN
        if (null !== $objEventMember)
        {
            while ($objEventMember->next())
            {
                $i++;

                // Check club membership
                $strIsActiveMember = '!inaktiv/keinMitglied';
                if ($objEventMember->sacMemberId != '')
                {
                    $objMemberModel = $memberModelAdapter->findOneBySacMemberId($objEventMember->sacMemberId);
                    if ($objMemberModel !== null)
                    {
                        if ($objMemberModel->isSacMember && !$objMemberModel->disable)
                        {
                            $strIsActiveMember = ' ';
                        }
                    }
                }

                $transportInfo = '';
                if (strlen($objEventMember->carInfo))
                {
                    if ((int) $objEventMember->carInfo > 0)
                    {
                        $transportInfo .= sprintf(' Auto mit %s Plätzen', $objEventMember->carInfo);
                    }
                }

                // GA, Halbtax, Tageskarte
                if (strlen($objEventMember->ticketInfo))
                {
                    $transportInfo .= sprintf(' Ticket: Mit %s', $objEventMember->ticketInfo);
                }

                // Phone
                $mobile = $objEventMember->mobile != '' ? $objEventMember->mobile : '----';
                // Clone row
                $objPhpWord->createClone('i');

                // Push data to clone
                $objPhpWord->addToClone('i', 'i', $i, ['multiline' => false]);
                $objPhpWord->addToClone('i', 'role', 'TN', ['multiline' => false]);
                $objPhpWord->addToClone('i', 'firstname', $this->prepareString($objEventMember->firstname), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'lastname', $this->prepareString($objEventMember->lastname), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'sacMemberId', 'Mitgl. No. ' . $objEventMember->sacMemberId, ['multiline' => false]);
                $objPhpWord->addToClone('i', 'isNotSacMember', $strIsActiveMember, ['multiline' => false]);
                $objPhpWord->addToClone('i', 'street', $this->prepareString($objEventMember->street), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'postal', $this->prepareString($objEventMember->postal), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'city', $this->prepareString($objEventMember->city), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'mobile', $this->prepareString($mobile), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'emergencyPhone', $this->prepareString($objEventMember->emergencyPhone), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'emergencyPhoneName', $this->prepareString($objEventMember->emergencyPhoneName), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'email', $this->prepareString($objEventMember->email), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'transportInfo', $this->prepareString($transportInfo), ['multiline' => false]);
                $objPhpWord->addToClone('i', 'dateOfBirth', $objEventMember->dateOfBirth != '' ? $dateAdapter->parse('Y', $objEventMember->dateOfBirth) : '', ['multiline' => false]);
            }
        }

        // Event instructors
        $aInstructors = $calendarEventsHelperAdapter->getInstructorsAsArray($objEvent, false);

        $arrInstructors = array_map(function ($id) {
            $userModelAdapter = $this->framework->getAdapter(UserModel::class);

            $objUser = $userModelAdapter->findByPk($id);
            if ($objUser !== null)
            {
                return $objUser->name;
            }
        }, $aInstructors);
        $objPhpWord->replace('eventInstructors', $this->prepareString(implode(', ', $arrInstructors)));

        // Event Id
        $objPhpWord->replace('eventId', $objEvent->id);
    }

    /**
     * @param $objEvent
     * @return mixed
     */
    public function getParticipatedEventMembers($objEvent)
    {
        /** @var  CalendarEventsMemberModel $calendarEventsMemberModelAdapter */
        $calendarEventsMemberModelAdapter = $this->framework->getAdapter(CalendarEventsMemberModel::class);

        $objEventsMember = $calendarEventsMemberModelAdapter->findBy(
            ['tl_calendar_events_member.eventId=?', 'tl_calendar_events_member.hasParticipated=?'],
            [$objEvent->id, '1']
        );
        return $objEventsMember;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function prepareString($string = ''): string
    {
        if (null === $string)
        {
            return '';
        }

        return htmlspecialchars(html_entity_decode((string) $string));
    }

}