<?php

declare(strict_types=1);

/*
 * This file is part of SAC Cabins Bundle.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/sac-cabins-bundle
 */

namespace Markocupic\SacCabinsBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Exception\ForwardPageNotFoundException;
use Contao\CoreBundle\File\Metadata;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\PageModel;
use Markocupic\SacCabinsBundle\Model\SacCabinsModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ExceptionInterface;

#[AsContentElement(SacCabinsListController::TYPE, category: 'sac_cabins_content_elements')]
class SacCabinsListController extends AbstractContentElementController
{
    public const TYPE = 'sac_cabins_list';

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Studio $studio,
        private readonly ContentUrlGenerator $contentUrlGenerator,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $cabin = $this->framework->getAdapter(SacCabinsModel::class)->findById((int) $model->sacCabin);

        if (null === $cabin) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $href = null;

        if ($model->jumpTo && ($page = $this->framework->getAdapter(PageModel::class)->findById((int) $model->jumpTo)) instanceof PageModel) {
            try {
                $href = $this->contentUrlGenerator->generate($page);
            } catch (ExceptionInterface|ForwardPageNotFoundException) {
                // The target page cannot be routed (e.g. a forward page without target)
            }
        }

        $template->set('cabin', $cabin->row());
        $template->set('href', $href);
        $template->set('figure', null);

        if ($model->singleSRC) {
            $figureBuilder = $this->studio
                ->createFigureBuilder()
                ->fromUuid($model->singleSRC)
                ->setSize($model->size)
                ->setMetadata($model->overwriteMeta ? $model->getOverwriteMetadata() : new Metadata([Metadata::VALUE_ALT => (string) $cabin->name]))
            ;

            if (null !== $href) {
                $figureBuilder->setLinkHref($href);
            } else {
                $figureBuilder->enableLightbox((bool) $model->fullsize);
            }

            $template->set('figure', $figureBuilder->buildIfResourceExists());
        }

        return $template->getResponse();
    }
}
