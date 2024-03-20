<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Localization::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\LocalizationTest: Localization for information such as language, currency, location, language specific formatting etc.')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The localization has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->localization->id);
        self::assertTrue(ISO3166TwoEnum::isValidValue($this->localization->country));
        self::assertTrue(TimeZoneEnumArray::isValidValue($this->localization->getTimezone()));
        self::assertTrue(ISO639x1Enum::isValidValue($this->localization->language));
        self::assertTrue(ISO4217CharEnum::isValidValue($this->localization->currency));
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Setting a invalid country code throws InvalidEnumValue')]
    public function testInvalidCountry() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setCountry('abc');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Setting a invalid timezone code throws InvalidEnumValue')]
    public function testInvalidTimezone() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setTimezone('abc');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Setting a invalid angle throws InvalidEnumValue')]
    public function testInvalidAngle() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setAngle('abc');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Setting a invalid temperature throws InvalidEnumValue')]
    public function testInvalidTemperature() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->localization->setTemperature('abc');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The country can be set and returned')]
    public function testCountryInputOutput() : void
    {
        $this->localization->setCountry(ISO3166TwoEnum::_USA);
        self::assertEquals(ISO3166TwoEnum::_USA, $this->localization->country);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The timezone can be set and returned')]
    public function testTimezoneInputOutput() : void
    {
        $this->localization->setTimezone(TimeZoneEnumArray::get(315));
        self::assertEquals(TimeZoneEnumArray::get(315), $this->localization->getTimezone());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The datetime can be set and returned')]
    public function testDatetimeInputOutput() : void
    {
        $this->localization->setDatetime(['Y-m-d H:i:s']);
        self::assertEquals(['Y-m-d H:i:s'], $this->localization->getDatetime());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The decimal can be set and returned')]
    public function testDecimalInputOutput() : void
    {
        $this->localization->setDecimal(',');
        self::assertEquals(',', $this->localization->getDecimal());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The thousands can be set and returned')]
    public function testThousandsInputOutput() : void
    {
        $this->localization->setThousands('.');
        self::assertEquals('.', $this->localization->getThousands());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The angle can be set and returned')]
    public function testAngleInputOutput() : void
    {
        $this->localization->setAngle(AngleType::CENTRAD);
        self::assertEquals(AngleType::CENTRAD, $this->localization->getAngle());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The temperature can be set and returned')]
    public function testTemperatureInputOutput() : void
    {
        $this->localization->setTemperature(TemperatureType::FAHRENHEIT);
        self::assertEquals(TemperatureType::FAHRENHEIT, $this->localization->getTemperature());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The weight can be set and returned')]
    public function testWeightInputOutput() : void
    {
        $this->localization->setWeight([1]);
        self::assertEquals([1], $this->localization->getWeight());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The currency format can be set and returned')]
    public function testCurrencyFormatInputOutput() : void
    {
        $this->localization->setCurrencyFormat('1');
        self::assertEquals('1', $this->localization->getCurrencyFormat());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The precision can be set and returned')]
    public function testPrecisionInputOutput() : void
    {
        $this->localization->setPrecision([1]);
        self::assertEquals([1], $this->localization->getPrecision());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The length can be set and returned')]
    public function testLengthInputOutput() : void
    {
        $this->localization->setLength([1]);
        self::assertEquals([1], $this->localization->getLength());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The area can be set and returned')]
    public function testAreaInputOutput() : void
    {
        $this->localization->setArea([1]);
        self::assertEquals([1], $this->localization->getArea());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The volume can be set and returned')]
    public function testVolumeInputOutput() : void
    {
        $this->localization->setVolume([1]);
        self::assertEquals([1], $this->localization->getVolume());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The speed can be set and returned')]
    public function testSpeedInputOutput() : void
    {
        $this->localization->setSpeed([1]);
        self::assertEquals([1], $this->localization->getSpeed());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Localization data can be loaded from a locale file')]
    public function testLocalizationFromLanguageCode() : void
    {
        $l11n = Localization::fromLanguage(ISO639x1Enum::_DE);
        self::assertEquals(ISO4217CharEnum::_EUR, $l11n->currency);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Localization data can be loaded from a locale file')]
    public function testLocalizationLoading() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_DE);
        self::assertEquals(ISO4217CharEnum::_EUR, $this->localization->currency);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Localization data can be serialized and unserialized')]
    public function testLocalizationSerialize() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_DE);
        $l11n1 = $this->localization->jsonSerialize();

        $l11nObj = Localization::fromJson($l11n1);
        $l11n2   = $l11nObj->jsonSerialize();

        self::assertEquals($l11n1, $l11n2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If no locale file for a specified country exists or a wild card country is used the first match of a locale file based on the defined language is loaded')]
    public function testInvalidCountryLocalizationLoading() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_DE, 'ABC');
        self::assertEquals(ISO4217CharEnum::_EUR, $this->localization->currency);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('By default the english locale file will be loaded if no other locale file can be found')]
    public function testMissingLocalizationLoading() : void
    {
        $this->localization->loadFromLanguage(ISO639x1Enum::_AA);
        self::assertEquals(ISO4217CharEnum::_USD, $this->localization->currency);
    }
}
