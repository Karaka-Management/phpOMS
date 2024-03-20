<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 2.0
 * @version    1.0.0
 * @link       https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\phpOMS\Model\Message;

use phpOMS\Model\Message\Reload;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Model\Message\Reload::class)]
final class ReloadTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefault() : void
    {
        $obj = new Reload();

        /* Testing default values */
        self::assertEquals(0, $obj->toArray()['time']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSetGet() : void
    {
        $obj = new Reload(5);

        self::assertEquals(['type' => 'reload', 'time' => 5], $obj->toArray());
        self::assertEquals(\json_encode(['type' => 'reload', 'time' => 5]), $obj->serialize());
        self::assertEquals(['type' => 'reload', 'time' => 5], $obj->jsonSerialize());

        $obj->setDelay(6);
        self::assertEquals(['type' => 'reload', 'time' => 6], $obj->toArray());

        $obj2 = new Reload();
        $obj2->unserialize($obj->serialize());
        self::assertEquals($obj, $obj2);
    }
}
