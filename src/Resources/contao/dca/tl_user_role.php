<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2018
 * @link https://sac-kurse.kletterkader.com
 */


$GLOBALS['TL_DCA']['tl_user_role'] = array
(
    'config'   => array
    (
        'dataContainer'    => 'Table',
        'doNotCopyRecords' => true,
        'enableVersioning' => true,
        'switchToEdit'     => true,
        'sql'              => array
        (
            'keys' => array
            (
                'id' => 'primary',
            ),
        ),
    ),
    'list'     => array
    (
        'sorting'           => array
        (
            'mode'                  => 5,
            'fields'                => array('title'),
            'flag'                  => 1,
            'panelLayout'           => 'filter;search,limit',
            'paste_button_callback' => array('tl_user_role', 'pasteTag'),
        ),
        'label'             => array
        (
            'fields' => array('title'),
            'format' => '%s',
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ),
        ),
        'operations'        => array
        (
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_user_role']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'copy'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_user_role']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ),
            'cut'    => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_tour_type']['cut'],
                'href'       => 'act=paste&mode=cut',
                'icon'       => 'cut.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ),
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_user_role']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
            ),
        ),
    ),
    'palettes' => array
    (
        'default' => 'title,belongsToExecutiveBoard',
    ),

    'fields' => array
    (
        'id'                  => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ),
        'pid'                 => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'sorting'             => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_user_role']['sorting'],
            'exclude'   => true,
            'search'    => false,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'rgxp' => 'natural', 'maxlength' => 10),
            'sql'       => "int(10) unsigned NOT NULL default '0'",
        ),
        'tstamp'              => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'title'               => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_user_role']['title'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'belongsToExecutiveBoard' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_user_role']['belongsToExecutiveBoard'],
            'exclude'   => true,
            'filter'    => true,
            'sorting'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('mandatory' => false, 'tl_class' => 'clr'),
            'sql'       => "char(1) NOT NULL default ''",
        ),
    ),
);
