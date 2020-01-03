<?php

declare(strict_types=1);

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2020 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2020
 * @link https://github.com/markocupic/sac-event-tool-bundle
 */

namespace Markocupic\SacEventToolBundle\Controller\Download;

use Contao\CalendarEventsModel;
use Contao\Config;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Date;
use Contao\System;
use Markocupic\SacEventToolBundle\Services\Docx\ExportEvents2Docx;
use Markocupic\SacEventToolBundle\Services\Ical\SendEventIcal;
use Markocupic\SacEventToolBundle\Services\Pdf\PrintWorkshopsAsPdf;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DownloadController
 * @package Markocupic\SacEventToolBundle\Controller\Download
 */
class DownloadController extends AbstractController
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * DownloadController constructor.
     * @param ContaoFramework $framework
     * @param RequestStack $requestStack
     */
    public function __construct(ContaoFramework $framework, RequestStack $requestStack)
    {
        $this->framework = $framework;
        $this->requestStack = $requestStack;

        $this->framework->initialize();
    }

    /**
     * Download workshops as pdf booklet
     * /_download/print_workshop_booklet_as_pdf?year=2019&cat=0
     * /_download/print_workshop_booklet_as_pdf?year=current&cat=0
     * @Route("/_download/print_workshop_booklet_as_pdf", name="sac_event_tool_download_print_workshop_booklet_as_pdf", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function printWorkshopBookletAsPdfAction()
    {
        /** @var $pdf PrintWorkshopsAsPdf */
        $pdf = System::getContainer()->get('Markocupic\SacEventToolBundle\Services\Pdf\PrintWorkshopsAsPdf');

        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        /** @var Date $dateAdapter */
        $dateAdapter = $this->framework->getAdapter(Date::class);

        /** @var Config $configAdapter */
        $configAdapter = $this->framework->getAdapter(Config::class);

        $year = $request->query->get('year') != '' ? (int)$request->query->get('year') : null;
        $calendarId = $request->query->get('calendarId') != '' ? (int)$request->query->get('calendarId') : null;

        if (!empty($year))
        {
            if ($year == 'current')
            {
                $year = (int)$dateAdapter->parse('Y');
            }
            $pdf = $pdf->setYear($year);
        }

        if (!empty($calendarId))
        {
            $pdf = $pdf->setCalendarId($calendarId);
        }

        $pdf->setDownload(true);

        // Log download
        $container = System::getContainer();
        $logger = $container->get('monolog.logger.contao');
        $logger->log(LogLevel::INFO, 'The course booklet has been downloaded.', array('contao' => new ContaoContext(__METHOD__, $configAdapter->get('SAC_EVT_LOG_COURSE_BOOKLET_DOWNLOAD'))));

        $pdf->printWorkshopsAsPdf();

        exit();
    }

    /**
     * Download events as docx file
     * /_download/print_workshop_details_as_docx?calendarId=6&year=2017
     * /_download/print_workshop_details_as_docx?calendarId=6&year=2017&eventId=89
     * @Route("/_download/print_workshop_details_as_docx", name="sac_event_tool_download_print_workshop_details_as_docx", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function printWorkshopDetailsAsDocxAction()
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        /** @var  ExportEvents2Docx $exportEvents2DocxAdapter */
        $exportEvents2DocxAdapter = $this->framework->getAdapter(ExportEvents2Docx::class);

        if ($request->query->get('year') && $request->query->get('calendarId'))
        {
            $exportEvents2DocxAdapter->sendToBrowser($request->query->get('calendarId'), $request->query->get('year'), $request->query->get('eventId'));
        }
        exit();
    }

    /**
     * Download workshop details as pdf
     * /_download/print_workshop_details_as_pdf?eventId=643
     * @Route("/_download/print_workshop_details_as_pdf", name="sac_event_tool_download_print_workshop_details_as_pdf", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function printWorkshopDetailsAsPdfAction()
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        /** @var $pdf PrintWorkshopsAsPdf */
        $pdf = System::getContainer()->get('Markocupic\SacEventToolBundle\Services\Pdf\PrintWorkshopsAsPdf');

        $eventId = $request->query->get('eventId') ? (int)$request->query->get('eventId') : null;

        if ($eventId !== null)
        {
            $pdf->setEventId($eventId);
        }

        $pdf->setDownload(true);
        $pdf->printWorkshopsAsPdf();
        exit();
    }

    /**
     * Send ical to the browser
     * @Route("/_download/download_event_ical", name="sac_event_tool_download_download_event_ical", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function downloadEventIcalAction()
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        /** @var  CalendarEventsModel $calendarEventsModelAdapter */
        $calendarEventsModelAdapter = $this->framework->getAdapter(CalendarEventsModel::class);

        // Course Filter
        if ($request->query->get('eventId') > 0)
        {
            $objEvent = $calendarEventsModelAdapter->findByPk($request->query->get('eventId'));
            {
                if ($objEvent !== null)
                {
                    /** @var  SendEventIcal $ical */
                    $ical = System::getContainer()->get('Markocupic\SacEventToolBundle\Services\Ical\SendEventIcal');
                    $ical->sendIcsFile($objEvent);
                }
            }
        }
        exit();
    }

    /**
     * The defaultAction has to be at the bottom of the class
     * Handles download requests.
     * @Route("/_download/{slug}", name="sac_event_tool_download", defaults={"_scope" = "frontend", "_token_check" = false})
     */
    public function defaultAction($slug = '')
    {
        echo sprintf('Welcome to %s::%s. You have called the Service with this route: _download/%s', __CLASS__, __FUNCTION__, $slug);
        exit();
    }
}