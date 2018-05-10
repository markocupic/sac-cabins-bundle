<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2018
 * @link https://sac-kurse.kletterkader.com
 */

namespace Markocupic\SacEventToolBundle\Maintenance;

use Contao\CalendarEventsStoryModel;
use Contao\Config;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Folder;
use Contao\MemberModel;
use Contao\System;
use Psr\Log\LogLevel;
use Contao\Database;


/**
 * Class MaintainModuleEventStory
 * @package Markocupic\SacEventToolBundle\Maintenance
 */
class MaintainModuleEventStory
{
    /**
     * Delete image upload folders that aren't assigned to an event story
     * @throws \Exception
     */
    public function run()
    {
        // Get the image upload path
        $eventStoriesUploadPath = Config::get('SAC_EVT_EVENT_STORIES_UPLOAD_PATH');

        // Get the logger class
        $level = LogLevel::INFO;
        $logger = System::getContainer()->get('monolog.logger.contao');

        // Get root dir
        $rootDir = System::getContainer()->getParameter('kernel.project_dir');
        $arrScan = scan($rootDir . '/' . $eventStoriesUploadPath);
        foreach ($arrScan as $folder)
        {
            if (is_dir($rootDir . '/' . $eventStoriesUploadPath . '/' . $folder))
            {
                $objFolder = new Folder($eventStoriesUploadPath . '/' . $folder);
                if (null === CalendarEventsStoryModel::findByPk($folder))
                {
                    $strText = sprintf('The folder "%s" has been deleted', $objFolder->path);
                    $logger->log($level, $strText, array('contao' => new ContaoContext(__METHOD__, $level)));
                    $objFolder->delete();
                }
            }
        }
    }
}