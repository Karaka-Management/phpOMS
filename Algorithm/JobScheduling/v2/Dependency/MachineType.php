<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Scheduling\Dependency
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Scheduling\Dependency;

/**
 * Machine type.
 *
 * @package phpOMS\Scheduling\Dependency
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class MachineType
{
    public int $id = 0;

    public array $idle = [];

    public array $qualifications = []; // qualifications needed

    // array of arrays, where each operator type requires certain qualifications
    public array $workerTypes = [];
}
