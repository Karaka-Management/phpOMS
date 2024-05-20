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

namespace phpOMS\tests\Algorithm\JobScheduling;

use phpOMS\Algorithm\JobScheduling\Job;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\JobScheduling\Job::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\JobScheduling\JobTest: Default job for the job scheduling')]
final class JobTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The job has the expected values after initialization')]
    public function testDefault() : void
    {
        $item = new Job(3.0, new \DateTime('now'), null, 'abc');

        self::assertEquals(3.0, $item->getValue());
        self::assertEquals((new \DateTime('now'))->format('Y-m-d'), $item->getStart()->format('Y-m-d'));
        self::assertNull($item->getEnd());
        self::assertEquals('abc', $item->name);
    }
}
