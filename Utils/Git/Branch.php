<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\Git
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\Git;

/**
 * Gray encoding class
 *
 * @package phpOMS\Utils\Git
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Branch
{
    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Constructor
     *
     * @param string $name Branch name
     *
     * @since 1.0.0
     */
    public function __construct(string $name = '')
    {
        $this->setName($name);
    }

    /**
     * Set branch name
     *
     * @param string $name Branch name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }
}
