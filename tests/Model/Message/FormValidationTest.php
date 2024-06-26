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

use phpOMS\Model\Message\FormValidation;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Model\Message\FormValidation::class)]
final class FormValidationTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefault() : void
    {
        $obj = new FormValidation([]);

        /* Testing default values */
        self::assertEmpty($obj->toArray()['validation']);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSetGet() : void
    {
        $arr = ['a' => true, 'b' => false];
        $obj = new FormValidation($arr);

        self::assertEquals(['type' => 'validation', 'validation' => $arr], $obj->toArray());
        self::assertEquals(\json_encode(['type' => 'validation', 'validation' => $arr]), $obj->serialize());
        self::assertEquals(['type' => 'validation', 'validation' => $arr], $obj->jsonSerialize());

        $obj2 = new FormValidation();
        $obj2->unserialize($obj->serialize());
        self::assertEquals($obj, $obj2);
    }
}
