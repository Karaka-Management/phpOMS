<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\AddressType;
use phpOMS\Stdlib\Base\Location;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Base\Location::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Base\LocationTest: Location type')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The location has the expected default values after initialization')]
    public function testDefault() : void
    {
        $expected = [
            'postal'  => '',
            'city'    => '',
            'country' => 'XX',
            'address' => '',
            'state'   => '',
            'lat'     => 0.0,
            'lon'     => 0.0,
            'id' => 0,
            'type' => 1,
        ];

        self::assertEquals('', $this->location->postal);
        self::assertEquals('', $this->location->city);
        self::assertEquals('XX', $this->location->country);
        self::assertEquals('', $this->location->address);
        self::assertEquals('', $this->location->state);
        self::assertEquals(0, $this->location->id);
        self::assertEquals(AddressType::HOME, $this->location->type);
        self::assertEquals($expected, $this->location->toArray());
        self::assertEquals($expected, $this->location->jsonSerialize());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The postal can be set and returned')]
    public function testPostalInputOutput() : void
    {
        $this->location->postal = '0123456789';
        self::assertEquals('0123456789', $this->location->postal);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The city can be set and returned')]
    public function testCityInputOutput() : void
    {
        $this->location->city = 'city';
        self::assertEquals('city', $this->location->city);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The country can be set and returned')]
    public function testCountryInputOutput() : void
    {
        $this->location->setCountry('Country');
        self::assertEquals('Country', $this->location->country);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The address can be set and returned')]
    public function testAddressInputOutput() : void
    {
        $this->location->address = 'Some address here';
        self::assertEquals('Some address here', $this->location->address);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The state can be set and returned')]
    public function testStateInputOutput() : void
    {
        $this->location->state = 'This is a state 123';
        self::assertEquals('This is a state 123', $this->location->state);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The location can be turned into an array')]
    public function testArray() : void
    {
        $expected = [
            'postal'  => '0123456789',
            'city'    => 'city',
            'country' => 'Country',
            'address' => 'Some address here',
            'state'   => 'This is a state 123',
            'lat'     => 12.1,
            'lon'     => 11.2,
            'id' => 0,
            'type' => 2,
        ];

        $this->location->postal  = '0123456789';
        $this->location->type    = AddressType::BUSINESS;
        $this->location->city    = 'city';
        $this->location->address = 'Some address here';
        $this->location->state   = 'This is a state 123';
        $this->location->lat     = 12.1;
        $this->location->lon     = 11.2;
        $this->location->setCountry('Country');

        self::assertEquals($expected, $this->location->toArray());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The location can be json serialized')]
    public function testJsonSerialize() : void
    {
        $expected = [
            'postal'  => '0123456789',
            'city'    => 'city',
            'country' => 'Country',
            'address' => 'Some address here',
            'state'   => 'This is a state 123',
            'lat'     => 12.1,
            'lon'     => 11.2,
            'id' => 0,
            'type' => 2,
        ];

        $this->location->postal  = '0123456789';
        $this->location->type    = AddressType::BUSINESS;
        $this->location->city    = 'city';
        $this->location->address = 'Some address here';
        $this->location->state   = 'This is a state 123';
        $this->location->lat     = 12.1;
        $this->location->lon     = 11.2;
        $this->location->setCountry('Country');

        self::assertEquals($expected, $this->location->jsonSerialize());
        self::assertEquals(\json_encode($this->location->jsonSerialize()), $this->location->serialize());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The location can unserialized')]
    public function testUnserialize() : void
    {
        $expected = [
            'id' => 0,
            'type' => 1,
            'postal'  => '0123456789',
            'city'    => 'city',
            'country' => 'Country',
            'address' => 'Some address here',
            'state'   => 'This is a state 123',
            'lat'     => 12.1,
            'lon'     => 11.2,
        ];

        $this->location->unserialize(\json_encode($expected));
        self::assertEquals(\json_encode($expected), $this->location->serialize());
    }
}
