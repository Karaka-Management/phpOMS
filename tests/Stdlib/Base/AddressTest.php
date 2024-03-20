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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Base\Address::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Base\AddressTest: Address type')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The address has the expected default values after initialization')]
    public function testDefault() : void
    {
        $expected = [
            'fao'       => '',
            'name'       => '',
            'postal'  => '',
            'city'    => '',
            'country' => 'XX',
            'address' => '',
            'state'   => '',
            'lat'     => 0.0,
            'lon'     => 0.0,
        ];

        self::assertEquals('', $this->address->fao);
        self::assertInstanceOf('\phpOMS\Stdlib\Base\Location', $this->address);
        self::assertEquals($expected, $this->address->toArray());
        self::assertEquals($expected, $this->address->jsonSerialize());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The fao can be set and returned')]
    public function testFAOInputOutput() : void
    {
        $this->address->fao = 'fao';
        self::assertEquals('fao', $this->address->fao);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The address can be turned into array data')]
    public function testArray() : void
    {
        $expected = [
            'fao'       => 'fao',
            'name'  => '',
            'postal'  => '',
            'city'    => '',
            'country' => 'XX',
            'address' => '',
            'state'   => '',
            'lat'     => 0.0,
            'lon'     => 0.0,
        ];

        $this->address->fao       = 'fao';

        self::assertEquals($expected, $this->address->toArray());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The address can be json serialized')]
    public function testJsonSerialize() : void
    {
        $expected = [
            'fao'       => 'fao',
            'name'  => '',
            'postal'  => '',
            'city'    => '',
            'country' => 'XX',
            'address' => '',
            'state'   => '',
            'lat'     => 0.0,
            'lon'     => 0.0,
        ];

        $this->address->fao       = 'fao';

        self::assertEquals($expected, $this->address->jsonSerialize());
    }
}
