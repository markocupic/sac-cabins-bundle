<?php

declare(strict_types=1);

/*
 * This file is part of SAC Cabins Bundle.
 *
 * (c) Marko Cupic 2022 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/sac-cabins-bundle
 */

use Contao\System;
use Markocupic\SacCabinsBundle\Model\SacCabinsModel;

$GLOBALS['BE_MOD']['sac_be_modules']['sac_cabins_tool'] = [
    'tables' => ['tl_sac_cabins'],
];

$GLOBALS['TL_MODELS']['tl_sac_cabins'] = SacCabinsModel::class;


$set = [
    'type' => 'sac_cabins_list'
];
$connection = System::getContainer()->get('database_connection');
$connection->update('tl_content',$set,['type' => 'cabanne_sac_list']);

$set = [
    'type' => 'sac_cabins_detail'
];
$connection->update('tl_content',$set,['type' => 'cabanne_sac_detail']);

