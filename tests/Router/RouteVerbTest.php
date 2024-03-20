<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\WebRouter;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Router\RouteVerb;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\WebRouter\RouteVerbTest: Route verb enum')]
final class RouteVerbTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The route verb enum has the correct values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::GET'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::PUT'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::SET'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::DELETE'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::ANY'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The route verb enum has only unique values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        $values = RouteVerb::getConstants();
        self::assertEquals(\count($values), \array_sum(\array_count_values($values)));
    }
}
