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
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
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

/**
 * ContentElement("cabanne_sac_detail", category="sac_event_tool_content_elements", template="ce_cabanne_sac_detail").
 *
 * @ContentElement(SacCabinsDetailController::TYPE, category="sac_cabins_content_elements", template="ce_sac_cabins_detail")
 */
class SacCabinsDetailController extends AbstractContentElementController
{
    public const TYPE = 'sac_cabins_detail';

    private ContaoFramework $framework;
    private Studio $studio;
    private InsertTagParser $insertTagParser;
    private Environment $twig;
    private string $projectDir;
    private string $geoLink;
    private ?SacCabinsModel $objSacCabin;

    public function __construct(ContaoFramework $framework, Studio $studio, InsertTagParser $insertTagParser, Environment $twig, string $projectDir, string $geoLink)
    {
        $this->framework = $framework;
        $this->studio = $studio;
        $this->insertTagParser = $insertTagParser;
        $this->twig = $twig;
        $this->projectDir = $projectDir;
        $this->geoLink = $geoLink;
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        /** @var StringUtil $stringUtilAdapter */
        $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

        $row = $this->objSacCabin->row();

        // geo link
        $row['geoLink'] = $this->geoLink;

        // encode email
        if ('' !== $row['email']) {
            $row['email'] = $this->insertTagParser->replaceInline('{{email::'.$row['email'].'}}');
        }

        // ascent variants
        $row['ascents'] = $stringUtilAdapter->deserialize($this->objSacCabin->ascent, true);

        // coordsCH1903
        if (!empty($this->objSacCabin->coordsCH1903)) {
            if (false !== strpos($this->objSacCabin->coordsCH1903, '/')) {
                $arrCoord = explode('/', $this->objSacCabin->coordsCH1903);

                if (\is_array($arrCoord) && 2 === \count($arrCoord)) {
                    $row['hasCoords'] = true;
                    $arrCoord =
                        $row['coordsCH1903X'] = trim($arrCoord[0]);
                    $row['coordsCH1903Y'] = trim($arrCoord[1]);
                }
            }
        }

        // add picture
        $figure = $this->studio->createFigureBuilder()
            ->fromUuid($model->singleSRC)
            ->setSize($model->size)
            ->setMetadata(
                new Metadata(
                    [
                        Metadata::VALUE_ALT => $stringUtilAdapter->specialchars($this->objSacCabin->name),
                    ]
                )
            )
            ->setLinkHref($model->jumpTo)
            ->buildIfResourceExists()
        ;

        if ($figure) {
            $template->figure = $this->twig->render('@ContaoCore/Image/Studio/figure.html.twig', ['figure' => $figure]);
        }

        // Add data to template
        $template->cabin = $row;

        return $template->getResponse();
    }
}
