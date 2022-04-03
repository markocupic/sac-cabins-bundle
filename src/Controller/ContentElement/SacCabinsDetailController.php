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

namespace Markocupic\SacCabinsBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\Template;
use Markocupic\SacCabinsBundle\Model\SacCabinsModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function in_array;

/**
 * ContentElement("cabanne_sac_detail", category="sac_event_tool_content_elements", template="ce_cabanne_sac_detail").
 *
 * @ContentElement(SacCabinsDetailController::TYPE, category="sac_cabins_content_elements", template="ce_sac_cabins_detail")
 */
class SacCabinsDetailController extends AbstractContentElementController
{
    public const TYPE = 'sac_cabins_detail';

    private ContaoFramework $framework;
    private string $projectDir;
    private ?SacCabinsModel $objSacCabins;

    public function __construct(ContaoFramework $framework, string $projectDir)
    {
        $this->framework = $framework;
        $this->projectDir = $projectDir;
    }

    public function __invoke(Request $request, ContentModel $model, string $section, array $classes = null, PageModel $pageModel = null): Response
    {
        /** @var SacCabinsModel $sacCabinsModelAdapter */
        $sacCabinsModelAdapter = $this->framework->getAdapter(SacCabinsModel::class);

        // Add data to template
        if (null === ($this->objSacCabins = $sacCabinsModelAdapter->findByPk($model->sacCabin))) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        /** @var FilesModel $filesModelAdapter */
        $filesModelAdapter = $this->framework->getAdapter(FilesModel::class);

        /** @var Controller $controllerAdapter */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        // Add data to template
        $skip = ['id', 'tstamp'];

        foreach ($this->objSacCabins->row() as $k => $v) {
            if (!in_array($k, $skip, true)) {
                $template->$k = $v;
            }
        }
        $objFiles = $filesModelAdapter->findByUuid($this->objSacCabins->singleSRC);

        if (null !== $objFiles && is_file($this->projectDir.'/'.$objFiles->path)) {
            $model->singleSRC = $objFiles->path;
            $controllerAdapter->addImageToTemplate($template, $model->row(), null, 'sacCabinsDetail', $objFiles);
        }

        // coordsCH1903
        if (!empty($this->objSacCabins->coordsCH1903)) {
            if (false !== strpos($this->objSacCabins->coordsCH1903, '/')) {
                $template->hasCoords = true;
                $arrCoord = explode('/', $this->objSacCabins->coordsCH1903);
                $template->coordsCH1903X = trim($arrCoord[0]);
                $template->coordsCH1903Y = trim($arrCoord[1]);
            }
        }

        return $template->getResponse();
    }
}
