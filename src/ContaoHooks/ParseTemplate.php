<?php

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2018
 * @link https://sac-kurse.kletterkader.com
 */

namespace Markocupic\SacEventToolBundle\ContaoHooks;

use Contao\MemberModel;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Controller;


/**
 * Class ParseTemplate
 * @package Markocupic\SacEventToolBundle\ContaoHooks
 */
class ParseTemplate
{
    /**
     * @param $objTemplate
     */
    public function checkIfAccountIsActivated($objTemplate)
    {

        // Check if login is allowed, if not replace the default error message
        if (TL_MODE === 'FE')
        {
            if ($objTemplate->getName() === 'mod_login')
            {
                if ($objTemplate->value !== '' && $objTemplate->hasError === true)
                {
                    $objMember = MemberModel::findByUsername($objTemplate->value);
                    if ($objMember !== null)
                    {
                        if (!$objMember->login)
                        {
                            // Redirect to account activation page if it is set
                            $objLoginModule = ModuleModel::findByPk($objTemplate->id);
                            if ($objLoginModule !== null)
                            {
                                if ($objLoginModule->jumpToWhenNotActivated > 0)
                                {
                                    $objPage = PageModel::findByPk($objLoginModule->jumpToWhenNotActivated);
                                    if ($objPage !== null)
                                    {
                                        $url = $objPage->getFrontendUrl();
                                        Controller::redirect($url);
                                    }
                                }
                            }
                            $objTemplate->message = $GLOBALS['TL_LANG']['ERR']['memberAccountNotActivated'];
                        }
                    }
                    else
                    {
                        $objTemplate->message = sprintf($GLOBALS['TL_LANG']['ERR']['memberAccountNotFound'], $objTemplate->value);
                    }
                }
            }
        }
    }
}