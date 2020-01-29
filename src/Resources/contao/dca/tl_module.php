<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2020 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2020
 * @link https://github.com/markocupic/sac-event-tool-bundle
 */

/**
 * Table tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventToolCalendarEventStoryList'] = '{title_legend},name,headline,type;{config_legend},story_eventOrganizers;{jumpTo_legend},jumpTo;{pagination_legend},story_limit,perPage;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['eventToolCalendarEventPreviewReader'] = '{title_legend},name,headline,type;{template_legend:hide},cal_template,customTpl;{image_legend},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

// Contao 5 ready
$GLOBALS['TL_DCA']['tl_module']['palettes']['tour_difficulty_list'] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['csv_event_member_export'] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['event_registration_form'] = '{title_legend},name,headline,type;{jumpTo_legend},jumpTo;{notification_legend},receiptEventRegistrationNotificationId;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['activate_member_account'] = '{title_legend},name,headline,type;{account_legend},reg_groups;cc{notification_legend},activateMemberAccountNotificationId;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['member_dashboard_upcoming_events'] = '{title_legend},name,headline,type;{member_dashboard_upcoming_events_legend},unregisterFromEventNotificationId;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['member_dashboard_past_events'] = '{title_legend},name,headline,type;{member_dashboard_event_type_filter_legend},eventType;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['member_dashboard_event_report_list'] = '{title_legend},name,headline,type;{events_story_legend},timeSpanForCreatingNewEventStory,eventStoryFormJumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['member_dashboard_write_event_report'] = '{title_legend},name,headline,type;{events_story_legend},timeSpanForCreatingNewEventStory,notifyOnEventStoryPublishedNotificationId,eventStoryUploadFolder;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['member_dashboard_edit_profile'] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['member_dashboard_delete_profile'] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['member_dashboard_avatar_upload'] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['csv_export'] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['event_filter_form'] = '{title_legend},name,headline,type;{config_legend},eventFilterBoardFields;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['pilatus_export'] = '{title_legend},name,headline,type,print_export_allowedEventTypes;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['jahresprogramm_export'] = '{title_legend},name,headline,type,print_export_allowedEventTypes;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['event_story_list'] = '{title_legend},name,headline,type;{config_legend},numberOfItems,skipFirst,perPage;{template_legend:hide},eventStoryListTemplate;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['event_story_reader'] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['event_list'] = '{title_legend},name,headline,type;{config_legend},cal_calendar,eventType,cal_readerModule,eventListLimitPerRequest;{template_legend:hide},eventListPartialTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

// Manipulate palettes
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addField(['jumpToWhenNotActivated'], 'redirect_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_PREPEND)
    ->applyToPalette('login', 'tl_module');

// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['eventType'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['eventType'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'select',
    'options'   => $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['EVENT-TYPE'],
    'eval'      => ['mandatory' => true, 'multiple' => true, 'includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'],
    'sql'       => "blob NULL",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['unregisterFromEventNotificationId'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['unregisterFromEventNotificationId'],
    'exclude'    => true,
    'search'     => true,
    'inputType'  => 'select',
    'foreignKey' => 'tl_nc_notification.title',
    'eval'       => ['mandatory' => true, 'includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['receiptEventRegistrationNotificationId'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['receiptEventRegistrationNotificationId'],
    'exclude'    => true,
    'search'     => true,
    'inputType'  => 'select',
    'foreignKey' => 'tl_nc_notification.title',
    'eval'       => ['mandatory' => true, 'includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['notifyOnEventStoryPublishedNotificationId'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['notifyOnEventStoryPublishedNotificationId'],
    'exclude'    => true,
    'search'     => true,
    'inputType'  => 'select',
    'foreignKey' => 'tl_nc_notification.title',
    'eval'       => ['mandatory' => true, 'includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['activateMemberAccountNotificationId'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['activateMemberAccountNotificationId'],
    'exclude'    => true,
    'search'     => true,
    'inputType'  => 'select',
    'foreignKey' => 'tl_nc_notification.title',
    'eval'       => ['mandatory' => true, 'includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['eventStoryFormJumpTo'] = [

    'label'      => &$GLOBALS['TL_LANG']['tl_module']['eventStoryFormJumpTo'],
    'exclude'    => true,
    'inputType'  => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval'       => ['mandatory' => true, 'fieldType' => 'radio', 'tl_class' => 'clr'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => ['type' => 'hasOne', 'load' => 'eager'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['eventStoryJumpTo'] = [

    'label'      => &$GLOBALS['TL_LANG']['tl_module']['eventStoryJumpTo'],
    'exclude'    => true,
    'inputType'  => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval'       => ['mandatory' => true, 'fieldType' => 'radio', 'tl_class' => 'clr'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => ['type' => 'hasOne', 'load' => 'eager'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['eventStoryUploadFolder'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['eventStoryUploadFolder'],
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => ['fieldType' => 'radio', 'filesOnly' => false, 'mandatory' => true, 'tl_class' => 'clr'],
    'sql'       => "binary(16) NULL"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['timeSpanForCreatingNewEventStory'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['timeSpanForCreatingNewEventStory'],
    'inputType' => 'select',
    'options'   => range(5, 365),
    'eval'      => ['mandatory' => true, 'includeBlankOption' => false, 'tl_class' => 'clr', 'rgxp' => 'natural'],
    'sql'       => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['story_limit'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['story_limit'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['rgxp' => 'natural', 'tl_class' => 'w50'],
    'sql'       => "smallint(5) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['story_eventOrganizers'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['story_eventOrganizers'],
    'exclude'    => true,
    'search'     => true,
    'filter'     => true,
    'sorting'    => true,
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_event_organizer.title',
    'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
    'eval'       => ['multiple' => true, 'mandatory' => false, 'tl_class' => 'clr m12'],
    'sql'        => "blob NULL",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['print_export_allowedEventTypes'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_module']['print_export_allowedEventTypes'],
    'inputType' => 'select',
    'options'   => $GLOBALS['TL_CONFIG']['SAC-EVENT-TOOL-CONFIG']['EVENT-TYPE'],
    'eval'      => ['mandatory' => false, 'multiple' => true, 'chosen' => true, 'tl_class' => 'clr'],
    'sql'       => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['jumpToWhenNotActivated'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['jumpToWhenNotActivated'],
    'exclude'    => true,
    'inputType'  => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval'       => ['fieldType' => 'radio'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
    'relation'   => ['type' => 'hasOne', 'load' => 'eager']
];

$GLOBALS['TL_DCA']['tl_module']['fields']['eventFilterBoardFields'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['eventFilterBoardFields'],
    'inputType'        => 'checkboxWizard',
    'options_callback' => ['tl_module_sac_event_tool', 'getEventFilterBoardFields'],
    'eval'             => ['mandatory' => false, 'multiple' => true, 'ooorderField' => 'orderSRC', 'tl_class' => 'clr'],
    'sql'              => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['eventListPartialTpl'] = [
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => ['tl_module_sac_event_tool', 'getEventListTemplates'],
    'eval'             => ['tl_class' => 'w50'],
    'sql'              => "varchar(64) NOT NULL default 'event_list_partial_tour'"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['eventListLimitPerRequest'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['rgxp' => 'natural', 'tl_class' => 'w50'],
    'sql'       => "smallint(5) unsigned NOT NULL default 0"
];



