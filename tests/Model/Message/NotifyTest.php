<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 2.2
 * @version    1.0.0
 * @link       https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\phpOMS\Model\Message;

use phpOMS\Message\NotificationLevel;
use phpOMS\Model\Message\Notify;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Model\Message\Notify::class)]
final class NotifyTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefault() : void
    {
        $obj = new Notify();

        /* Testing default values */
        self::assertEquals(0, $obj->toArray()['time']);
        self::assertEquals('', $obj->toArray()['title']);
        self::assertEquals('', $obj->toArray()['msg']);
        self::assertEquals(0, $obj->toArray()['stay']);
        self::assertEquals(NotificationLevel::INFO, $obj->toArray()['level']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSetGet() : void
    {
        $obj          = new Notify('message', NotificationLevel::WARNING);
        $obj->delay   = 3;
        $obj->stay    = 5;
        $obj->level   = NotificationLevel::ERROR;
        $obj->message = 'msg';
        $obj->title   = 'title';

        self::assertEquals([
            'type'  => 'notify',
            'time'  => 3,
            'stay'  => 5,
            'msg'   => 'msg',
            'title' => 'title',
            'level' => NotificationLevel::ERROR,
        ], $obj->toArray());

        self::assertEquals(\json_encode([
            'type'  => 'notify',
            'time'  => 3,
            'stay'  => 5,
            'msg'   => 'msg',
            'title' => 'title',
            'level' => NotificationLevel::ERROR,
        ]), $obj->serialize());

        self::assertEquals([
            'type'  => 'notify',
            'time'  => 3,
            'stay'  => 5,
            'msg'   => 'msg',
            'title' => 'title',
            'level' => NotificationLevel::ERROR,
        ], $obj->jsonSerialize());

        $obj2 = new Notify();
        $obj2->unserialize($obj->serialize());
        self::assertEquals($obj, $obj2);
    }
}
