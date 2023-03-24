<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Stdlib\Base\Exception\InvalidEnumValue;

/**
 * Account group class.
 *
 * @package phpOMS\Account
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Group implements \JsonSerializable
{
    /**
     * Group id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Group name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Group name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $description = '';

    /**
     * Group members.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $members = [];

    /**
     * Parents.
     *
     * @var int[]
     * @since 1.0.0
     */
    protected array $parents = [];

    /**
     * Group status.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $status = GroupStatus::INACTIVE;

    use PermissionHandlingTrait;

    /**
     * Get group id.
     *
     * @return int Returns the id of the group
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get group status.
     *
     * @return int Group status
     *
     * @since 1.0.0
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
     * @return void
     *
     * @throws InvalidEnumValue This exception is thrown if an invalid status is used
     *
     * @since 1.0.0
     */
    public function setStatus(int $status) : void
    {
        if (!GroupStatus::isValidValue($status)) {
            throw new InvalidEnumValue($status);
        }

        $this->status = $status;
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
            'name'        => $this->name,
            'description' => $this->description,
            'permissions' => $this->permissions,
            'members'     => $this->members,
        ];
    }

    /**
     * Json serialize.
     *
     * @return array<string, mixed>
     *
     * @since 1.0.0
     */

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
