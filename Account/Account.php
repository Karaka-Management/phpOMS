<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Contract\ArrayableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Localization\NullLocalization;
use phpOMS\Validation\Network\Email;

/**
 * Account manager class.
 *
 * @category   Framework
 * @package    phpOMS\Account
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Account implements ArrayableInterface, \JsonSerializable
{

    /**
     * Id.
     *
     * @var int
     * @since 1.0.0
     */
    protected $id = 0;

    /**
     * Names.
     *
     * @var string
     * @since 1.0.0
     */
    protected $name1 = '';

    /**
     * Names.
     *
     * @var string
     * @since 1.0.0
     */
    protected $name2 = '';

    /**
     * Names.
     *
     * @var string
     * @since 1.0.0
     */
    protected $name3 = '';

    /**
     * Email.
     *
     * @var string
     * @since 1.0.0
     */
    protected $email = '';

    /**
     * Ip.
     *
     * Used in order to make sure ips don't change
     *
     * @var string
     * @since 1.0.0
     */
    protected $origin = '';

    /**
     * Login.
     *
     * @var string
     * @since 1.0.0
     */
    protected $login = '';

    /**
     * Last activity.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected $lastActive = null;

    /**
     * Last activity.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    protected $createdAt = null;

    /**
     * Permissions.
     *
     * @var PermissionAbstract[]
     * @since 1.0.0
     */
    protected $permissions = [];

    /**
     * Groups.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected $groups = [];

    /**
     * Password.
     *
     * @var string
     * @since 1.0.0
     */
    protected $password = '';

    /**
     * Account type.
     *
     * @var AccountType|int
     * @since 1.0.0
     */
    protected $type = AccountType::USER;

    /**
     * Account status.
     *
     * @var AccountStatus|int
     * @since 1.0.0
     */
    protected $status = AccountStatus::INACTIVE;

    /**
     * Localization.
     *
     * @var Localization
     * @since 1.0.0
     */
    protected $l11n = null;

    /**
     * Constructor.
     *
     * @param int $id Account id
     *
     * @since  1.0.0
     */
    public function __construct(int $id = 0)
    {
        $this->createdAt = new \DateTime('now');
        $this->lastActive = new \DateTime('now');
        $this->id        = $id;
        $this->l11n      = new NullLocalization();
    }

    /**
     * Get account id.
     *
     * @return int Account id
     *
     * @since  1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get localization.
     *
     * @return Localization
     *
     * @since  1.0.0
     */
    public function getL11n() : Localization
    {
        return $this->l11n;
    }

    /**
     * Get groups.
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getGroups() : array
    {
        return $this->groups;
    }

    /**
     * Set localization.
     *
     * @param Localization $l11n Localization
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setL11n(Localization $l11n) /* : void */
    {
        $this->l11n = $l11n;
    }

    /**
     * Set permissions.
     *
     * @param PermissionAbstract[] $permissions
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setPermissions(array $permissions) /* : void */
    {
        $this->permissions = $permissions;
    }

    /**
     * Add permissions.
     *
     * @param PermissionAbstract[] $permissions
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function addPermissions(array $permissions) /* : void */
    {
        $this->permissions += $permissions;
    }

    /**
     * Add permission.
     *
     * @param PermissionAbstract $permission
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function addPermission(PermissionAbstract $permission) /* : void */
    {
        $this->permissions[] = $permission;
    }

    /**
     * Has permissions.
     *
     * @param int $permission Check if user has this permission
     * @param int $unit Unit
     * @param string $app App
     * @param int $module Module
     * @param int $type Type (e.g. customer)
     * @param int $element (e.g. customer id)
     * @param int $component (e.g. address)
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function hasPermission(int $permission, int $unit = null, string $app = null, int $module = null, int $type = null, $element = null, $component = null) : bool
    {
        $app = isset($app) ? strtolower($app) : $app;

        foreach($this->permissions as $p) {
            if(($p->getUnit() === $unit || $p->getUnit() === null || !isset($unit))
                && ($p->getApp() === $app || $p->getApp() === null || !isset($app)) 
                && ($p->getModule() === $module || $p->getModule() === null || !isset($module)) 
                && ($p->getType() === $type || $p->getType() === null || !isset($type)) 
                && ($p->getElement() === $element || $p->getElement() === null || !isset($element)) 
                && ($p->getComponent() === $component || $p->getComponent() === null || !isset($component)) 
                && ($p->getPermission() | $permission) === $p->getPermission()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get name.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getName() : string
    {
        return $this->login;
    }

    /**
     * Get name1.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getName1() : string
    {
        return $this->name1;
    }

    /**
     * Set name1.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setName1(string $name) /* : void */
    {
        $this->name1 = $name;
    }

    /**
     * Get name2.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getName2() : string
    {
        return $this->name2;
    }

    /**
     * Set name2.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setName2(string $name) /* : void */
    {
        $this->name2 = $name;
    }

    /**
     * Get name3.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getName3() : string
    {
        return $this->name3;
    }

    /**
     * Set name3.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setName3(string $name) /* : void */
    {
        $this->name3 = $name;
    }

    /**
     * Get email.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email Email
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setEmail(string $email) /* : void */
    {
        if (!Email::isValid($email)) {
            throw new \InvalidArgumentException();
        }

        $this->email = mb_strtolower($email);
    }

    /**
     * Get status.
     *
     * AccountStatus
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Get status.
     *
     * @param int $status Status
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setStatus(int $status) /* : void */
    {
        if (!AccountStatus::isValidValue($status)) {
            throw new \InvalidArgumentException();
        }

        $this->status = $status;
    }

    /**
     * Get type.
     *
     * AccountType
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Get type.
     *
     * @param int $type Type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setType(int $type) /* : void */
    {
        if (!AccountType::isValidValue($type)) {
            throw new \InvalidArgumentException();
        }

        $this->type = $type;
    }

    /**
     * Get last activity.
     *
     * @return \DateTime
     *
     * @since  1.0.0
     */
    public function getLastActive() : \DateTime
    {
        return $this->lastActive ?? $this->getCreatedAt();
    }

    /**
     * Get created at.
     *
     * @return \DateTime
     *
     * @since  1.0.0
     */
    public function getCreatedAt() : \DateTime
    {
        return $this->createdAt ?? new \DateTime('NOW');
    }

    /**
     * Generate password.
     *
     * @param string $password Password
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function generatePassword(string $password) /* : void */
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Set name.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setName(string $name) /* : void */
    {
        $this->login = $name;
    }

    /**
     * Update last activity.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function updateLastActive() /* : void */
    {
        $this->lastActive = new \DateTime('NOW');
    }

    /**
     * Get string representation.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function __toString()
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
            'name'        => [
                $this->name1,
                $this->name2,
                $this->name3,
            ],
            'email'       => $this->email,
            'login'       => $this->login,
            'groups'      => $this->groups,
            'permissions' => $this->permissions,
            'type'        => $this->type,
            'status'      => $this->status,
        ];
    }

    /**
     * Json serialize.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

}
