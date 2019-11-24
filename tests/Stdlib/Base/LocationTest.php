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

use phpOMS\Stdlib\Base\AddressType;
use phpOMS\Stdlib\Base\Location;

/**
 * @testdox phpOMS\tests\Stdlib\Base\LocationTest: Location type
 *
 * @internal
 */
class LocationTest extends \PHPUnit\Framework\TestCase
{
    protected Location $location;

    protected function setUp() : void
    {
        $this->location = new Location();
    }

    /**
     * @testdox The location has the expected attributes
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testAttributes() : void
    {
        self::assertObjectHasAttribute('postal', $this->location);
        self::assertObjectHasAttribute('city', $this->location);
        self::assertObjectHasAttribute('country', $this->location);
        self::assertObjectHasAttribute('address', $this->location);
        self::assertObjectHasAttribute('state', $this->location);
        self::assertObjectHasAttribute('geo', $this->location);
    }

    /**
     * @testdox The location has the expected default values after initialization
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testDefault() : void
    {
        $expected = [
            'postal'  => '',
            'city'    => '',
            'country' => '',
            'address' => '',
            'state'   => '',
            'geo'     => [
                'lat'  => 0,
                'long' => 0,
            ],
        ];

        self::assertEquals('', $this->location->getPostal());
        self::assertEquals('', $this->location->getCity());
        self::assertEquals('', $this->location->getCountry());
        self::assertEquals('', $this->location->getAddress());
        self::assertEquals('', $this->location->getState());
        self::assertEquals(0, $this->location->getId());
        self::assertEquals(AddressType::HOME, $this->location->getType());
        self::assertEquals(['lat' => 0, 'long' => 0], $this->location->getGeo());
        self::assertEquals($expected, $this->location->toArray());
        self::assertEquals($expected, $this->location->jsonSerialize());
    }

    /**
     * @testdox The postal can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testPostalInputOutput() : void
    {
        $this->location->setPostal('0123456789');
        self::assertEquals('0123456789', $this->location->getPostal());
    }

    /**
     * @testdox The type can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testTypeInputOutput() : void
    {
        $this->location->setType(AddressType::BUSINESS);
        self::assertEquals(AddressType::BUSINESS, $this->location->getType());
    }

    /**
     * @testdox The city can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testCityInputOutput() : void
    {
        $this->location->setCity('city');
        self::assertEquals('city', $this->location->getCity());
    }

    /**
     * @testdox The country can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testCountryInputOutput() : void
    {
        $this->location->setCountry('Country');
        self::assertEquals('Country', $this->location->getCountry());
    }

    /**
     * @testdox The address can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testAddressInputOutput() : void
    {
        $this->location->setAddress('Some address here');
        self::assertEquals('Some address here', $this->location->getAddress());
    }

    /**
     * @testdox The state can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testStateInputOutput() : void
    {
        $this->location->setState('This is a state 123');
        self::assertEquals('This is a state 123', $this->location->getState());
    }

    /**
     * @testdox The geo location can be set and returned
     * @covers phpOMS\Stdlib\Base\Location
     */
    public function testGeoInputOutput() : void
    {
        $this->location->setGeo(['lat' => 12.1, 'long' => 11.2,]);
        self::assertEquals(['lat' => 12.1, 'long' => 11.2], $this->location->getGeo());
    }

    /**
     * @testdox The location can be turned into an array
     * @covers phpOMS\Stdlib\Base\Location
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

        $this->location->setPostal('0123456789');
        $this->location->setType(AddressType::BUSINESS);
        $this->location->setCity('city');
        $this->location->setCountry('Country');
        $this->location->setAddress('Some address here');
        $this->location->setState('This is a state 123');
        $this->location->setGeo(['lat' => 12.1, 'long' => 11.2,]);

        self::assertEquals($expected, $this->location->toArray());
    }

    /**
     * @testdox The location can be json serialized
     * @covers phpOMS\Stdlib\Base\Location
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

        $this->location->setPostal('0123456789');
        $this->location->setType(AddressType::BUSINESS);
        $this->location->setCity('city');
        $this->location->setCountry('Country');
        $this->location->setAddress('Some address here');
        $this->location->setState('This is a state 123');
        $this->location->setGeo(['lat' => 12.1, 'long' => 11.2,]);

        self::assertEquals($expected, $this->location->jsonSerialize());
    }
}
