<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 2.0
 * @link    https://jingga.app
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
