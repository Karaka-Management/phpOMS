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

/**
 * Permission class.
 *
 * This permission abstract is the basis for all permissions. Contrary to it's name it is not an
 * abstract class and can be used directly if needed.
 *
 * @package phpOMS\Account
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class PermissionAbstract implements \JsonSerializable
{
    /**
     * Permission id.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Unit id.
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $unit = null;

    /**
     * App name.
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $app = null;

    /**
     * Module id.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $module = null;

    /**
     * Providing module id.
     *
     * @var string
     * @since 1.0.0
     */
    public ?string $from = null;

    /**
     * Type.
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $category = null;

    /**
     * Element id.
     *
     * null === all
     * int === specific
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $element = null;

    /**
     * Component id.
     *
     * null === all
     * int === specific
     * 0 === own data
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $component = null;

    /**
     * Permission.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasRead = false;

    /**
     * Permission.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasModify = false;

    /**
     * Permission.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasCreate = false;

    /**
     * Default create permissions
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $defaultCPermissions = null;

    /**
     * Permission.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasDelete = false;

    /**
     * Permission.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasPermission = false;

    /**
     * Default permission permissions
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $defaultPPermissions = null;

    /**
     * Constructor.
     *
     * @param null|int    $unit       Unit to check (null if all are acceptable)
     * @param null|int    $app        App to check  (null if all are acceptable)
     * @param null|string $module     Module Module to check  (null if all are acceptable)
     * @param null|string $from       Provided by which module
     * @param null|int    $category   Category (e.g. customer) (null if all are acceptable)
     * @param null|int    $element    (e.g. customer id) (null if all are acceptable)
     * @param null|int    $component  (e.g. address) (null if all are acceptable)
     * @param int         $permission Permission to check
     *
     * @since 1.0.0
     */
    public function __construct(
        ?int $unit = null,
        ?int $app = null,
        ?string $module = null,
        ?string $from = null,
        ?int $category = null,
        ?int $element = null,
        ?int $component = null,
        int $permission = PermissionType::NONE
    ) {
        $this->unit      = $unit;
        $this->app       = $app;
        $this->module    = $module;
        $this->from      = $from;
        $this->category  = $category;
        $this->element   = $element;
        $this->component = $component;

        $this->hasRead       = ($permission & PermissionType::READ) === PermissionType::READ;
        $this->hasCreate     = ($permission & PermissionType::CREATE) === PermissionType::CREATE;
        $this->hasModify     = ($permission & PermissionType::MODIFY) === PermissionType::MODIFY;
        $this->hasDelete     = ($permission & PermissionType::DELETE) === PermissionType::DELETE;
        $this->hasPermission = ($permission & PermissionType::PERMISSION) === PermissionType::PERMISSION;
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
        $permission = 0;

        if ($this->hasRead) {
            $permission |= PermissionType::READ;
        }

        if ($this->hasCreate) {
            $permission |= PermissionType::CREATE;
        }

        if ($this->hasModify) {
            $permission |= PermissionType::MODIFY;
        }

        if ($this->hasDelete) {
            $permission |= PermissionType::DELETE;
        }

        if ($this->hasPermission) {
            $permission |= PermissionType::PERMISSION;
        }

        return $permission === 0 ? PermissionType::NONE : $permission;
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
        $this->hasRead       = ($permission & PermissionType::READ) === PermissionType::READ;
        $this->hasCreate     = ($permission & PermissionType::CREATE) === PermissionType::CREATE;
        $this->hasModify     = ($permission & PermissionType::MODIFY) === PermissionType::MODIFY;
        $this->hasDelete     = ($permission & PermissionType::DELETE) === PermissionType::DELETE;
        $this->hasPermission = ($permission & PermissionType::PERMISSION) === PermissionType::PERMISSION;
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
        switch($permission) {
            case PermissionType::READ:
                $this->hasRead = true;
                break;
            case PermissionType::CREATE:
                $this->hasCreate = true;
                break;
            case PermissionType::MODIFY:
                $this->hasModify = true;
                break;
            case PermissionType::DELETE:
                $this->hasDelete = true;
                break;
            case PermissionType::PERMISSION:
                $this->hasPermission = true;
                break;
        }
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
        return ($this->getPermission() & $permission) === $permission;
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
     * @param null|int    $category   Category (e.g. customer) (null if all are acceptable)
     * @param null|int    $element    (e.g. customer id) (null if all are acceptable)
     * @param null|int    $component  (e.g. address) (null if all are acceptable)
     *
     * @return bool Returns true if the permission is set, false otherwise
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
        return $permission === PermissionType::NONE ||
            (($unit === null || $this->unit === null || $this->unit === $unit)
            && ($app === null || $this->app === null || $this->app === $app)
            && ($module === null || $this->module === null || $this->module === $module)
            && ($category === null || $this->category === null || $this->category === $category)
            && ($element === null || $this->element === null || $this->element === $element)
            && ($component === null || $this->component === null || $this->component === $component)
            && ($this->getPermission() & $permission) === $permission);
    }

    /**
     * Is equals.
     *
     * @param self $permission Permission
     *
     * @return bool Returns true if the permission is the same
     *
     * @since 1.0.0
     */
    public function isEqual(self $permission) : bool
    {
        return $this->unit === $permission->unit
            && $this->app === $permission->app
            && $this->module === $permission->module
            && $this->category === $permission->category
            && $this->element === $permission->element
            && $this->component === $permission->component
            && $this->getPermission() === $permission->getPermission();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return [
            'id'                  => $this->id,
            'unit'                => $this->unit,
            'app'                 => $this->app,
            'module'              => $this->module,
            'from'                => $this->from,
            'category'            => $this->category,
            'element'             => $this->element,
            'component'           => $this->component,
            'hasRead'             => $this->hasRead,
            'hasModify'           => $this->hasModify,
            'hasCreate'           => $this->hasCreate,
            'hasDelete'           => $this->hasDelete,
            'defaultCPermissions' => $this->defaultCPermissions,
            'hasPermission'       => $this->hasPermission,
            'defaultPPermissions' => $this->defaultPPermissions,
        ];
    }

    /**
     * Create object from json string
     *
     * @param array{id:int, unit:?int, app:?int, module:?string, from:?string, category:?int, element:?int, component:?int, hasRead:bool, hasModify:bool, hasCreate:bool, hasDelete:bool, defaultCPermissions:?string, hasPermission:bool, defaultPPermissions:?string} $permission Permission
     *
     * @return self
     *
     * @since 1.0.0
     */
    public static function fromJson(array $permission) : self
    {
        $new = new self();

        $new->id                  = $permission['id'];
        $new->unit                = $permission['unit'];
        $new->app                 = $permission['app'];
        $new->module              = $permission['module'];
        $new->from                = $permission['from'];
        $new->category            = $permission['category'];
        $new->element             = $permission['element'];
        $new->component           = $permission['component'];
        $new->hasRead             = $permission['hasRead'];
        $new->hasModify           = $permission['hasModify'];
        $new->hasCreate           = $permission['hasCreate'];
        $new->hasDelete           = $permission['hasDelete'];
        $new->defaultCPermissions = $permission['defaultCPermissions'];
        $new->hasPermission       = $permission['hasPermission'];
        $new->defaultPPermissions = $permission['defaultPPermissions'];

        return $new;
    }
}
