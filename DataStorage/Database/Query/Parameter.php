<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Parameter
{
    /**
     * Parameter name
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Constructor.
     *
     * @param string $name Name of the parameter
     *
     * @since 1.0.0
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Render the parameter as string
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return ':' . $this->name;
    }
}
