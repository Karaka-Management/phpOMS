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

/**
 * Permission class.
 *
 * This permission abstract is the basis for all permissions. Contrary to it's name it is not an
 * abstract class and can be used directly if needed.
 *
 * @package phpOMS\Account
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class PermissionAbstract implements \JsonSerializable
{
    /**
     * Permission id.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Unit id.
     *
     * @var   null|int
     * @since 1.0.0
     */
    protected ?int $unit = null;

    /**
     * App name.
     *
     * @var   null|string
     * @since 1.0.0
     */
    protected ?string $app = null;

    /**
     * Module id.
     *
     * @var   null|string
     * @since 1.0.0
     */
    protected ?string $module = null;

    /**
     * Providing module id.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $from = 0;

    /**
     * Type.
     *
     * @var   null|int
     * @since 1.0.0
     */
    protected ?int $type = null;

    /**
     * Element id.
     *
     * @var   null|int
     * @since 1.0.0
     */
    protected ?int $element = null;

    /**
     * Component id.
     *
     * @var   null|int
     * @since 1.0.0
     */
    protected ?int $component = null;

    /**
     * Permission.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $permission = PermissionType::NONE;

    /**
     * Constructor.
     *
     * @param null|int    $unit       Unit Unit to check (null if all are acceptable)
     * @param null|string $app        App App to check  (null if all are acceptable)
     * @param null|string $module     Module Module to check  (null if all are acceptable)
     * @param int         $from       Provided by which module
     * @param null|int    $type       Type (e.g. customer) (null if all are acceptable)
     * @param null|int    $element    (e.g. customer id) (null if all are acceptable)
     * @param null|int    $component  (e.g. address) (null if all are acceptable)
     * @param int         $permission Permission to check
     *
     * @since 1.0.0
     */
    public function __construct(
        int $unit = null,
        string $app = null,
        string $module = null,
        int $from = 0,
        int $type = null,
        int $element = null,
        int $component = null,
        int $permission = PermissionType::NONE
    ) {
        $this->unit       = $unit;
        $this->app        = $app;
        $this->module     = $module;
        $this->from       = $from;
        $this->type       = $type;
        $this->element    = $element;
        $this->component  = $component;
        $this->permission = $permission;
    }

    /**
     * Get permission id.
     *
     * @return int Retunrs the id of the permission
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get unit id.
     *
     * @return null|int
     *
     * @since 1.0.0
     */
    public function getUnit() : ?int
    {
        return $this->unit;
    }

    /**
     * Set unit id.
     *
     * @param null|int $unit Unit
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setUnit(int $unit = null) : void
    {
        $this->unit = $unit;
    }

    /**
     * Get app name.
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    public function getApp() : ?string
    {
        return $this->app;
    }

    /**
     * Set app name.
     *
     * @param string $app App name
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setApp(string $app = null) : void
    {
        $this->app = $app;
    }

    /**
     * Get module id.
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    public function getModule() : ?string
    {
        return $this->module;
    }

    /**
     * Set module id.
     *
     * @param string $module Module
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setModule(string $module = null) : void
    {
        $this->module = $module;
    }

    /**
     * Get providing module id.
     *
     * @return int Returns the module responsible for setting this permission
     *
     * @since 1.0.0
     */
    public function getFrom() : int
    {
        return $this->from;
    }

    /**
     * Set providing module id.
     *
     * @param int $from Providing module
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setFrom(int $from = 0) : void
    {
        $this->from = $from;
    }

    /**
     * Get type.
     *
     * @return null|int
     *
     * @since 1.0.0
     */
    public function getType() : ?int
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param int $type Type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setType(int $type = null) : void
    {
        $this->type = $type;
    }

    /**
     * Get element id.
     *
     * @return null|int
     *
     * @since 1.0.0
     */
    public function getElement() : ?int
    {
        return $this->element;
    }

    /**
     * Set element id.
     *
     * @param int $element Element id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setElement(int $element = null) : void
    {
        $this->element = $element;
    }

    /**
     * Get component id.
     *
     * @return null|int
     *
     * @since 1.0.0
     */
    public function getComponent() : ?int
    {
        return $this->component;
    }

    /**
     * Set component id.
     *
     * @param int $component Component
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setComponent(int $component = null) : void
    {
        $this->component = $component;
    }

    /**
     * Get permission
     *
     * @return int Returns the permission (PermissionType)
     *
     * @since 1.0.0
     */
    public function getPermission() : int
    {
        return $this->permission;
    }

    /**
     * Set permission.
     *
     * @param int $permission Permission
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPermission(int $permission = 0) : void
    {
        $this->permission = $permission;
    }

    /**
     * Add permission.
     *
     * @param int $permission Permission
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addPermission(int $permission = 0) : void
    {
        $this->permission |= $permission;
    }

    /**
     * Has permission.
     *
     * @param int $permission Permission
     *
     * @return bool Returns true if the permission is set otherwise returns false
     *
     * @since 1.0.0
     */
    public function hasPermissionFlags(int $permission) : bool
    {
        return ($this->permission | $permission) === $this->permission;
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
     * @since 1.0.0
     */
    public function hasPermission(
        int $permission,
        int $unit = null,
        string $app = null,
        string $module = null,
        int $type = null,
        int $element = null,
        int $component = null
    ) {
        return ($unit === null || $this->unit === null || $this->unit === $unit)
            && ($app === null || $this->app === null || $this->app === $app)
            && ($module === null || $this->module === null || $this->module === $module)
            && ($type === null || $this->type === null || $this->type === $type)
            && ($element === null || $this->element === null || $this->element === $element)
            && ($component === null || $this->component === null || $this->component === $component)
            && ($this->permission | $permission) === $this->permission;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id'         => $this->id,
            'unit'       => $this->unit,
            'app'        => $this->app,
            'module'     => $this->module,
            'from'       => $this->from,
            'type'       => $this->type,
            'element'    => $this->element,
            'component'  => $this->component,
            'permission' => $this->permission,
        ];
    }
}
