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
use Contao\CoreBundle\File\Metadata;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Markocupic\SacCabinsBundle\Model\SacCabinsModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(SacCabinsDetailController::TYPE, category: 'sac_cabins_content_elements')]
class SacCabinsDetailController extends AbstractContentElementController
{
    public const TYPE = 'sac_cabins_detail';

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Studio $studio,
        private readonly InsertTagParser $insertTagParser,
        #[Autowire('%markocupic_sac_cabins.geo_link%')]
        private readonly string $geoLink,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $cabin = $this->framework->getAdapter(SacCabinsModel::class)->findById((int) $model->sacCabin);

        if (null === $cabin) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $stringUtil = $this->framework->getAdapter(StringUtil::class);

        $row = $cabin->row();
        $row['geoLink'] = $this->geoLink;
        $row['url'] = $stringUtil->decodeEntities((string) $cabin->url);
        $row['ascents'] = $stringUtil->deserialize($cabin->ascent, true);
        $row['hasCoords'] = false;

        // Encode the email address
        if ('' !== (string) $cabin->email) {
            $row['email'] = $this->insertTagParser->replaceInline('{{email::'.$cabin->email.'}}');
        }

        // Coordinates CH1903
        $coords = explode('/', (string) $cabin->coordsCH1903);

        if (2 === \count($coords) && '' !== trim($coords[0]) && '' !== trim($coords[1])) {
            $row['hasCoords'] = true;
            $row['coordsCH1903X'] = trim($coords[0]);
            $row['coordsCH1903Y'] = trim($coords[1]);
        }

        $template->set('cabin', $row);
        $template->set('figure', null);

        if ($model->singleSRC) {
            $template->set('figure', $this->studio
                ->createFigureBuilder()
                ->fromUuid($model->singleSRC)
                ->setSize($model->size)
                ->setMetadata($model->overwriteMeta ? $model->getOverwriteMetadata() : new Metadata([Metadata::VALUE_ALT => (string) $cabin->name]))
                ->enableLightbox((bool) $model->fullsize)
                ->buildIfResourceExists(),
            );
        }

        return $template->getResponse();
    }
}
