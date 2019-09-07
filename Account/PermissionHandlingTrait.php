<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Account
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Account;

/**
 * Permission handling trait.
 *
 * @package    phpOMS\Account
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
trait PermissionHandlingTrait
{
    /**
     * Permissions.
     *
     * @var PermissionAbstract[]
     * @since 1.0.0
     */
    protected array $permissions = [];

    /**
     * Amount of permissions.
     *
     * @var int
     * @since 1.0.0
     */
    private int $pLength = 0;

    /**
     * Set permissions.
     *
     * The method accepts an array of permissions. All existing permissions are replaced.
     *
     * @param PermissionAbstract[] $permissions Permissions
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setPermissions(array $permissions) : void
    {
        $this->permissions = $permissions;
        $this->pLength     = \count($this->permissions);
    }

    /**
     * Add permissions.
     *
     * Adds permissions
     *
     * @param array<array|PermissionAbstract> $permissions Array of permissions to add
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function addPermissions(array $permissions) : void
    {
        foreach ($permissions as $permission) {
            if (\is_array($permission)) {
                $this->permissions = \array_merge($this->permissions, $permission);
            } else {
                $this->permissions[] = $permission;
            }
        }

        $this->pLength = \count($this->permissions);
    }

    /**
     * Add permission.
     *
     * Adds a single permission
     *
     * @param PermissionAbstract $permission Permission to add
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function addPermission(PermissionAbstract $permission) : void
    {
        $this->permissions[] = $permission;
        ++$this->permission;
    }

    /**
     * Get permissions.
     *
     * @return PermissionAbstract[]
     *
     * @since  1.0.0
     */
    public function getPermissions() : array
    {
        return $this->permissions;
    }

    /**
     * Has permissions.
     *
     * Checks if the permission is defined
     *
     * @param int         $permission Permission to check
     * @param null|int    $unit       Unit Unit to check (null if all are acceptable)
     * @param null|string $app        App App to check  (null if all are acceptable)
     * @param null|string $module     Module Module to check  (null if all are acceptable)
     * @param null|int    $type       Type (e.g. customer) (null if all are acceptable)
     * @param null|int    $element    (e.g. customer id) (null if all are acceptable)
     * @param null|int    $component  (e.g. address) (null if all are acceptable)
     *
     * @return bool Returns true if the permission is set, false otherwise
     *
     * @since  1.0.0
     */
    public function hasPermission(
        int $permission,
        int $unit = null,
        string $app = null,
        string $module = null,
        int $type = null,
        int $element = null,
        int $component = null
    ) : bool {
        $app = $app !== null ? \strtolower($app) : $app; // @todo: maybe don't do this because this function get's called so often.

        for ($i = 0; $i < $this->pLength; ++$i) {
            if ($this->permissions[$i]->hasPermission($permission, $unit, $app, $module, $type, $element, $component)) {
                return true;
            }
        }

        return false;
    }
}
