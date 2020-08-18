<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/php-library-template
 */

namespace Iterica\NavigationBundle\Test\Unit;

use Iterica\NavigationBundle\Extension\RouteExtension;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Iterica\NavigationBundle\Extension\RouteExtension
 */
final class RouteExtensionTest extends Framework\TestCase
{
    public function testRoutingExtension(): void
    {
        self::assertEquals(true, true);
    }
}
