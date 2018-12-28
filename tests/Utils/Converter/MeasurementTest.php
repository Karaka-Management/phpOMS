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
 * @link       http://website.orange-management.de
 */

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

class MeasurementTest extends \PHPUnit\Framework\TestCase
{
    public function testTemperature() : void
    {
        $temps = TemperatureType::getConstants();

        foreach ($temps as $from) {
            foreach ($temps as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertTemperature(Measurement::convertTemperature($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testWeight() : void
    {
        $weights = WeightType::getConstants();

        foreach ($weights as $from) {
            foreach ($weights as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertWeight(Measurement::convertWeight($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testLength() : void
    {
        $lengths = LengthType::getConstants();

        foreach ($lengths as $from) {
            foreach ($lengths as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertLength(Measurement::convertLength($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testArea() : void
    {
        $areas = AreaType::getConstants();

        foreach ($areas as $from) {
            foreach ($areas as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertArea(Measurement::convertArea($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testVolume() : void
    {
        $volumes = VolumeType::getConstants();

        foreach ($volumes as $from) {
            foreach ($volumes as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertVolume(Measurement::convertVolume($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testSpeed() : void
    {
        $speeds = SpeedType::getConstants();

        foreach ($speeds as $from) {
            foreach ($speeds as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertSpeed(Measurement::convertSpeed($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testTime() : void
    {
        $times = TimeType::getConstants();

        foreach ($times as $from) {
            foreach ($times as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertTime(Measurement::convertTime($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testAngle() : void
    {
        $angles = AngleType::getConstants();

        foreach ($angles as $from) {
            foreach ($angles as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertAngle(Measurement::convertAngle($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testPressure() : void
    {
        $pressures = PressureType::getConstants();

        foreach ($pressures as $from) {
            foreach ($pressures as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertPressure(Measurement::convertPressure($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testEnergy() : void
    {
        $energies = EnergyPowerType::getConstants();

        foreach ($energies as $from) {
            foreach ($energies as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertEnergy(Measurement::convertEnergy($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    public function testFileSize() : void
    {
        $fileSizes = FileSizeType::getConstants();

        foreach ($fileSizes as $from) {
            foreach ($fileSizes as $to) {
                $rand = mt_rand(0, 100);
                self::assertTrue(($rand - Measurement::convertFileSize(Measurement::convertFileSize($rand, $from, $to), $to, $from)) < 1);
            }
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTemperatureFrom() : void
    {
        Measurement::convertTemperature(1.1, 'invalid', TemperatureType::CELSIUS);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTemperatureTo() : void
    {
        Measurement::convertTemperature(1.1, TemperatureType::CELSIUS, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidWeightFrom() : void
    {
        Measurement::convertWeight(1.1, 'invalid', WeightType::KILOGRAM);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidWeightTo() : void
    {
        Measurement::convertWeight(1.1, WeightType::KILOGRAM, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLengthFrom() : void
    {
        Measurement::convertLength(1.1, 'invalid', LengthType::METERS);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLengthTo() : void
    {
        Measurement::convertLength(1.1, LengthType::METERS, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAreaFrom() : void
    {
        Measurement::convertArea(1.1, 'invalid', AreaType::SQUARE_METERS);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAreaTo() : void
    {
        Measurement::convertArea(1.1, AreaType::SQUARE_METERS, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidVolumeFrom() : void
    {
        Measurement::convertVolume(1.1, 'invalid', VolumeType::LITER);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidVolumeTo() : void
    {
        Measurement::convertVolume(1.1, VolumeType::LITER, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSpeedFrom() : void
    {
        Measurement::convertSpeed(1.1, 'invalid', SpeedType::KILOMETERS_PER_HOUR);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSpeedTo() : void
    {
        Measurement::convertSpeed(1.1, SpeedType::KILOMETERS_PER_HOUR, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTimeFrom() : void
    {
        Measurement::convertTime(1.1, 'invalid', TimeType::HOURS);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTimeTo() : void
    {
        Measurement::convertTime(1.1, TimeType::HOURS, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAngleFrom() : void
    {
        Measurement::convertAngle(1.1, 'invalid', AngleType::RADIAN);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAngleTo() : void
    {
        Measurement::convertAngle(1.1, AngleType::RADIAN, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPressureFrom() : void
    {
        Measurement::convertPressure(1.1, 'invalid', PressureType::BAR);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPressureTo() : void
    {
        Measurement::convertPressure(1.1, PressureType::BAR, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidEnergyPowerFrom() : void
    {
        Measurement::convertEnergy(1.1, 'invalid', EnergyPowerType::JOULS);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidEnergyPowerTo() : void
    {
        Measurement::convertEnergy(1.1, EnergyPowerType::JOULS, 'invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFileSizeFrom() : void
    {
        Measurement::convertFileSize(1.1, 'invalid', FileSizeType::KILOBYTE);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFileSizeTo() : void
    {
        Measurement::convertFileSize(1.1, FileSizeType::KILOBYTE, 'invalid');
    }
}
