<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Router;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Router\RouteVerb;

class RouteVerbTest extends \PHPUnit\Framework\TestCase
{
    public function testEnum()
    {
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::GET'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::PUT'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::SET'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::DELETE'));
        self::assertTrue(\defined('phpOMS\Router\RouteVerb::ANY'));
    }

    public function testEnumUnique()
    {
        $values = RouteVerb::getConstants();
        self::assertEquals(\count($values), array_sum(array_count_values($values)));
    }
}
