<?php

declare(strict_types=1);

/*
 * This file is part of SAC Cabins Bundle.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/sac-cabins-bundle
 */

use Markocupic\SacCabinsBundle\Controller\ContentElement\SacCabinsDetailController;
use Markocupic\SacCabinsBundle\Controller\ContentElement\SacCabinsListController;

// Palettes
$GLOBALS['TL_DCA']['tl_content']['palettes'][SacCabinsListController::TYPE] = '
{type_legend},type,headline,sacCabin;
{image_legend},singleSRC,size,imagemargin,fullsize,overwriteMeta;
{link_legend},jumpTo;
{template_legend:hide},customTpl;
{protected_legend:hide},protected;
{expert_legend:hide},guests,cssID;
{invisible_legend:hide},invisible,start,stop
';

$GLOBALS['TL_DCA']['tl_content']['palettes'][SacCabinsDetailController::TYPE] = '
{type_legend},type,headline,sacCabin;
{image_legend},singleSRC,size,imagemargin,fullsize,overwriteMeta;
{template_legend:hide},customTpl;
{protected_legend:hide},protected;
{expert_legend:hide},guests,cssID;
{invisible_legend:hide},invisible,start,stop
';

// Fields
$GLOBALS['TL_DCA']['tl_content']['fields']['sacCabin'] = [
    'exclude'    => true,
    'search'     => true,
    'inputType'  => 'select',
    'foreignKey' => 'tl_sac_cabins.name',
    'relation'   => ['type' => 'belongsTo', 'load' => 'eager'],
    'eval'       => ['mandatory' => true, 'maxlength' => 200, 'tl_class' => 'w50 clr'],
    'sql'        => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo'] = [
    'exclude'    => true,
    'search'     => true,
    'inputType'  => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval'       => ['mandatory' => true, 'fieldType' => 'radio', 'tl_class' => 'w50 wizard'],
    'sql'        => "int(10) unsigned NOT NULL default 0",
    'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
];
