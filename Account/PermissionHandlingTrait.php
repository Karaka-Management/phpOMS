<?php
/**
 * Jingga
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

/**
 * Permission handling trait.
 *
 * @package phpOMS\Account
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
trait PermissionHandlingTrait
{
    /**
     * Permissions.
     *
     * @var PermissionAbstract[]
     * @since 1.0.0
     */
    public array $permissions = [];

    /**
     * Set permissions.
     *
     * The method accepts an array of permissions. All existing permissions are replaced.
     *
     * @param PermissionAbstract[] $permissions Permissions
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPermissions(array $permissions) : void
    {
        $this->permissions = $permissions;
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function addPermission(PermissionAbstract $permission) : void
    {
        $this->permissions[] = $permission;
    }

    /**
     * Remove permission.
     *
     * @param PermissionAbstract $permission Permission to remove
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function removePermission(PermissionAbstract $permission) : void
    {
        foreach ($this->permissions as $key => $p) {
            if ($p->isEqual($permission)) {
                unset($this->permissions[$key]);
            }
        }
    }

    /**
     * Get permissions.
     *
     * @return PermissionAbstract[]
     *
     * @since 1.0.0
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
     * @param null|int    $app        App App to check  (null if all are acceptable)
     * @param null|string $module     Module Module to check  (null if all are acceptable)
     * @param null|int    $category   Type (e.g. customer) (null if all are acceptable)
     * @param null|int    $element    (e.g. customer id) (null if all are acceptable)
     * @param null|int    $component  (e.g. address) (null if all are acceptable)
     *
     * @return bool Returns true if the permission is set, false otherwise
     *
     * @since 1.0.0
     */
    public function hasPermission(
        int $permission,
        int $unit = null,
        int $app = null,
        string $module = null,
        int $category = null,
        int $element = null,
        int $component = null
    ) : bool
    {
        foreach ($this->permissions as $p) {
            if ($p->hasPermission($permission, $unit, $app, $module, $category, $element, $component)) {
                return true;
            }
        }

        return false;
    }
}
