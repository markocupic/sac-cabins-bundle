<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2018
 * @link https://sac-kurse.kletterkader.com
 */


// Remove unwanted fields from palette
foreach ($GLOBALS['TL_DCA']['tl_user']['palettes'] as $key => $value)
{
    if ($key != '__selector__')
    {
        $GLOBALS['TL_DCA']['tl_user']['palettes'][$key] = str_replace('alternate_email', '', $GLOBALS['TL_DCA']['tl_user']['palettes'][$key]);
        $GLOBALS['TL_DCA']['tl_user']['palettes'][$key] = str_replace('alternate_email_2', '', $GLOBALS['TL_DCA']['tl_user']['palettes'][$key]);
    }
}

// Manipulate palettes
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('frontend_legend', 'backend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addLegend('bank_account_legend', 'frontend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addLegend('emergency_phone_legend', 'bank_account_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addField(array('dateOfBirth', 'street', 'postal', 'city', 'phone', 'mobile', 'website'), 'name_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(array('emergencyPhone', 'emergencyPhoneName'), 'emergency_phone_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(array('iban'), 'bank_account_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(array('avatarSRC'), 'frontend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('login', 'tl_user');


Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('frontend_legend', 'backend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addLegend('instructor_legend', 'backend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addLegend('role_legend', 'backend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addLegend('instructor_legend', 'backend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_BEFORE)
    ->addField(array('role'), 'role_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(array('avatarSRC'), 'frontend_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(array('iban', 'leiterQualifikation'), 'instructor_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->addField(array('calendar_containers', 'calendar_containerp'), 'calendars_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_PREPEND)
    ->applyToPalette('admin', 'tl_user')
    ->applyToPalette('default', 'tl_user')
    ->applyToPalette('group', 'tl_user')
    ->applyToPalette('extend', 'tl_user')
    ->applyToPalette('custom', 'tl_user');


// Onload callbacks
$GLOBALS['TL_DCA']['tl_user']['config']['onload_callback'][] = array('tl_user_sac_event_tool', 'onloadCallback');


// Fields
// calendar_containers
$GLOBALS['TL_DCA']['tl_user']['fields']['calendar_containers'] = array(
    'label'      => &$GLOBALS['TL_LANG']['tl_user']['calendar_containers'],
    'exclude'    => true,
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_calendar_container.title',
    'eval'       => array('multiple' => true),
    'sql'        => "blob NULL",
);

// calendar_containerp
$GLOBALS['TL_DCA']['tl_user']['fields']['calendar_containerp'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['calendar_containerp'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => array('create', 'delete'),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => array('multiple' => true),
    'sql'       => "blob NULL",
);

// firstname
$GLOBALS['TL_DCA']['tl_user']['fields']['firstname'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['firstname'],
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'),
    'sql'       => "varchar(255) NOT NULL default ''",
);

// lastname
$GLOBALS['TL_DCA']['tl_user']['fields']['lastname'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['lastname'],
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'),
    'sql'       => "varchar(255) NOT NULL default ''",
);

// iban
$GLOBALS['TL_DCA']['tl_user']['fields']['iban'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['iban'],
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'),
    'sql'       => "varchar(255) NOT NULL default ''",
);

// sacMemberId
$GLOBALS['TL_DCA']['tl_user']['fields']['sacMemberId'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['sacMemberId'],
    'exclude'   => true,
    'search'    => true,
    'sorting'   => true,
    'flag'      => 1,
    'inputType' => 'text',
    'eval'      => array('doNotCopy' => true, 'readonly' => false, 'mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr', 'rgxp' => 'natural'),
    'sql'       => "int(10) unsigned NOT NULL default '0'",
);

// dateOfBirth
$GLOBALS['TL_DCA']['tl_user']['fields']['dateOfBirth'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['dateOfBirth'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => array('rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'clr wizard'),
    'sql'       => "varchar(11) NOT NULL default ''",
);

// gender
$GLOBALS['TL_DCA']['tl_user']['fields']['gender'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['gender'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('male', 'female'),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => array('includeBlankOption' => true, 'tl_class' => 'clr'),
    'sql'       => "varchar(32) NOT NULL default ''",
);

// street
$GLOBALS['TL_DCA']['tl_user']['fields']['street'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['street'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 255, 'tl_class' => 'clr'),
    'sql'       => "varchar(255) NOT NULL default ''",
);


// postal
$GLOBALS['TL_DCA']['tl_user']['fields']['postal'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['postal'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 32, 'tl_class' => 'clr'),
    'sql'       => "varchar(32) NOT NULL default ''",
);

// city
$GLOBALS['TL_DCA']['tl_user']['fields']['city'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['city'],
    'exclude'   => true,
    'filter'    => true,
    'search'    => true,
    'sorting'   => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 255, 'tl_class' => 'clr'),
    'sql'       => "varchar(255) NOT NULL default ''",
);

// state
$GLOBALS['TL_DCA']['tl_user']['fields']['state'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['state'],
    'exclude'   => true,
    'sorting'   => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 64, 'tl_class' => 'clr'),
    'sql'       => "varchar(64) NOT NULL default ''",
);

// country
$GLOBALS['TL_DCA']['tl_user']['fields']['country'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['country'],
    'exclude'   => true,
    'filter'    => true,
    'sorting'   => true,
    'inputType' => 'select',
    'options'   => System::getCountries(),
    'eval'      => array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'),
    'sql'       => "varchar(2) NOT NULL default ''",
);

// phone
$GLOBALS['TL_DCA']['tl_user']['fields']['phone'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['phone'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 64, 'rgxp' => 'phone', 'decodeEntities' => true, 'tl_class' => 'clr'),
    'sql'       => "varchar(64) NOT NULL default ''",
);

// emergencyPhone
$GLOBALS['TL_DCA']['tl_user']['fields']['emergencyPhone'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['emergencyPhone'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 64, 'rgxp' => 'phone', 'mandatory' => true, 'decodeEntities' => true, 'tl_class' => 'clr'),
    'sql'       => "varchar(64) NOT NULL default ''",
);

// emergencyPhoneName
$GLOBALS['TL_DCA']['tl_user']['fields']['emergencyPhoneName'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['emergencyPhoneName'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 64, 'mandatory' => true, 'decodeEntities' => true, 'tl_class' => 'clr'),
    'sql'       => "varchar(64) NOT NULL default ''",
);

// mobile
$GLOBALS['TL_DCA']['tl_user']['fields']['mobile'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['mobile'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('maxlength' => 64, 'rgxp' => 'phone', 'decodeEntities' => true, 'tl_class' => 'clr'),
    'sql'       => "varchar(64) NOT NULL default ''",
);

// website
$GLOBALS['TL_DCA']['tl_user']['fields']['website'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['website'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('rgxp' => 'url', 'maxlength' => 255, 'tl_class' => 'clr'),
    'sql'       => "varchar(255) NOT NULL default ''",
);

// leiterQualifikation
$GLOBALS['TL_DCA']['tl_user']['fields']['leiterQualifikation'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['leiterQualifikation'],
    'exclude'   => true,
    'search'    => true,
    'filter'    => true,
    'inputType' => 'checkboxWizard',
    'options'   => $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['leiterQualifikation'],
    'eval'      => array('tl_class' => 'clr', 'multiple' => true, 'orderField' => 'orderLeiterQualifikation'),
    'sql'       => "blob NULL",
);

// role
$GLOBALS['TL_DCA']['tl_user']['fields']['role'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['role'],
    'reference' => &$GLOBALS['TL_LANG']['tl_user'],
    'exclude'   => true,
    'search'    => true,
    'filter'    => true,
    'inputType' => 'checkboxWizard',
    'options'   => $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['role'],
    'eval'      => array('tl_class' => 'clr', 'multiple' => true),
    'sql'       => "blob NULL",
);

// orderLeiterQualifikation
$GLOBALS['TL_DCA']['tl_user']['fields']['orderLeiterQualifikation'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['orderLeiterQualifikation-1'],
    'sql'   => "blob NULL",
);

// avatarSRC
$GLOBALS['TL_DCA']['tl_user']['fields']['avatarSRC'] = array(
    'label'         => &$GLOBALS['TL_LANG']['tl_user']['avatarSRC'],
    'exclude'       => true,
    'inputType'     => 'fileTree',
    'eval'          => array('doNotCopy' => true, 'filesOnly' => true, 'fieldType' => 'radio', 'mandatory' => false, 'tl_class' => ''),
    'load_callback' => array(
        array('tl_user_sac_event_tool', 'setSingleSrcFlags'),
    ),
    'sql'           => "binary(16) NULL",
);
