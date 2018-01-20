<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2018
 * @link https://sac-kurse.kletterkader.com
 */


if (Input::get('do') == 'sac_calendar_events_tool')
{
    $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_calendar_events';
    $GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_calendar', 'checkPermission');
    $GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_calendar', 'generateFeed');
    $GLOBALS['TL_DCA']['tl_content']['list']['operations']['toggle']['button_callback'] = array('tl_content_calendar', 'toggleIcon');
}

$GLOBALS['TL_DCA']['tl_content']['palettes']['ce_user_portrait'] = 'name,type,headline;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['cabanneSacDetail'] = '{type_legend},type,headline,cabanne;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';


// Fields
$GLOBALS['TL_DCA']['tl_content']['fields']['cabanneSac'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['cabanneSac'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'select',
    'options'   => array('tl_content_sac_event_tool', 'getCabannes'),
    'eval'      => array('mandatory' => true, 'maxlength' => 200, 'tl_class' => 'w50 clr'),
    'sql'       => "int(10) unsigned NOT NULL default '0'",
);

