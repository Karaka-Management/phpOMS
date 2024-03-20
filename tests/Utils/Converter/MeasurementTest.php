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
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Converter\Measurement::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Converter\MeasurementTest: Measurement converter')]
final class MeasurementTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Temperatures can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Weights can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Lengths can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Areas can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Volumes can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Speeds can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Times can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Angles can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Pressures can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Energies can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('File sizes can be converted')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown temperature throws a InvalidArgumentException')]
    public function testInvalidTemperatureFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTemperature(1.1, 'invalid', TemperatureType::CELSIUS);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown temperature throws a InvalidArgumentException')]
    public function testInvalidTemperatureTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTemperature(1.1, TemperatureType::CELSIUS, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown weight throws a InvalidArgumentException')]
    public function testInvalidWeightFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertWeight(1.1, 'invalid', WeightType::KILOGRAM);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown weight throws a InvalidArgumentException')]
    public function testInvalidWeightTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertWeight(1.1, WeightType::KILOGRAM, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown length throws a InvalidArgumentException')]
    public function testInvalidLengthFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertLength(1.1, 'invalid', LengthType::METERS);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown length throws a InvalidArgumentException')]
    public function testInvalidLengthTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertLength(1.1, LengthType::METERS, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown area throws a InvalidArgumentException')]
    public function testInvalidAreaFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertArea(1.1, 'invalid', AreaType::SQUARE_METERS);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown area throws a InvalidArgumentException')]
    public function testInvalidAreaTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertArea(1.1, AreaType::SQUARE_METERS, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown volume throws a InvalidArgumentException')]
    public function testInvalidVolumeFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertVolume(1.1, 'invalid', VolumeType::LITER);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown volume throws a InvalidArgumentException')]
    public function testInvalidVolumeTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertVolume(1.1, VolumeType::LITER, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown speed throws a InvalidArgumentException')]
    public function testInvalidSpeedFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertSpeed(1.1, 'invalid', SpeedType::KILOMETERS_PER_HOUR);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown speed throws a InvalidArgumentException')]
    public function testInvalidSpeedTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertSpeed(1.1, SpeedType::KILOMETERS_PER_HOUR, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown time throws a InvalidArgumentException')]
    public function testInvalidTimeFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTime(1.1, 'invalid', TimeType::HOURS);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown time throws a InvalidArgumentException')]
    public function testInvalidTimeTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertTime(1.1, TimeType::HOURS, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown angle throws a InvalidArgumentException')]
    public function testInvalidAngleFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertAngle(1.1, 'invalid', AngleType::RADIAN);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown angle throws a InvalidArgumentException')]
    public function testInvalidAngleTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertAngle(1.1, AngleType::RADIAN, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown pressure throws a InvalidArgumentException')]
    public function testInvalidPressureFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertPressure(1.1, 'invalid', PressureType::BAR);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown pressure throws a InvalidArgumentException')]
    public function testInvalidPressureTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertPressure(1.1, PressureType::BAR, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown energy throws a InvalidArgumentException')]
    public function testInvalidEnergyPowerFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertEnergy(1.1, 'invalid', EnergyPowerType::JOULES);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown energy throws a InvalidArgumentException')]
    public function testInvalidEnergyPowerTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertEnergy(1.1, EnergyPowerType::JOULES, 'invalid');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion from unknown filesize throws a InvalidArgumentException')]
    public function testInvalidFileSizeFrom() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertFileSize(1.1, 'invalid', FileSizeType::KILOBYTE);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid conversion to unknown filesize throws a InvalidArgumentException')]
    public function testInvalidFileSizeTo() : void
    {
        $this->expectException(\InvalidArgumentException::class);

        Measurement::convertFileSize(1.1, FileSizeType::KILOBYTE, 'invalid');
    }
}
