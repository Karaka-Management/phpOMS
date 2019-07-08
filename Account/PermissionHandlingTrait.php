<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Account
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Account;

/**
 * Permission handling trait.
 *
 * @package    phpOMS\Account
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
    protected $permissions = [];

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
        $app = $app !== null ? \strtolower($app) : $app;

        foreach ($this->permissions as $p) {
            if (($unit === null || $p->getUnit() === $unit || $p->getUnit() === null)
                && ($app === null || $p->getApp() === $app || $p->getApp() === null)
                && ($module === null || $p->getModule() === $module || $p->getModule() === null)
                && ($type === null || $p->getType() === $type || $p->getType() === null)
                && ($element === null || $p->getElement() === $element || $p->getElement() === null)
                && ($component === null || $p->getComponent() === $component || $p->getComponent() === null)
                && ($p->getPermission() | $permission) === $p->getPermission()
            ) {
                return true;
            }
        }

        return false;
    }
}
