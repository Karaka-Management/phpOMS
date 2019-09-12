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
 * @internal
 */
class LocationTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $location = new Location();
        self::assertObjectHasAttribute('postal', $location);
        self::assertObjectHasAttribute('city', $location);
        self::assertObjectHasAttribute('country', $location);
        self::assertObjectHasAttribute('address', $location);
        self::assertObjectHasAttribute('state', $location);
        self::assertObjectHasAttribute('geo', $location);
    }

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

        $location = new Location();
        self::assertEquals('', $location->getPostal());
        self::assertEquals('', $location->getCity());
        self::assertEquals('', $location->getCountry());
        self::assertEquals('', $location->getAddress());
        self::assertEquals('', $location->getState());
        self::assertEquals(0, $location->getId());
        self::assertEquals(AddressType::HOME, $location->getType());
        self::assertEquals(['lat' => 0, 'long' => 0], $location->getGeo());
        self::assertEquals($expected, $location->toArray());
        self::assertEquals($expected, $location->jsonSerialize());
    }

    public function testGetSet() : void
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

        $location = new Location();

        $location->setPostal('0123456789');
        $location->setType(AddressType::BUSINESS);
        $location->setCity('city');
        $location->setCountry('Country');
        $location->setAddress('Some address here');
        $location->setState('This is a state 123');
        $location->setGeo(['lat' => 12.1, 'long' => 11.2,]);

        self::assertEquals(AddressType::BUSINESS, $location->getType());
        self::assertEquals('0123456789', $location->getPostal());
        self::assertEquals('city', $location->getCity());
        self::assertEquals('Country', $location->getCountry());
        self::assertEquals('Some address here', $location->getAddress());
        self::assertEquals('This is a state 123', $location->getState());
        self::assertEquals(['lat' => 12.1, 'long' => 11.2], $location->getGeo());
        self::assertEquals($expected, $location->toArray());
        self::assertEquals($expected, $location->jsonSerialize());
    }
}
