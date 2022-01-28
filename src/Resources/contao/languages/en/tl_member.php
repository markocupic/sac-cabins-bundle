<?php

declare(strict_types=1);

/*
 * This file is part of SAC Event Tool Bundle.
 *
 * (c) Marko Cupic 2022 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/sac-event-tool-bundle
 */

// Legends
$GLOBALS['TL_LANG']['tl_member']['section_legend'] = 'Sektions-Einstellungen';
$GLOBALS['TL_LANG']['tl_member']['section_info_legend'] = 'Sektionsinformationen';
$GLOBALS['TL_LANG']['tl_member']['avatar_legend'] = 'Avatar';
$GLOBALS['TL_LANG']['tl_member']['emergency_legend'] = 'Notfallangaben';
$GLOBALS['TL_LANG']['tl_member']['food_legend'] = 'Essgewohnheiten';
$GLOBALS['TL_LANG']['tl_member']['education_legend'] = 'Ausbildung';

// Fields
if (TL_MODE === 'FE') {
    $GLOBALS['TL_LANG']['tl_member']['username'] = ['SAC Mitgliedernummer', 'Gib deine 6-stellige SAC-Mitgliedernummer ein.'];
}
$GLOBALS['TL_LANG']['tl_member']['uuid'] = ['UUID (Zentralkommitee Bern)', ''];
$GLOBALS['TL_LANG']['tl_member']['activation'] = ['Aktivierungscode', ''];
$GLOBALS['TL_LANG']['tl_member']['activationLinkLifetime'] = ['Gültigkeitsdauer Aktivierungstoken', ''];
$GLOBALS['TL_LANG']['tl_member']['isSacMember'] = ['Dieser User ist ein SAC-Mitglied', 'Dieses Mitglied wurde in der Datenbank (CSV-File) beim täglichen Sync gefunden.'];
$GLOBALS['TL_LANG']['tl_member']['hasLeadClimbingEducation'] = ['Seilschaftsführer', 'Geben Sie an, ob das Mitglied die Seilschaftsführer-Ausbildung besitzt.'];
$GLOBALS['TL_LANG']['tl_member']['dateOfLeadClimbingEducation'] = ['Datum der Seilschaftsführer-Ausbildung', 'Geben Sie an, wann das Mitglied die Seilschaftsführer-Ausbildung absolviert hat.'];
$GLOBALS['TL_LANG']['tl_member']['sacMemberId'] = ['Mitgliedernummer', ''];
$GLOBALS['TL_LANG']['tl_member']['ahvNumber'] = ['AHV-Nummer', 'Geben Sie hier die AHV-Nummer ein.'];
$GLOBALS['TL_LANG']['tl_member']['emergencyPhone'] = ['Notfall-Benachrichtigungs-Telefonnummer', 'Geben Sie die Telefonnummer einer Ihnen vertrauten Person an, welche in einem Notfall kontaktiert werden kann.'];
$GLOBALS['TL_LANG']['tl_member']['emergencyPhoneName'] = ['Kontaktperson für Notfallbenachrichtigung', 'Geben Sie den Namen der Kontaktperson ein, welche im FAlle eines Notfalls benachrichtigt werden soll.'];
$GLOBALS['TL_LANG']['tl_member']['sectionId'] = ['Sektion', ''];
$GLOBALS['TL_LANG']['tl_member']['addressExtra'] = ['Adresszusatz', ''];
$GLOBALS['TL_LANG']['tl_member']['phoneBusiness'] = ['Telefon Geschäft', ''];
$GLOBALS['TL_LANG']['tl_member']['profession'] = ['Beruf', ''];
$GLOBALS['TL_LANG']['tl_member']['entryYear'] = ['Eintrittsjahr', ''];
$GLOBALS['TL_LANG']['tl_member']['membershipType'] = ['Mitglieder Typ', ''];
$GLOBALS['TL_LANG']['tl_member']['sectionInfo1'] = ['Sektionsinfo 1', ''];
$GLOBALS['TL_LANG']['tl_member']['sectionInfo2'] = ['Sektionsinfo 2', ''];
$GLOBALS['TL_LANG']['tl_member']['sectionInfo3'] = ['Sektionsinfo 3', ''];
$GLOBALS['TL_LANG']['tl_member']['sectionInfo4'] = ['Bemerkungen Sektion', ''];
$GLOBALS['TL_LANG']['tl_member']['debit'] = ['Debit', ''];
$GLOBALS['TL_LANG']['tl_member']['memberStatus'] = ['Mitgliederstatus', ''];
$GLOBALS['TL_LANG']['tl_member']['disable'] = ['Inaktives SAC-Mitglied (ausgetreten)', ''];
$GLOBALS['TL_LANG']['tl_member']['foodHabits'] = ['Essgewohnheiten (Vegetarier, Laktoseintoleranz, etc.)', ''];

// References
$GLOBALS['TL_LANG']['tl_member']['section']['4250'] = 'SAC PILATUS';
$GLOBALS['TL_LANG']['tl_member']['section']['4251'] = 'SAC PILATUS SURENTAL';
$GLOBALS['TL_LANG']['tl_member']['section']['4252'] = 'SAC PILATUS NAPF';
$GLOBALS['TL_LANG']['tl_member']['section']['4253'] = 'SAC PILATUS HOCHDORF';
$GLOBALS['TL_LANG']['tl_member']['section']['4254'] = 'SAC PILATUS RIGI';
