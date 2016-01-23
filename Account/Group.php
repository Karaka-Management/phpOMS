<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Account;

/**
 * Account group class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Group
{

    /**
     * Account id.
     *
     * @var int
     * @since 1.0.0
     */
    protected $id = 0;

    /**
     * Account name.
     *
     * @var string
     * @since 1.0.0
     */
    protected $name = '';

    /**
     * Account name.
     *
     * @var string
     * @since 1.0.0
     */
    protected $description = '';

    /**
     * Account name.
     *
     * @var int
     * @since 1.0.0
     */
    protected $members = [];

    /**
     * Parents .
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $parents = [];

    /**
     * Permissions.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $permissions = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * Get group id.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get group name.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get group description.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Set group name.
     *
     * @param string $name Group name
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set group description.
     *
     * @param string $description Group description
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

}
