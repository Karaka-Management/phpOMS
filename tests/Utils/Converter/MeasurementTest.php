<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\AngleType;
use phpOMS\Utils\Converter\AreaType;
use phpOMS\Utils\Converter\EnergyPowerType;
use phpOMS\Utils\Converter\FileSizeType;
use phpOMS\Utils\Converter\LengthType;
use phpOMS\Utils\Converter\Measurement;
use phpOMS\Utils\Converter\PressureType;
use phpOMS\Utils\Converter\SpeedType;
use phpOMS\Utils\Converter\TemperatureType;
use phpOMS\Utils\Converter\TimeType;
use phpOMS\Utils\Converter\VolumeType;
use phpOMS\Utils\Converter\WeightType;

/**
 * @internal
 */
class MeasurementTest extends \PHPUnit\Framework\TestCase
{
    public function testTemperature() : void
    {
        $temps = TemperatureType::getConstants();

        foreach ($temps as $from) {
            foreach ($temps as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertTemperature(Measurement::convertTemperature($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testWeight() : void
    {
        $weights = WeightType::getConstants();

        foreach ($weights as $from) {
            foreach ($weights as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertWeight(Measurement::convertWeight($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testLength() : void
    {
        $lengths = LengthType::getConstants();

        foreach ($lengths as $from) {
            foreach ($lengths as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertLength(Measurement::convertLength($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testArea() : void
    {
        $areas = AreaType::getConstants();

        foreach ($areas as $from) {
            foreach ($areas as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertArea(Measurement::convertArea($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testVolume() : void
    {
        $volumes = VolumeType::getConstants();

        foreach ($volumes as $from) {
            foreach ($volumes as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertVolume(Measurement::convertVolume($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testSpeed() : void
    {
        $speeds = SpeedType::getConstants();

        foreach ($speeds as $from) {
            foreach ($speeds as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertSpeed(Measurement::convertSpeed($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testTime() : void
    {
        $times = TimeType::getConstants();

        foreach ($times as $from) {
            foreach ($times as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertTime(Measurement::convertTime($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testAngle() : void
    {
        $angles = AngleType::getConstants();

        foreach ($angles as $from) {
            foreach ($angles as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertAngle(Measurement::convertAngle($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testPressure() : void
    {
        $pressures = PressureType::getConstants();

        foreach ($pressures as $from) {
            foreach ($pressures as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertPressure(Measurement::convertPressure($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testEnergy() : void
    {
        $energies = EnergyPowerType::getConstants();

        foreach ($energies as $from) {
            foreach ($energies as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertEnergy(Measurement::convertEnergy($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testFileSize() : void
    {
        $fileSizes = FileSizeType::getConstants();

        foreach ($fileSizes as $from) {
            foreach ($fileSizes as $to) {
                $rand = \mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertFileSize(Measurement::convertFileSize($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testInvalidTemperatureFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertTemperature(1.1, 'invalid', TemperatureType::CELSIUS);
    }

    public function testInvalidTemperatureTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertTemperature(1.1, TemperatureType::CELSIUS, 'invalid');
    }

    public function testInvalidWeightFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertWeight(1.1, 'invalid', WeightType::KILOGRAM);
    }

    public function testInvalidWeightTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertWeight(1.1, WeightType::KILOGRAM, 'invalid');
    }

    public function testInvalidLengthFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertLength(1.1, 'invalid', LengthType::METERS);
    }

    public function testInvalidLengthTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertLength(1.1, LengthType::METERS, 'invalid');
    }

    public function testInvalidAreaFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertArea(1.1, 'invalid', AreaType::SQUARE_METERS);
    }

    public function testInvalidAreaTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertArea(1.1, AreaType::SQUARE_METERS, 'invalid');
    }

    public function testInvalidVolumeFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertVolume(1.1, 'invalid', VolumeType::LITER);
    }

    public function testInvalidVolumeTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertVolume(1.1, VolumeType::LITER, 'invalid');
    }

    public function testInvalidSpeedFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertSpeed(1.1, 'invalid', SpeedType::KILOMETERS_PER_HOUR);
    }

    public function testInvalidSpeedTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertSpeed(1.1, SpeedType::KILOMETERS_PER_HOUR, 'invalid');
    }

    public function testInvalidTimeFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertTime(1.1, 'invalid', TimeType::HOURS);
    }

    public function testInvalidTimeTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertTime(1.1, TimeType::HOURS, 'invalid');
    }

    public function testInvalidAngleFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertAngle(1.1, 'invalid', AngleType::RADIAN);
    }

    public function testInvalidAngleTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertAngle(1.1, AngleType::RADIAN, 'invalid');
    }

    public function testInvalidPressureFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertPressure(1.1, 'invalid', PressureType::BAR);
    }

    public function testInvalidPressureTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertPressure(1.1, PressureType::BAR, 'invalid');
    }

    public function testInvalidEnergyPowerFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertEnergy(1.1, 'invalid', EnergyPowerType::JOULS);
    }

    public function testInvalidEnergyPowerTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertEnergy(1.1, EnergyPowerType::JOULS, 'invalid');
    }

    public function testInvalidFileSizeFrom() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertFileSize(1.1, 'invalid', FileSizeType::KILOBYTE);
    }

    public function testInvalidFileSizeTo() : void
    {
        self::expectException(\InvalidArgumentException::class);

        Measurement::convertFileSize(1.1, FileSizeType::KILOBYTE, 'invalid');
    }
}
