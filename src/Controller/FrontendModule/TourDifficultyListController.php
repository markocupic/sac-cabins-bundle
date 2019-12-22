<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2020 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2020
 * @link https://github.com/markocupic/sac-event-tool-bundle
 */

namespace Markocupic\SacEventToolBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\ModuleModel;
use Contao\Template;
use Contao\TourDifficultyCategoryModel;
use Contao\TourDifficultyModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;


/**
 * Class TourDifficultyListController
 * @package Markocupic\SacEventToolBundle\Controller\FrontendModule
 * @FrontendModule(category="sac_event_tool_fe_modules", type="tour_difficulty_list")
 */
class TourDifficultyListController extends AbstractFrontendModuleController
{

    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * TourDifficultyListController constructor.
     * @param ContaoFramework $framework
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    /**
     * @param Template $template
     * @param ModuleModel $model
     * @param Request $request
     * @return null|Response
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $arrDifficulty = [];
        $pid = 0;
        $options = array('order' => 'code ASC');
        $tourDifficultyAdapter = $this->framework->getAdapter(TourDifficultyModel::class);
        $objDifficulty = $tourDifficultyAdapter->findAll($options);

        if ($objDifficulty !== null)
        {
            while ($objDifficulty->next())
            {
                if ($pid !== $objDifficulty->pid)
                {
                    $objDifficulty->catStart = true;
                    $tourDifficultyCategoryAdapter = $this->framework->getAdapter(TourDifficultyCategoryModel::class);
                    $objDifficultyCategory = $tourDifficultyCategoryAdapter->findByPk($objDifficulty->pid);
                    if ($objDifficultyCategory !== null)
                    {
                        $objDifficulty->catTitle = $objDifficultyCategory->title;
                    }
                }
                $pid = $objDifficulty->pid;
                $arrDifficulty[] = $objDifficulty->row();
            }
        }

        $template->difficulties = $arrDifficulty;

        return $template->getResponse();
    }
}