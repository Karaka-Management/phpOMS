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

class Group
{

    /**
     * Account id.
     *
     * @var \int
     * @since 1.0.0
     */
    protected $id = 0;

    /**
     * Account name.
     *
     * @var \string
     * @since 1.0.0
     */
    protected $name = '';

    /**
     * Account name.
     *
     * @var \string
     * @since 1.0.0
     */
    protected $description = '';

    /**
     * Account name.
     *
     * @var \int
     * @since 1.0.0
     */
    protected $members = [];

    /**
     * Parents .
     *
     * @var \int[]
     * @since 1.0.0
     */
    protected $parents = [];

    /**
     * Permissions.
     *
     * @var \int[]
     * @since 1.0.0
     */
    protected $permissions = [];

    /**
     * Multition cache.
     *
     * @var \Model\Account[]
     * @since 1.0.0
     */
    private static $instances = [];

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
     * Multition constructor.
     *
     * @param \int $id Account id
     *
     * @return \phpOMS\Account\Group
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getInstance($id)
    {
        return self::$instances[$id] = self::$instances[$id] ?? new self();
    }

    /**
     * Get account id.
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get account name.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName() : \string
    {
        return $this->name;
    }

    /**
     * Get group description.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDescription() : \string
    {
        return $this->description;
    }

}
