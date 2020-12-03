<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
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
 * @testdox phpOMS\tests\Utils\Converter\MeasurementTest: Measurement converter
 *
 * @internal
 */
class MeasurementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Temperatures can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testTemperature() : void
    {
        $temps = TemperatureType::getConstants();

        foreach ($temps as $from) {
            foreach ($temps as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertTemperature(Measurement::convertTemperature($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Weights can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testWeight() : void
    {
        $weights = WeightType::getConstants();

        foreach ($weights as $from) {
            foreach ($weights as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertWeight(Measurement::convertWeight($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Lengths can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testLength() : void
    {
        $lengths = LengthType::getConstants();

        foreach ($lengths as $from) {
            foreach ($lengths as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertLength(Measurement::convertLength($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Areas can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testArea() : void
    {
        $areas = AreaType::getConstants();

        foreach ($areas as $from) {
            foreach ($areas as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertArea(Measurement::convertArea($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Volumes can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testVolume() : void
    {
        $volumes = VolumeType::getConstants();

        foreach ($volumes as $from) {
            foreach ($volumes as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertVolume(Measurement::convertVolume($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Speeds can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testSpeed() : void
    {
        $speeds = SpeedType::getConstants();

        foreach ($speeds as $from) {
            foreach ($speeds as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertSpeed(Measurement::convertSpeed($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Times can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testTime() : void
    {
        $times = TimeType::getConstants();

        foreach ($times as $from) {
            foreach ($times as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertTime(Measurement::convertTime($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Angles can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testAngle() : void
    {
        $angles = AngleType::getConstants();

        foreach ($angles as $from) {
            foreach ($angles as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertAngle(Measurement::convertAngle($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Pressures can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testPressure() : void
    {
        $pressures = PressureType::getConstants();

        foreach ($pressures as $from) {
            foreach ($pressures as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertPressure(Measurement::convertPressure($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Energies can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testEnergy() : void
    {
        $energies = EnergyPowerType::getConstants();

        foreach ($energies as $from) {
            foreach ($energies as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertEnergy(Measurement::convertEnergy($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Filesizes can be converted
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testFileSize() : void
    {
        $fileSizes = FileSizeType::getConstants();

        foreach ($fileSizes as $from) {
            foreach ($fileSizes as $to) {
                $rand = \mt_rand(0, 100);
                if ($rand - Measurement::convertFileSize(Measurement::convertFileSize($rand, $from, $to), $to, $from) >= 1) {
                    self::assertTrue(false);
                }
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox Invalid conversion from unknown temperature throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidTemperatureFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTemperature(1.1, 'invalid', TemperatureType::CELSIUS);
    }

    /**
     * @testdox Invalid conversion to unknown temperature throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidTemperatureTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTemperature(1.1, TemperatureType::CELSIUS, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown weight throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidWeightFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertWeight(1.1, 'invalid', WeightType::KILOGRAM);
    }

    /**
     * @testdox Invalid conversion to unknown weight throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidWeightTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertWeight(1.1, WeightType::KILOGRAM, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown length throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidLengthFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertLength(1.1, 'invalid', LengthType::METERS);
    }

    /**
     * @testdox Invalid conversion to unknown length throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidLengthTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertLength(1.1, LengthType::METERS, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown area throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidAreaFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertArea(1.1, 'invalid', AreaType::SQUARE_METERS);
    }

    /**
     * @testdox Invalid conversion to unknown area throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidAreaTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertArea(1.1, AreaType::SQUARE_METERS, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown volume throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidVolumeFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertVolume(1.1, 'invalid', VolumeType::LITER);
    }

    /**
     * @testdox Invalid conversion to unknown volume throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidVolumeTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertVolume(1.1, VolumeType::LITER, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown speed throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidSpeedFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertSpeed(1.1, 'invalid', SpeedType::KILOMETERS_PER_HOUR);
    }

    /**
     * @testdox Invalid conversion to unknown speed throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidSpeedTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertSpeed(1.1, SpeedType::KILOMETERS_PER_HOUR, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown time throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidTimeFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTime(1.1, 'invalid', TimeType::HOURS);
    }

    /**
     * @testdox Invalid conversion to unknown time throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidTimeTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTime(1.1, TimeType::HOURS, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown angle throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidAngleFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertAngle(1.1, 'invalid', AngleType::RADIAN);
    }

    /**
     * @testdox Invalid conversion to unknown angle throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidAngleTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertAngle(1.1, AngleType::RADIAN, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown pressure throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidPressureFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertPressure(1.1, 'invalid', PressureType::BAR);
    }

    /**
     * @testdox Invalid conversion to unknown pressure throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidPressureTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertPressure(1.1, PressureType::BAR, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown energy throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidEnergyPowerFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertEnergy(1.1, 'invalid', EnergyPowerType::JOULS);
    }

    /**
     * @testdox Invalid conversion to unknown energy throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidEnergyPowerTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertEnergy(1.1, EnergyPowerType::JOULS, 'invalid');
    }

    /**
     * @testdox Invalid conversion from unknown filesize throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidFileSizeFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertFileSize(1.1, 'invalid', FileSizeType::KILOBYTE);
    }

    /**
     * @testdox Invalid conversion to unknown filesize throws a InvalidArgumentException
     * @covers phpOMS\Utils\Converter\Measurement
     * @group framework
     */
    public function testInvalidFileSizeTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertFileSize(1.1, FileSizeType::KILOBYTE, 'invalid');
    }
}
