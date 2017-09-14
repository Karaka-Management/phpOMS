<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);
namespace phpOMS\Account;

/**
 * InfoManager class.
 *
 * Handling the info files for modules
 *
 * @category   Framework
 * @package    phpOMS\Module
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class PermissionAbstract
{
    protected $id = 0;

    protected $unit = null;

    protected $app = null;

    protected $module = null;

    protected $from = 0;

    protected $type = null;

    protected $element = null;

    protected $component = null;

    protected $permission = 0;

    public function getId()
    {
        return $id;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getElement()
    {
        return $this->element;
    }

    public function getComponent()
    {
        return $this->component;
    }

    public function getPermission() : int
    {
        return $this->permission;
    }

    public function setUnit(int $unit = null) /* : void */
    {
        $this->unit = $unit;
    }

    public function setApp(int $app = null) /* : void */
    {
        $this->app = $app;
    }

    public function setModule(int $module = null) /* : void */
    {
        $this->module = $module;
    }

    public function setFrom(int $from = null) /* : void */
    {
        $this->from = $from;
    }

    public function setType(int $type = null) /* : void */
    {
        $this->type = $type;
    }

    public function setElement(int $element = null) /* : void */
    {
        $this->element = $element;
    }

    public function setComponent(int $component = null) /* : void */
    {
        $this->component = $component;
    }

    public function setPermission(int $permission = 0) /* : void */
    {
        if($permission === 0) {
            $this->permission = 0;
        } else {
            $this->permission |= $permission;
        }
    }
}
