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

use Markocupic\SacEventToolBundle\Dca\TlEventType;

/*
 * Table tl_event_type
 */
$GLOBALS['TL_DCA']['tl_event_type'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['title'],
            'flag' => 1,
            'panelLayout' => 'filter;search,limit',
        ],
        'label' => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_event_type']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_event_type']['copy'],
                'href' => 'act=paste&amp;mode=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_event_type']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_event_type']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},alias,title;{release_level_legend},levelAccessPermissionPackage;{preview_page_legend},previewPage;',
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'alias' => [
            'label' => &$GLOBALS['TL_LANG']['tl_event_type']['alias'],
            'inputType' => 'text',
            'exclude' => true,
            'search' => true,
            'flag' => 1,
            'load_callback' => [
                [
                    TlEventType::class,
                    'loadCallbackAlias',
                ],
            ],
            'eval' => [
                'mandatory' => true,
                'rgxp' => 'alnum',
                'maxlength' => 128,
                'tl_class' => 'w50',
            ],
            'sql' => 'varchar(128) NULL',
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_event_type']['title'],
            'inputType' => 'text',
            'exclude' => true,
            'search' => true,
            'flag' => 1,
            'eval' => [
                'mandatory' => true,
                'maxlength' => 128,
                'tl_class' => 'w50',
            ],
            'sql' => 'varchar(128) NULL',
        ],
        'levelAccessPermissionPackage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_event_type']['levelAccessPermissionPackage'],
            'exclude' => true,
            'inputType' => 'select',
            'relation' => [
                'type' => 'belongsTo',
                'load' => 'eager',
            ],
            'foreignKey' => 'tl_event_release_level_policy_package.title',
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'eval' => [
                'includeBlankOption' => true,
                'mandatory' => true,
                'tl_class' => 'clr',
            ],
        ],
        'previewPage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_event_type']['previewPage'],
            'exclude' => true,
            'inputType' => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval' => [
                'mandatory' => true,
                'fieldType' => 'radio',
                'tl_class' => 'clr',
            ],
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => [
                'type' => 'hasOne',
                'load' => 'lazy',
            ],
        ],
    ],
];
