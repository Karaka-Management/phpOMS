<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
final class AddressTest extends \PHPUnit\Framework\TestCase
{
    protected Address $address;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->address = new Address();
    }

    /**
     * @testdox The address has the expected default values after initialization
     * @covers phpOMS\Stdlib\Base\Address
     * @group framework
     */
    public function testDefault() : void
    {
        $expected = [
            'recipient' => '',
            'fao'       => '',
            'location'  => [
                'postal'  => '',
                'city'    => '',
                'country' => 'XX',
                'address' => '',
                'state'   => '',
                'geo'     => [
                    'lat'  => 0,
                    'long' => 0,
                ],
            ],
        ];

        self::assertEquals('', $this->address->recipient);
        self::assertEquals('', $this->address->fao);
        self::assertInstanceOf('\phpOMS\Stdlib\Base\Location', $this->address->location);
        self::assertEquals($expected, $this->address->toArray());
        self::assertEquals($expected, $this->address->jsonSerialize());
    }

    /**
     * @testdox The fao can be set and returned
     * @covers phpOMS\Stdlib\Base\Address
     * @group framework
     */
    public function testFAOInputOutput() : void
    {
        $this->address->fao = 'fao';
        self::assertEquals('fao', $this->address->fao);
    }

    /**
     * @testdox The recipient can be set and returned
     * @covers phpOMS\Stdlib\Base\Address
     * @group framework
     */
    public function testRecipientInputOutput() : void
    {
        $this->address->recipient = 'recipient';
        self::assertEquals('recipient', $this->address->recipient);
    }

    /**
     * @testdox The location can be set and returned
     * @covers phpOMS\Stdlib\Base\Address
     * @group framework
     */
    public function testLocationInputOutput() : void
    {
        $this->address->location = new Location();

        self::assertInstanceOf('\phpOMS\Stdlib\Base\Location', $this->address->location);
    }

    /**
     * @testdox The address can be turned into array data
     * @covers phpOMS\Stdlib\Base\Address
     * @group framework
     */
    public function testArray() : void
    {
        $expected = [
            'recipient' => 'recipient',
            'fao'       => 'fao',
            'location'  => [
                'postal'  => '',
                'city'    => '',
                'country' => 'XX',
                'address' => '',
                'state'   => '',
                'geo'     => [
                    'lat'  => 0,
                    'long' => 0,
                ],
            ],
        ];

        $this->address->fao       = 'fao';
        $this->address->recipient = 'recipient';
        $this->address->location  = new Location();

        self::assertEquals($expected, $this->address->toArray());
    }

    /**
     * @testdox The address can be json serialized
     * @covers phpOMS\Stdlib\Base\Address
     * @group framework
     */
    public function testJsonSerialize() : void
    {
        $expected = [
            'recipient' => 'recipient',
            'fao'       => 'fao',
            'location'  => [
                'postal'  => '',
                'city'    => '',
                'country' => 'XX',
                'address' => '',
                'state'   => '',
                'geo'     => [
                    'lat'  => 0,
                    'long' => 0,
                ],
            ],
        ];

        $this->address->fao       = 'fao';
        $this->address->recipient = 'recipient';
        $this->address->location  = new Location();

        self::assertEquals($expected, $this->address->jsonSerialize());
    }
}
