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
 * @testdox phpOMS\tests\WebRouter\RouteVerbTest: Route verb enum
 * @internal
 */
final class RouteVerbTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The route verb enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::GET'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::PUT'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::SET'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::DELETE'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::ANY'));
    }

    /**
     * @testdox The route verb enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        $values = RouteVerb::getConstants();
        self::assertEquals(\count($values), \array_sum(\array_count_values($values)));
    }
}
