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

namespace Markocupic\SacCabinsBundle\Tests\DependencyInjection;

use Markocupic\SacCabinsBundle\Controller\ContentElement\SacCabinsDetailController;
use Markocupic\SacCabinsBundle\Controller\ContentElement\SacCabinsListController;
use Markocupic\SacCabinsBundle\DependencyInjection\MarkocupicSacCabinsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MarkocupicSacCabinsExtensionTest extends TestCase
{
    public function testRegistersTheContentElementControllers(): void
    {
        $container = new ContainerBuilder();

        (new MarkocupicSacCabinsExtension())->load([], $container);

        $this->assertTrue($container->hasDefinition(SacCabinsDetailController::class));
        $this->assertTrue($container->hasDefinition(SacCabinsListController::class));
        $this->assertStringContainsString('E=%%s.00&N=%%s.00', $container->getParameter('markocupic_sac_cabins.geo_link'));
    }

    public function testAllowsOverridingTheGeoLink(): void
    {
        $container = new ContainerBuilder();

        (new MarkocupicSacCabinsExtension())->load([['geo_link' => 'https://example.com/?E=%%s&N=%%s']], $container);

        $this->assertSame('https://example.com/?E=%%s&N=%%s', $container->getParameter('markocupic_sac_cabins.geo_link'));
    }
}
