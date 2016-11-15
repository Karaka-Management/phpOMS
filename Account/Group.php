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

use phpOMS\Contract\ArrayableInterface;

/**
 * Account group class.
 *
 * @category   Framework
 * @package    phpOMS\Account
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Group implements ArrayableInterface, \JsonSerializable
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
     * Parents.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $parents = [];

    /**
     * Group status.
     *
     * @var int
     * @since 1.0.0
     */
    protected $status = GroupStatus::INACTIVE;

    /**
     * Permissions.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $permissions = [];

    /**
     * Created at.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected $createdAt = null;

    /**
     * Created by.
     *
     * @var int
     * @since 1.0.0
     */
    protected $createdBy = 0;

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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

    /**
     * Get group status.
     *
     * @return int Group status
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Set group status.
     *
     * @param int $status Group status
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setStatus(int $status)
    {
        // todo: check valid
        $this->status = $status;
    }

    /**
     * Get string representation.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __toString()
    {
        return $this->jsonSerialize();
    }

    /**
     * Json serialize.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function jsonSerialize()
    {
        return json_encode($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'createdBy'   => $this->createdBy,
            'createdAt'   => $this->createdAt->format('Y-m-d H:i:s'),
            'permissions' => $this->permissions,
            'members'     => $this->members,
        ];
    }
}
