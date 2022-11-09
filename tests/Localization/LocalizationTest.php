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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Localization;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO4217CharEnum;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\Localization;
use phpOMS\Localization\TimeZoneEnumArray;
use phpOMS\Utils\Converter\AngleType;
use phpOMS\Utils\Converter\TemperatureType;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Localization\LocalizationTest: Localization for information such as language, currency, location, language specific formatting etc.
 *
 * @internal
 */
final class LocalizationTest extends \PHPUnit\Framework\TestCase
{
    protected Localization $localization;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->localization = new Localization();
    }

    /**
     * @testdox The localization has the expected member variables
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testAttributes() : void
    {
        self::assertObjectHasAttribute('country', $this->localization);
        self::assertObjectHasAttribute('timezone', $this->localization);
        self::assertObjectHasAttribute('language', $this->localization);
        self::assertObjectHasAttribute('currency', $this->localization);
        self::assertObjectHasAttribute('decimal', $this->localization);
        self::assertObjectHasAttribute('thousands', $this->localization);
        self::assertObjectHasAttribute('datetime', $this->localization);
    }

    /**
     * @testdox The localization has the expected default values after initialization
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->localization->getId());
        self::assertTrue(ISO3166TwoEnum::isValidValue($this->localization->getCountry()));
        self::assertTrue(TimeZoneEnumArray::isValidValue($this->localization->getTimezone()));
        self::assertTrue(ISO639x1Enum::isValidValue($this->localization->getLanguage()));
        self::assertTrue(ISO4217CharEnum::isValidValue($this->localization->getCurrency()));
        self::assertEquals('0', $this->localization->getCurrencyFormat());
        self::assertEquals('.', $this->localization->getDecimal());
        self::assertEquals(',', $this->localization->getThousands());
        self::assertEquals([], $this->localization->getDatetime());

        self::assertEquals([], $this->localization->getPrecision());
        self::assertEquals([], $this->localization->getSpeed());
        self::assertEquals([], $this->localization->getWeight());
        self::assertEquals([], $this->localization->getLength());
        self::assertEquals([], $this->localization->getArea());
        self::assertEquals([], $this->localization->getVolume());
    }

    /**
     * @testdox Setting a invalid language code throws InvalidEnumValue
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testInvalidLanguage() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setLanguage('abc');
    }

    /**
     * @testdox Setting a invalid country code throws InvalidEnumValue
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testInvalidCountry() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setCountry('abc');
    }

    /**
     * @testdox Setting a invalid timezone code throws InvalidEnumValue
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testInvalidTimezone() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setTimezone('abc');
    }

    /**
     * @testdox Setting a invalid currency code throws InvalidEnumValue
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testInvalidCurrency() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setCurrency('abc');
    }

    /**
     * @testdox Setting a invalid angle throws InvalidEnumValue
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testInvalidAngle() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setAngle('abc');
    }

    /**
     * @testdox Setting a invalid temperature throws InvalidEnumValue
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testInvalidTemperature() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setTemperature('abc');
    }

    /**
     * @testdox The country can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testCountryInputOutput() : void
    {
        $this->localization->setCountry(ISO3166TwoEnum::_USA);
        self::assertEquals(ISO3166TwoEnum::_USA, $this->localization->getCountry());
    }

    /**
     * @testdox The timezone can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testTimezoneInputOutput() : void
    {
        $this->localization->setTimezone(TimeZoneEnumArray::get(315));
        self::assertEquals(TimeZoneEnumArray::get(315), $this->localization->getTimezone());
    }

    /**
     * @testdox The language can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testLanguageInputOutput() : void
    {
        $this->localization->setLanguage(ISO639x1Enum::_DE);
        self::assertEquals(ISO639x1Enum::_DE, $this->localization->getLanguage());
    }

    /**
     * @testdox The currency can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testCurrencyInputOutput() : void
    {
        $this->localization->setCurrency(ISO4217CharEnum::_EUR);
        self::assertEquals(ISO4217CharEnum::_EUR, $this->localization->getCurrency());
    }

    /**
     * @testdox The datetime can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testDatetimeInputOutput() : void
    {
        $this->localization->setDatetime(['Y-m-d H:i:s']);
        self::assertEquals(['Y-m-d H:i:s'], $this->localization->getDatetime());
    }

    /**
     * @testdox The decimal can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testDecimalInputOutput() : void
    {
        $this->localization->setDecimal(',');
        self::assertEquals(',', $this->localization->getDecimal());
    }

    /**
     * @testdox The thousands can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testThousandsInputOutput() : void
    {
        $this->localization->setThousands('.');
        self::assertEquals('.', $this->localization->getThousands());
    }

    /**
     * @testdox The angle can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testAngleInputOutput() : void
    {
        $this->localization->setAngle(AngleType::CENTRAD);
        self::assertEquals(AngleType::CENTRAD, $this->localization->getAngle());
    }

    /**
     * @testdox The temperature can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testTemperatureInputOutput() : void
    {
        $this->localization->setTemperature(TemperatureType::FAHRENHEIT);
        self::assertEquals(TemperatureType::FAHRENHEIT, $this->localization->getTemperature());
    }

    /**
     * @testdox The weight can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testWeightInputOutput() : void
    {
        $this->localization->setWeight([1]);
        self::assertEquals([1], $this->localization->getWeight());
    }

    /**
     * @testdox The currency format can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testCurrencyFormatInputOutput() : void
    {
        $this->localization->setCurrencyFormat('1');
        self::assertEquals('1', $this->localization->getCurrencyFormat());
    }

    /**
     * @testdox The precision can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testPrecisionInputOutput() : void
    {
        $this->localization->setPrecision([1]);
        self::assertEquals([1], $this->localization->getPrecision());
    }

    /**
     * @testdox The length can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testLengthInputOutput() : void
    {
        $this->localization->setLength([1]);
        self::assertEquals([1], $this->localization->getLength());
    }

    /**
     * @testdox The area can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testAreaInputOutput() : void
    {
        $this->localization->setArea([1]);
        self::assertEquals([1], $this->localization->getArea());
    }

    /**
     * @testdox The volume can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testVolumeInputOutput() : void
    {
        $this->localization->setVolume([1]);
        self::assertEquals([1], $this->localization->getVolume());
    }

    /**
     * @testdox The speed can be set and returned
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testSpeedInputOutput() : void
    {
        $this->localization->setSpeed([1]);
        self::assertEquals([1], $this->localization->getSpeed());
    }

    /**
     * @testdox Localization data can be loaded from a locale file
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testLocalizationFromLanguageCode() : void
    {
        $l11n = Localization::fromLanguage(ISO639x1Enum::_DE);
        self::assertEquals(ISO4217CharEnum::_EUR, $l11n->getCurrency());
    }

    /**
     * @testdox Localization data can be loaded from a locale file
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testLocalizationLoading() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_DE);
        self::assertEquals(ISO4217CharEnum::_EUR, $this->localization->getCurrency());
    }

    /**
     * @testdox Localization data can be serialized and unserialized
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testLocalizationSerialize() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_DE);
        $l11n1 = $this->localization->jsonSerialize();

        $l11nObj = Localization::fromJson($l11n1);
        $l11n2   = $l11nObj->jsonSerialize();

        self::assertEquals($l11n1, $l11n2);
    }

    /**
     * @testdox If no locale file for a specified country exists or a wild card country is used the first match of a locale file based on the defined language is loaded
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testInvalidCountryLocalizationLoading() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_DE, 'ABC');
        self::assertEquals(ISO4217CharEnum::_EUR, $this->localization->getCurrency());
    }

    /**
     * @testdox By default the english locale file will be loaded if no other locale file can be found
     * @covers phpOMS\Localization\Localization
     * @group framework
     */
    public function testMissingLocalizationLoading() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_AA);
        self::assertEquals(ISO4217CharEnum::_USD, $this->localization->getCurrency());
    }
}
