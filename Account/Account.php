<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Localization\Localization;
use phpOMS\Validation\Network\Email;

/**
 * Account class.
 *
 * The account class is the base model for accounts. This model contains the most common account
 * information. This model is not comparable to a profile which contains much more information.
 *
 * @package phpOMS\Account
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Account implements \JsonSerializable
{
    /**
     * Id.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Names.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name1 = '';

    /**
     * Names.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name2 = '';

    /**
     * Names.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name3 = '';

    /**
     * Email.
     *
     * @var string
     * @since 1.0.0
     */
    public string $email = '';

    /**
     * Ip.
     *
     * Used in order to make sure ips don't change
     *
     * @var string
     * @since 1.0.0
     */
    public string $origin = '';

    /**
     * Login.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $login = null;

    /**
     * Last activity.
     *
     * @var \DateTime
     * @since 1.0.0
     */
    public \DateTime $lastActive;

    /**
     * Last activity.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Groups.
     *
     * @var Group[]
     * @since 1.0.0
     */
    public array $groups = [];

    /**
     * Password.
     *
     * @var string
     * @since 1.0.0
     */
    public string $password = '';

    /**
     * Account type.
     *
     * @var int
     * @since 1.0.0
     */
    public int $type = AccountType::USER;

    /**
     * Account status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = AccountStatus::INACTIVE;

    /**
     * Localization.
     *
     * @var Localization
     * @since 1.0.0
     */
    public Localization $l11n;

    use PermissionHandlingTrait;

    /**
     * Has permission.
     *
     * @param int         $permission Permission
     * @param int|null    $unit       Unit
     * @param int|null    $app        App
     * @param string|null $module     Module
     * @param int|null    $category   Category
     * @param int|null    $element    Element
     * @param int|null    $component  Component
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasPermission(
        int $permission,
        ?int $unit = null,
        ?int $app = null,
        ?string $module = null,
        ?int $category = null,
        ?int $element = null,
        ?int $component = null
    ) : bool
    {
        foreach ($this->groups as $group) {
            if ($group->hasPermission($permission, $unit, $app, $module, $category, $element, $component)) {
                return true;
            }
        }

        foreach ($this->permissions as $p) {
            if ($p->hasPermission($permission, $unit, $app, $module, $category, $element, $component)) {
                return true;
            }
        }

        return false;
    }

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
        $this->createdAt  = new \DateTimeImmutable('now');
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
     * Get groups.
     *
     * Every account can belong to multiple groups.
     * These groups usually are used for permissions and categorize accounts.
     *
     * @return Group[] Returns array of all groups
     *
     * @since 1.0.0
     */
    public function getGroups() : array
    {
        return $this->groups;
    }

    /**
     * Get ids of groups
     *
     * @return int[]
     *
     * @since 1.0.0
     */
    public function getGroupIds() : array
    {
        /*
        $ids = [];
        foreach ($this->groups as $group) {
            $ids[] = $group->id;
        }

        return $ids;
        */
        return \array_keys($this->groups);
    }

    /**
     * Add group.
     *
     * @param Group $group Group to add
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addGroup(Group $group) : void
    {
        $this->groups[] = $group;
    }

    /**
     * User has group.
     *
     * @param int $id Group id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasGroup(int $id) : bool
    {
        foreach ($this->groups as $group) {
            if ($group->id === $id) {
                return true;
            }
        }

        return false;
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
     * Get last activity.
     *
     * @return \DateTimeInterface
     *
     * @since 1.0.0
     */
    public function getLastActive() : \DateTimeInterface
    {
        return $this->lastActive ?? $this->createdAt;
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
        $temp = \password_hash($password, \PASSWORD_BCRYPT);

        if ($temp === false) {
            throw new \Exception('Internal password_hash error.'); // @codeCoverageIgnore
        }

        $this->password = $temp;
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
        $this->lastActive = new \DateTime('now');
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
            'id'   => $this->id,
            'name' => [
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
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
