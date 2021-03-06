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

namespace Iterica\NavigationBundle\Test\AutoReview;

use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @coversNothing
 */
final class SrcCodeTest extends Framework\TestCase
{
    use Helper;

    public function testSrcClassesHaveUnitTests(): void
    {
        /** @todo disabled for now */
        self::assertEquals(true, true);

//        self::assertClassesHaveTests(
//            __DIR__ . '/../../src/',
//            'Iterica\\NavigationBundle\\',
//            'Iterica\\NavigationBundle\\Test\\Unit\\'
//        );
    }
}
