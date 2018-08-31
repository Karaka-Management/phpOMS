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

namespace phpOMS\tests\Account;

use phpOMS\Account\Group;
use phpOMS\Account\GroupStatus;

require_once __DIR__ . '/../Autoloader.php';

class GroupTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes()
    {
        $group = new Group();
        self::assertInstanceOf('\phpOMS\Account\Group', $group);

        /* Testing members */
        self::assertObjectHasAttribute('id', $group);
        self::assertObjectHasAttribute('name', $group);
        self::assertObjectHasAttribute('description', $group);
        self::assertObjectHasAttribute('members', $group);
        self::assertObjectHasAttribute('parents', $group);
        self::assertObjectHasAttribute('permissions', $group);
        self::assertObjectHasAttribute('status', $group);
    }

    public function testDefault()
    {
        $group = new Group();

        /* Testing default values */
        self::assertTrue(\is_int($group->getId()));
        self::assertEquals(0, $group->getId());

        self::assertTrue(\is_string($group->getName()));
        self::assertEquals('', $group->getName());

        self::assertTrue(\is_int($group->getStatus()));
        self::assertEquals(GroupStatus::INACTIVE, $group->getStatus());

        self::assertTrue(\is_string($group->getDescription()));
        self::assertEquals('', $group->getDescription());

        $array = $group->toArray();
        self::assertTrue(\is_array($array));
        self::assertGreaterThan(0, \count($array));
        self::assertEquals(\json_encode($array), $group->__toString());
        self::assertEquals($array, $group->jsonSerialize());
    }

    public function testSetGet()
    {
        $group = new Group();

        $group->setName('Duck');
        self::assertEquals('Duck', $group->getName());

        $group->setDescription('Animal');
        self::assertEquals('Animal', $group->getDescription());

        $group->setStatus(GroupStatus::ACTIVE);
        self::assertEquals(GroupStatus::ACTIVE, $group->getStatus());
    }

    /**
     * @expectedException \phpOMS\Stdlib\Base\Exception\InvalidEnumValue
     */
    public function testStatusException()
    {
        $account = new Group();
        $account->setStatus(99);
    }
}
