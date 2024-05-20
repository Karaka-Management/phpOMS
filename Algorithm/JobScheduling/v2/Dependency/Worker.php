<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Scheduling\Dependency
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Scheduling\Dependency;

/**
 * Worker.
 *
 * @package phpOMS\Scheduling\Dependency
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Worker
{
    public int $id = 0;

    public int $type = 0;

    public array $idle = [];

    public array $qualifications = [];
}
