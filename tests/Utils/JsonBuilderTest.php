<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils;

use phpOMS\Utils\JsonBuilder;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class JsonBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $builder = new JsonBuilder();
        self::assertEquals([], $builder->getJson());
        $builder->remove('test/path');
        self::assertEquals([], $builder->getJson());
    }

    public function testBuilder() : void
    {
        // main test is/should be done on ArrayUtils::setArray etc.
        $builder = new JsonBuilder();
        $builder->add('a/test/path', 3);
        self::assertEquals(['a' => ['test' => ['path' => 3]]], $builder->getJson());
        $builder->remove('a/test/path');
        self::assertEquals(['a' => ['test' => []]], $builder->getJson());

        $arr = $builder->getJson();

        self::assertEquals($arr, $builder->jsonSerialize());
        self::assertEquals(\json_encode($arr), $builder->serialize());

        $builder->unserialize($builder->serialize());
        self::assertEquals($arr, $builder->getJson());
    }
}
