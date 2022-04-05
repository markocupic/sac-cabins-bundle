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
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\File\Metadata;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Markocupic\SacCabinsBundle\Model\SacCabinsModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * ContentElement("cabanne_sac_list", category="sac_event_tool_content_elements", template="ce_cabanne_sac_detail").
 *
 * @ContentElement(SacCabinsListController::TYPE, category="sac_cabins_content_elements", template="ce_sac_cabins_list")
 */
class SacCabinsListController extends AbstractContentElementController
{
    public const TYPE = 'sac_cabins_list';

    private ContaoFramework $framework;
    private Connection $connection;
    private Studio $studio;
    private Environment $twig;
    private string $projectDir;
    private ?SacCabinsModel $objSacCabin;

    public function __construct(ContaoFramework $framework, Connection $connection, Studio $studio, Environment $twig, string $projectDir)
    {
        $this->framework = $framework;
        $this->connection = $connection;
        $this->studio = $studio;
        $this->twig = $twig;
        $this->projectDir = $projectDir;
    }

    public function __invoke(Request $request, ContentModel $model, string $section, array $classes = null, PageModel $pageModel = null): Response
    {
        /** @var SacCabinsModel $sacCabinsModelAdapter */
        $sacCabinsModelAdapter = $this->framework->getAdapter(SacCabinsModel::class);

        // Add data to template
        if (null === ($this->objSacCabin = $sacCabinsModelAdapter->findByPk($model->sacCabin))) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    /**
     * @param Template $template
     * @param ContentModel $model
     * @param Request $request
     * @return Response|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        // Add data to template
        $template->cabin = $this->objSacCabin->row();

        $figure = $this->studio->createFigureBuilder()
            ->fromUuid($model->singleSRC)
            ->setSize($model->size)
            ->setMetadata(
                new Metadata(
                [
                    Metadata::VALUE_ALT => StringUtil::specialchars($this->objSacCabin->name),
                ]
            )
            )
            ->setLinkHref($model->jumpTo)
            ->buildIfResourceExists()
            ;

        if ($figure) {
            $template->figure = $this->twig->render('@ContaoCore/Image/Studio/figure.html.twig', ['figure' => $figure]);
        }

        return $template->getResponse();
    }
}
