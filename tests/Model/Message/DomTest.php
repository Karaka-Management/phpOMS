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

use phpOMS\Model\Message\Dom;
use phpOMS\Model\Message\DomAction;

/**
 * @internal
 */
final class DomTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \phpOMS\Model\Message\Dom
     * @group framework
     */
    public function testDefault() : void
    {
        $obj = new Dom();

        /* Testing default values */
        self::assertEquals(0, $obj->toArray()['time']);
        self::assertEquals('', $obj->toArray()['selector']);
        self::assertEquals('', $obj->toArray()['content']);
        self::assertEquals(DomAction::MODIFY, $obj->toArray()['action']);
    }

    /**
     * @covers \phpOMS\Model\Message\Dom
     * @group framework
     */
    public function testSetGet() : void
    {
        $obj = new Dom();
        $obj->setDelay(3);
        $obj->setAction(DomAction::SHOW);
        $obj->setContent('msg');
        $obj->setSelector('#sel');

        self::assertEquals([
            'type'     => 'dom',
            'time'     => 3,
            'selector' => '#sel',
            'action'   => DomAction::SHOW,
            'content'  => 'msg',
        ], $obj->toArray());

        self::assertEquals(\json_encode([
            'type'     => 'dom',
            'time'     => 3,
            'selector' => '#sel',
            'action'   => DomAction::SHOW,
            'content'  => 'msg',
        ]), $obj->serialize());

        self::assertEquals([
            'type'     => 'dom',
            'time'     => 3,
            'selector' => '#sel',
            'action'   => DomAction::SHOW,
            'content'  => 'msg',
        ], $obj->jsonSerialize());

        $obj2 = new Dom();
        $obj2->unserialize($obj->serialize());
        self::assertEquals($obj, $obj2);
    }
}
