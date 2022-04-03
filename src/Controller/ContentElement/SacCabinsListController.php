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
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ContentElement("cabanne_sac_list", category="sac_event_tool_content_elements", template="ce_cabanne_sac_detail")
 * @ContentElement(SacCabinsListController::TYPE, category="sac_cabins_content_elements", template="ce_sac_cabins_list")
 */
class SacCabinsListController extends AbstractContentElementController
{
    public const TYPE = 'sac_cabins_list';

    private ContaoFramework $framework;
    private Connection $connection;
    private string $projectDir;

    public function __construct(ContaoFramework $framework, Connection $connection, string $projectDir)
    {
        $this->framework = $framework;
        $this->connection = $connection;
        $this->projectDir = $projectDir;

    }

    public function __invoke(Request $request, ContentModel $model, string $section, array $classes = null, PageModel $pageModel = null): Response
    {
        return parent::__invoke($request, $model, $section, $classes);
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        /** @var FilesModel $filesModelAdapter */
        $filesModelAdapter = $this->framework->getAdapter(FilesModel::class);

        /** @var Controller $controllerAdapter */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        // Add data to template
        $row = $this->connection->fetchAssociative('SELECT * FROM tl_sac_cabins WHERE id = ?', [$model->cabanneSac]);

        if ($row) {
            $skip = ['id', 'tstamp', 'singleSRC'];

            foreach ($row as $k => $v) {
                if (!\in_array($k, $skip, true)) {
                    $template->$k = $v;
                }
            }
        }

        $objFiles = $filesModelAdapter->findByUuid($model->singleSRC);

        if (null !== $objFiles && is_file($this->projectDir.'/'.$objFiles->path)) {
            $model->singleSRC = $objFiles->path;
            $controllerAdapter->addImageToTemplate($template, $model->row(), null, 'sac_cabins_list', $objFiles);
        }

        return $template->getResponse();
    }
}
