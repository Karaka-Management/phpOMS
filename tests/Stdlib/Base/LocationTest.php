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

use phpOMS\Stdlib\Base\AddressType;
use phpOMS\Stdlib\Base\Location;

/**
 * @testdox phpOMS\tests\Stdlib\Base\LocationTest: Location type
 *
 * @internal
 */
final class LocationTest extends \PHPUnit\Framework\TestCase
{
    protected Location $location;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->location = new Location();
    }

    /**
     * @testdox The location has the expected default values after initialization
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testDefault() : void
    {
        $expected = [
            'postal'  => '',
            'city'    => '',
            'country' => 'XX',
            'address' => '',
            'state'   => '',
            'geo'     => [
                'lat'  => 0,
                'long' => 0,
            ],
        ];

        self::assertEquals('', $this->location->postal);
        self::assertEquals('', $this->location->city);
        self::assertEquals('XX', $this->location->getCountry());
        self::assertEquals('', $this->location->address);
        self::assertEquals('', $this->location->state);
        self::assertEquals(0, $this->location->getId());
        self::assertEquals(AddressType::HOME, $this->location->getType());
        self::assertEquals(['lat' => 0, 'long' => 0], $this->location->getGeo());
        self::assertEquals($expected, $this->location->toArray());
        self::assertEquals($expected, $this->location->jsonSerialize());
    }

    /**
     * @testdox The postal can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testPostalInputOutput() : void
    {
        $this->location->postal = '0123456789';
        self::assertEquals('0123456789', $this->location->postal);
    }

    /**
     * @testdox The type can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testTypeInputOutput() : void
    {
        $this->location->setType(AddressType::BUSINESS);
        self::assertEquals(AddressType::BUSINESS, $this->location->getType());
    }

    /**
     * @testdox The city can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testCityInputOutput() : void
    {
        $this->location->city = 'city';
        self::assertEquals('city', $this->location->city);
    }

    /**
     * @testdox The country can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testCountryInputOutput() : void
    {
        $this->location->setCountry('Country');
        self::assertEquals('Country', $this->location->getCountry());
    }

    /**
     * @testdox The address can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testAddressInputOutput() : void
    {
        $this->location->address = 'Some address here';
        self::assertEquals('Some address here', $this->location->address);
    }

    /**
     * @testdox The state can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testStateInputOutput() : void
    {
        $this->location->state = 'This is a state 123';
        self::assertEquals('This is a state 123', $this->location->state);
    }

    /**
     * @testdox The geo location can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testGeoInputOutput() : void
    {
        $this->location->setGeo(['lat' => 12.1, 'long' => 11.2,]);
        self::assertEquals(['lat' => 12.1, 'long' => 11.2], $this->location->getGeo());
    }

    /**
     * @testdox The location can be turned into an array
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testArray() : void
    {
        $expected = [
            'postal'  => '0123456789',
            'city'    => 'city',
            'country' => 'Country',
            'address' => 'Some address here',
            'state'   => 'This is a state 123',
            'geo'     => [
                'lat'  => 12.1,
                'long' => 11.2,
            ],
        ];

        $this->location->postal = '0123456789';
        $this->location->setType(AddressType::BUSINESS);
        $this->location->city    = 'city';
        $this->location->address = 'Some address here';
        $this->location->state   = 'This is a state 123';
        $this->location->setCountry('Country');
        $this->location->setGeo(['lat' => 12.1, 'long' => 11.2,]);

        self::assertEquals($expected, $this->location->toArray());
    }

    /**
     * @testdox The location can be json serialized
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testJsonSerialize() : void
    {
        $expected = [
            'postal'  => '0123456789',
            'city'    => 'city',
            'country' => 'Country',
            'address' => 'Some address here',
            'state'   => 'This is a state 123',
            'geo'     => [
                'lat'  => 12.1,
                'long' => 11.2,
            ],
        ];

        $this->location->postal = '0123456789';
        $this->location->setType(AddressType::BUSINESS);
        $this->location->city    = 'city';
        $this->location->address = 'Some address here';
        $this->location->state   = 'This is a state 123';
        $this->location->setCountry('Country');
        $this->location->setGeo(['lat' => 12.1, 'long' => 11.2,]);

        self::assertEquals($expected, $this->location->jsonSerialize());
        self::assertEquals(\json_encode($this->location->jsonSerialize()), $this->location->serialize());
    }

    /**
     * @testdox The location can unserialized
     * @covers phpOMS\Stdlib\Base\Location
     * @group framework
     */
    public function testUnserialize() : void
    {
        $expected = [
            'postal'  => '0123456789',
            'city'    => 'city',
            'country' => 'Country',
            'address' => 'Some address here',
            'state'   => 'This is a state 123',
            'geo'     => [
                'lat'  => 12.1,
                'long' => 11.2,
            ],
        ];

        $this->location->unserialize(\json_encode($expected));
        self::assertEquals(\json_encode($expected), $this->location->serialize());
    }
}
