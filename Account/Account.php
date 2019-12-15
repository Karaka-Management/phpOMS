<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Contract\ArrayableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;
use phpOMS\Validation\Network\Email;

/**
 * Account class.
 *
 * The account class is the base model for accounts. This model contains the most common account
 * information. This model is not comparable to a profile which contains much more information.
 *
 * @package phpOMS\Account
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Account implements ArrayableInterface, \JsonSerializable
{
    /**
     * Id.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Names.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $name1 = '';

    /**
     * Names.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $name2 = '';

    /**
     * Names.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $name3 = '';

    /**
     * Email.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $email = '';

    /**
     * Ip.
     *
     * Used in order to make sure ips don't change
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $origin = '';

    /**
     * Login.
     *
     * @var   null|string
     * @since 1.0.0
     */
    protected ?string $login = null;

    /**
     * Last activity.
     *
     * @var   \DateTime
     * @since 1.0.0
     */
    protected \DateTime $lastActive;

    /**
     * Last activity.
     *
     * @var   \DateTime
     * @since 1.0.0
     */
    protected \DateTime $createdAt;

    /**
     * Groups.
     *
     * @var   int[]
     * @since 1.0.0
     */
    protected array $groups = [];

    /**
     * Password.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $password = '';

    /**
     * Account type.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $type = AccountType::USER;

    /**
     * Account status.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $status = AccountStatus::INACTIVE;

    /**
     * Localization.
     *
     * @var   Localization
     * @since 1.0.0
     */
    protected Localization $l11n;

    use PermissionHandlingTrait;

    /**
     * Constructor.
     *
     * The constructor automatically sets the created date as well as the last activity to now.
     *
     * @param int $id Account id
     *
     * @since 1.0.0
     */
    public function __construct(int $id = 0)
    {
        $this->createdAt  = new \DateTime('now');
        $this->lastActive = new \DateTime('now');
        $this->id         = $id;
        $this->l11n       = new Localization();
    }

    /**
     * Get account id.
     *
     * @return int Account id
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get localization.
     *
     * Every account can have a different localization which can be accessed here.
     *
     * @return Localization
     *
     * @since 1.0.0
     */
    public function getL11n() : Localization
    {
        return $this->l11n;
    }

    /**
     * Get groups.
     *
     * Every account can belong to multiple groups.
     * These groups usually are used for permissions and categorize accounts.
     *
     * @return array Returns array of all groups
     *
     * @since 1.0.0
     */
    public function getGroups() : array
    {
        return $this->groups;
    }

    /**
     * Add group.
     *
     * @param mixed $group Group to add
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addGroup($group) : void
    {
        $this->groups[] = $group;
    }

    /**
     * Set localization.
     *
     * @param Localization $l11n Localization
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setL11n(Localization $l11n) : void
    {
        $this->l11n = $l11n;
    }

    /**
     * Get name.
     *
     * @return string Returns the login name or null
     *
     * @since 1.0.0
     */
    public function getName() : ?string
    {
        return $this->login;
    }

    /**
     * Get name1.
     *
     * @return string Returns the name1
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function setName1(string $name) : void
    {
        $this->name1 = $name;
    }

    /**
     * Get name2.
     *
     * @return string Returns name 2
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function setName2(string $name) : void
    {
        $this->name2 = $name;
    }

    /**
     * Get name3.
     *
     * @return string Returns name 3
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function setName3(string $name) : void
    {
        $this->name3 = $name;
    }

    /**
     * Get email.
     *
     * @return string Returns the email address
     *
     * @since 1.0.0
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
     * @throws \InvalidArgumentException Exception is thrown if the provided string is not a valid email
     *
     * @since 1.0.0
     */
    public function setEmail(string $email) : void
    {
        if ($email !== '' && !Email::isValid($email)) {
            throw new \InvalidArgumentException();
        }

        $this->email = \mb_strtolower($email);
    }

    /**
     * Get status.
     *
     * @return int Returns the status (AccountStatus)
     *
     * @since 1.0.0
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
     * @throws InvalidEnumValue This exception is thrown if a invalid status is used
     *
     * @since 1.0.0
     */
    public function setStatus(int $status) : void
    {
        if (!AccountStatus::isValidValue($status)) {
            throw new InvalidEnumValue($status);
        }

        $this->status = $status;
    }

    /**
     * Get type.
     *
     * @return int Returns the type (AccountType)
     *
     * @since 1.0.0
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
     * @throws InvalidEnumValue This exception is thrown if an invalid type is used
     *
     * @since 1.0.0
     */
    public function setType(int $type) : void
    {
        if (!AccountType::isValidValue($type)) {
            throw new InvalidEnumValue($type);
        }

        $this->type = $type;
    }

    /**
     * Get last activity.
     *
     * @return \DateTime
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @throws \Exception Throws this exception if the password_hash function fails
     *
     * @since 1.0.0
     */
    public function generatePassword(string $password) : void
    {
        $temp = \password_hash($password, \PASSWORD_DEFAULT);

        if ($temp === false) {
            throw new \Exception('Internal password_hash error.'); // @codeCoverageIgnore
        }

        $this->password = $temp;
    }

    /**
     * Set name.
     *
     * @param string $name Name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName(string $name) : void
    {
        $this->login = $name;
    }

    /**
     * Update last activity.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function updateLastActive() : void
    {
        $this->lastActive = new \DateTime('NOW');
    }

    /**
     * Get string representation.
     *
     * @return string Returns the json_encode of this object
     *
     * @since 1.0.0
     */
    public function __toString() : string
    {
        return (string) \json_encode($this->toArray());
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
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
