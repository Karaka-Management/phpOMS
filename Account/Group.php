<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Account;

/**
 * Account group class.
 *
 * @package phpOMS\Account
 * @license OMS License 2.2
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
    public int $id = 0;

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
    public array $members = [];

    /**
     * Parents.
     *
     * @var int[]
     * @since 1.0.0
     */
    public array $parents = [];

    /**
     * Group status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = GroupStatus::INACTIVE;

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
            'parents'     => $this->parents,
            'status'      => $this->status,
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

    /**
     * Create object from array
     *
     * @param array{id:int, name:string, description:string, members:array, parents:array, status:int, permissions?:array} $group Group data
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function fromJson(array $group) : self
    {
        $new = new self();

        $new->id          = $group['id'];
        $new->name        = $group['name'];
        $new->description = $group['description'];
        $new->members     = $group['members'];
        $new->parents     = $group['parents'];
        $new->status      = $group['status'];

        foreach (($group['permissions'] ?? []) as $permission) {
            $new->permissions[] = PermissionAbstract::fromJson($permission);
        }

        return $new;
    }
}
