<?php

declare(strict_types=1);

/*
 * This file is part of SAC Cabins Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/sac-cabins-bundle
 */

namespace Markocupic\SacCabinsBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\File\Metadata;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Template;
use Markocupic\SacCabinsBundle\Model\SacCabinsModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsContentElement(SacCabinsListController::TYPE, category:'sac_cabins_content_elements', template:'ce_sac_cabins_list')]
class SacCabinsListController extends AbstractContentElementController
{
    public const TYPE = 'sac_cabins_list';

    private SacCabinsModel|null $objSacCabin = null;
    private Adapter $pageAdapter;
    private Adapter $sacCabinsAdapter;

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Studio $studio,
        private readonly Environment $twig,
    ) {
        $this->pageAdapter = $this->framework->getAdapter(PageModel::class);
        $this->sacCabinsAdapter = $this->framework->getAdapter(SacCabinsModel::class);
    }

    public function __invoke(Request $request, ContentModel $model, string $section, array $classes = null, PageModel $pageModel = null): Response
    {
        // Add data to template
        if (null === ($this->objSacCabin = $this->sacCabinsAdapter->findByPk($model->sacCabin))) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        // Add data to template
        $template->cabin = $this->objSacCabin->row();

        $figureBuilder = $this->studio->createFigureBuilder()
            ->fromUuid($model->singleSRC)
            ->setSize($model->size)
            ->setMetadata(
                new Metadata(
                    [
                        Metadata::VALUE_ALT => StringUtil::specialchars($this->objSacCabin->name),
                    ]
                )
            )
            ;

        if($model->jumpTo){

            /** @var PageModel|null $page */
            $page = $this->pageAdapter->findByPk($model->jumpTo);

            if(null !== $page)
            {
                $figureBuilder->setLinkHref($page->getFrontendUrl());
                $template->href = $page->getFrontendUrl();
            }
        }

        $figure = $figureBuilder->buildIfResourceExists();

        if ($figure) {
            $template->figure = $this->twig->render('@ContaoCore/Image/Studio/figure.html.twig', ['figure' => $figure]);
        }

        return $template->getResponse();
    }
}
