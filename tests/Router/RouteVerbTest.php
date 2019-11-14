<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\WebRouter;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Router\RouteVerb;

/**
 * @internal
 */
class RouteVerbTest extends \PHPUnit\Framework\TestCase
{
    /**
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

    public function testEnumUnique() : void
    {
        $values = RouteVerb::getConstants();
        self::assertEquals(\count($values), \array_sum(\array_count_values($values)));
    }
}
