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

use phpOMS\Localization\Localization;
use phpOMS\Localization\NullLocalization;
use phpOMS\Validation\Base\Email;

/**
 * Account manager class.
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Account
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
    protected $created = null;

    /**
     * Permissions.
     *
     * @var array
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
     * Account type.
     *
     * @var AccountType
     * @since 1.0.0
     */
    protected $type = AccountType::USER;

    /**
     * Account status.
     *
     * @var AccountStatus
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * Get account id.
     *
     * @return int Account id
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getL11n() : Localization
    {
        return $this->l11n ?? new NullLocalization();
    }

    /**
     * Set localization.
     *
     * @param Localization $l11n Localization
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setL11n(Localization $l11n)
    {
        $this->l11n = $l11n;
    }

    /**
     * Get name1.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName1() : string
    {
        return $this->name1;
    }

    /**
     * Get name2.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName2() : string
    {
        return $this->name2;
    }

    /**
     * Get name3.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName3() : string
    {
        return $this->name3;
    }

    /**
     * Get email.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Get status.
     *
     * AccountStatus
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Get type.
     *
     * AccountType
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Get last activity.
     *
     * @return \DateTime
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLastActive() : \DateTime
    {
        return $this->lastActive ?? new \DateTime('NOW');
    }

    /**
     * Get last activity.
     *
     * @return \DateTime
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCreated() : \DateTime
    {
        return $this->created ?? new \DateTime('NOW');
    }

    /**
     * Set name1.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setName1(string $name)
    {
        $this->name1 = $name;
    }

    /**
     * Set name2.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setName2(string $name)
    {
        $this->name2 = $name;
    }

    /**
     * Set name3.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setName3(string $name)
    {
        $this->name3 = $name;
    }

    /**
     * Set email.
     *
     * @param string $email Email
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setEmail(string $email)
    {
        if(!Email::isValid($email)) {
            throw new \InvalidArgumentException();
        }

        $this->email = $email;
    }

    /**
     * Get status.
     *
     * @param int $status Status
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setStatus(int $status)
    {
        if(!AccountStatus::isValidValue($status)) {
            throw new \InvalidArgumentException();
        }

        $this->status = $status;
    }

    /**
     * Get type.
     *
     * @param int $type Type
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setType(int $type)
    {
        if(!AccountType::isValidValue($type)) {
            throw new \InvalidArgumentException();
        }

        $this->type = $type;
    }

    /**
     * Get last activity.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function updateLastActive()
    {
        $this->lastActive = new \DateTime('NOW');
    }

}
