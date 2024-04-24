<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 2.0
 * @version    1.0.0
 * @link       https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\phpOMS\Model\Message;

use phpOMS\Model\Message\Redirect;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Model\Message\Redirect::class)]
final class RedirectTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefault() : void
    {
        $obj = new Redirect('');

        /* Testing default values */
        self::assertEmpty($obj->toArray()['uri']);
        self::assertEquals(0, $obj->toArray()['time']);
        self::assertFalse($obj->toArray()['new']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSetGet() : void
    {
        $obj = new Redirect('url', true);

        self::assertEquals(['type' => 'redirect', 'time' => 0, 'uri' => 'url', 'new' => true], $obj->toArray());
        self::assertEquals(\json_encode(['type' => 'redirect', 'time' => 0, 'uri' => 'url', 'new' => true]), $obj->serialize());
        self::assertEquals(['type' => 'redirect', 'time' => 0, 'uri' => 'url', 'new' => true], $obj->jsonSerialize());

        $obj->setDelay(6);
        $obj->setUri('test');
        self::assertEquals(['type' => 'redirect', 'time' => 6, 'uri' => 'test', 'new' => true], $obj->toArray());

        $obj2 = new Redirect();
        $obj2->unserialize($obj->serialize());
        self::assertEquals($obj, $obj2);
    }
}
