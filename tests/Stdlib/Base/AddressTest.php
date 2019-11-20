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

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\Address;
use phpOMS\Stdlib\Base\Location;

/**
 * @testdox phpOMS\tests\Stdlib\Base\AddressTest: Address type
 *
 * @internal
 */
class AddressTest extends \PHPUnit\Framework\TestCase
{
    protected Address $address;

    protected function setUp() : void
    {
        $this->address = new Address();
    }

    /**
     * @testdox The address has the expected attributes
     * @covers phpOMS\Stdlib\Base\Address<extended>
     */
    public function testAttributes() : void
    {
        self::assertObjectHasAttribute('recipient', $this->address);
        self::assertObjectHasAttribute('fao', $this->address);
        self::assertObjectHasAttribute('location', $this->address);
    }

    /**
     * @testdox The address has the expected default values after initialization
     * @covers phpOMS\Stdlib\Base\Address<extended>
     */
    public function testDefault() : void
    {
        $expected = [
            'recipient' => '',
            'fao' => '',
            'location' => [
                'postal' => '',
                'city' => '',
                'country' => '',
                'address' => '',
                'state' => '',
                'geo' => [
                    'lat' => 0,
                    'long' => 0,
                ],
            ],
        ];

        self::assertEquals('', $this->address->getRecipient());
        self::assertEquals('', $this->address->getFAO());
        self::assertInstanceOf('\phpOMS\Stdlib\Base\Location', $this->address->getLocation());
        self::assertEquals($expected, $this->address->toArray());
        self::assertEquals($expected, $this->address->jsonSerialize());
    }

    /**
     * @testdox The fao can be set and returned
     * @covers phpOMS\Stdlib\Base\Address<extended>
     */
    public function testFAOInputOutput() : void
    {
        $this->address->setFAO('fao');
        self::assertEquals('fao', $this->address->getFAO());
    }

    /**
     * @testdox The recepient can be set and returned
     * @covers phpOMS\Stdlib\Base\Address<extended>
     */
    public function testRecipientInputOutput() : void
    {
        $this->address->setRecipient('recipient');
        self::assertEquals('recipient', $this->address->getRecipient());
    }

    /**
     * @testdox The location can be set and returned
     * @covers phpOMS\Stdlib\Base\Address<extended>
     */
    public function testLocationInputOutput() : void
    {
        $this->address->setLocation(new Location());

        self::assertInstanceOf('\phpOMS\Stdlib\Base\Location', $this->address->getLocation());

    }

    /**
     * @testdox The address can be turned into array data
     * @covers phpOMS\Stdlib\Base\Address<extended>
     */
    public function testArray() : void
    {
        $expected = [
            'recipient' => 'recipient',
            'fao' => 'fao',
            'location' => [
                'postal' => '',
                'city' => '',
                'country' => '',
                'address' => '',
                'state' => '',
                'geo' => [
                    'lat' => 0,
                    'long' => 0,
                ],
            ],
        ];

        $this->address->setFAO('fao');
        $this->address->setRecipient('recipient');
        $this->address->setLocation(new Location());

        self::assertEquals($expected, $this->address->toArray());
    }

    /**
     * @testdox The address can be json serialized
     * @covers phpOMS\Stdlib\Base\Address<extended>
     */
    public function testJsonSerialize() : void
    {
        $expected = [
            'recipient' => 'recipient',
            'fao' => 'fao',
            'location' => [
                'postal' => '',
                'city' => '',
                'country' => '',
                'address' => '',
                'state' => '',
                'geo' => [
                    'lat' => 0,
                    'long' => 0,
                ],
            ],
        ];

        $this->address->setFAO('fao');
        $this->address->setRecipient('recipient');
        $this->address->setLocation(new Location());

        self::assertEquals($expected, $this->address->jsonSerialize());
    }
}
