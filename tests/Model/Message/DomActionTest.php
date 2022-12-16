<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\phpOMS\Model\Message;

use phpOMS\Model\Message\DomAction;

/**
 * @internal
 */
final class DomActionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(9, DomAction::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(DomAction::getConstants(), \array_unique(DomAction::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, DomAction::CREATE_BEFORE);
        self::assertEquals(1, DomAction::CREATE_AFTER);
        self::assertEquals(2, DomAction::DELETE);
        self::assertEquals(3, DomAction::REPLACE);
        self::assertEquals(4, DomAction::MODIFY);
        self::assertEquals(5, DomAction::SHOW);
        self::assertEquals(6, DomAction::HIDE);
        self::assertEquals(7, DomAction::ACTIVATE);
        self::assertEquals(8, DomAction::DEACTIVATE);
    }
}
