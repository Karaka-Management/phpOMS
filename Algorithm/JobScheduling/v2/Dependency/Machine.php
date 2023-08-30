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
 * Machine.
 *
 * @package phpOMS\Scheduling\Dependency
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Machine
{
    public int $id = 0;

    public MachineType $type;

    public array $idle = [];
}
