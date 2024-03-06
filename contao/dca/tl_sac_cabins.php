<?php

declare(strict_types=1);

/*
 * This file is part of SAC Cabins Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/sac-cabins-bundle
 */

use Contao\Config;
use Contao\DC_Table;
use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_sac_cabins'] = [
    'config'   => [
        'dataContainer'    => DC_Table::class,
        'doNotCopyRecords' => true,
        'enableVersioning' => true,
        'switchToEdit'     => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list'     => [
        'sorting'           => [
            'mode'        => DataContainer::MODE_SORTABLE,
            'fields'      => ['name ASC'],
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label'             => [
            'fields'      => ['name'],
            'showColumns' => true,
        ],
        'global_operations' => [
            'all',
        ],
    ],
    'palettes' => [
        'default' => '{contact_legend},name,owner,canton,altitude,hutWarden,phone,email,url,bookingMethod;
        {capacity_legend},capacity,capacityShelterRoom;
        {image_legend},singleSRC;
        {details_legend},huettenchef,coordsCH1903,coordsWGS84,openingTime;
        {ascent_legend},ascent',
    ],

    'fields' => [
        'id'                  => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp'              => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'name'                => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'owner'               => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'canton'              => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'altitude'            => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'natural', 'mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'hutWarden'           => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'textarea',
            'eval'      => ['rgxp' => '', 'mandatory' => true, 'maxlength' => 512, 'tl_class' => 'clr'],
            'sql'       => "varchar(512) NOT NULL default ''",
        ],
        'phone'               => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'phone', 'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'email'               => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'email', 'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'bookingMethod'       => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory' => false, 'maxlength' => 512, 'tl_class' => 'clr'],
            'sql'       => "varchar(512) NOT NULL default ''",
        ],
        'url'                 => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'url', 'mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'singleSRC'           => [
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => ['fieldType' => 'radio', 'filesOnly' => true, 'extensions' => Config::get('validImageTypes'), 'mandatory' => true],
            'sql'       => 'binary(16) NULL',
        ],
        'huettenchef'         => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory' => true, 'maxlength' => 512, 'tl_class' => 'clr'],
            'sql'       => "varchar(512) NOT NULL default ''",
        ],
        'capacity'            => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'options'   => range(0, 200),
            'inputType' => 'select',
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'capacityShelterRoom' => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'options'   => range(0, 200),
            'inputType' => 'select',
            'eval'      => ['includeBlankOption' => true, 'mandatory' => false, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'coordsCH1903'        => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'coordsWGS84'         => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'openingTime'         => [
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory' => true, 'maxlength' => 512, 'tl_class' => 'clr'],
            'sql'       => "varchar(512) NOT NULL default ''",
        ],
        'ascent'              => [
            'label'     => &$GLOBALS['TL_LANG']['tl_sac_cabins']['ascent'],
            'exclude'   => true,
            'inputType' => 'multiColumnWizard',
            'eval'      => [
                'columnFields' => [
                    'ascentDescription' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_sac_cabins']['ascentDescription'],
                        'exclude'   => true,
                        'inputType' => 'textarea',
                        'eval'      => ['style' => 'width:150px'],
                    ],
                    'ascentTime'        => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_sac_cabins']['ascentTime'],
                        'exclude'   => true,
                        'inputType' => 'text',
                        'eval'      => ['style' => 'width:80px'],
                    ],
                    'ascentDifficulty'  => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_sac_cabins']['ascentDifficulty'],
                        'exclude'   => true,
                        'inputType' => 'textarea',
                        'eval'      => ['style' => 'width:80px'],
                    ],
                    'ascentSummer'      => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_sac_cabins']['ascentSummer'],
                        'exclude'   => true,
                        'inputType' => 'select',
                        'options'   => ['possible', 'not-possible'],
                        'reference' => &$GLOBALS['TL_LANG']['tl_sac_cabins'],
                        'eval'      => ['style' => 'width:50px'],
                    ],
                    'ascentWinter'      => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_sac_cabins']['ascentWinter'],
                        'exclude'   => true,
                        'inputType' => 'select',
                        'options'   => ['possible', 'not-possible'],
                        'reference' => &$GLOBALS['TL_LANG']['tl_sac_cabins'],
                        'eval'      => ['style' => 'width:50px'],
                    ],
                    'ascentComment'     => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_sac_cabins']['ascentComment'],
                        'exclude'   => true,
                        'inputType' => 'textarea',
                        'eval'      => ['style' => 'width:150px'],
                    ],
                ],
            ],
            'sql'       => 'blob NULL',
        ],
    ],
];
