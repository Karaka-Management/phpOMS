<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Query\Where;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Query\Where::class)]
final class WhereTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Builder', new Where($GLOBALS['dbpool']->get()));
    }
}
