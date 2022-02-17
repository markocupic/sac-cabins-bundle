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

namespace Markocupic\SacEventToolBundle\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;

class TourDifficulty
{
    /**
     * @Callback(table="tl_tour_difficulty", target="list.sorting.child_record")
     */
    public function listDifficulties(array $row): string
    {
        return '<div class="tl_content_left"><span class="level">'.$row['title'].'</span> '.$row['shortcut']."</div>\n";
    }
}
