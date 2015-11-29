<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\Compiler\Php;

use phpOMS\Utils\ArrayUtils;

/**
 * Php enum compiler.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ClassCompiler
{
    private $path = null;

    private $namespace = null;

    private $class = null;

    private $extends = null;

    private $implements = [];

    private $member = [];

    public function __construct($path)
    {
        $this->path = (string) $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = (string) $path;
    }

    public function setNamesapce($namespace)
    {
        $this->namespace = $namespace;
    }

    public function getNamesapce()
    {
        return $this->namespace;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getExtends()
    {
        return $this->extends;
    }

    public function setExtends($extends)
    {
        $this->extends = $extends;
    }

    public function addImplements($implements)
    {
        $this->implements[] = $implements;
    }

    public function getImplements()
    {
        return $this->implements;
    }

    public function getMember($name)
    {
        return $this->member[$name];
    }

    public function setMember($name, $default = null, $isString = false, $type = 'private', $static = false, $overwrite = true)
    {
        if ($overwrite || !isset($this->member[$name])) {
            $this->member[$name] = ['default'  => $default,
                                    'isString' => $isString,
                                    'type'     => $type,
                                    'static'   => $static,];
        }
    }

    public function removeMember($name)
    {
        unset($this->member[$name]);
    }

    public function __toString()
    {
        $member = '';
        foreach ($this->member as $name => $value) {
            $member .= '    ' . ($value['static'] ? 'static ' : '')
                       . $value['type'] . ' ' . $name;

            if (isset($value['default'])) {
                $member .= ' = ';

                if ($value['isString']) {
                    $member .= $value['default'];
                } else {
                    switch (gettype($value['default'])) {
                        case 'array':
                            $member .= ArrayUtils::stringify($value['default']);
                            break;
                        case 'integer':
                        case 'float':
                        case 'double':
                            $member .= $value['default'];
                            break;
                        case 'string':
                            $member .= '"' . $value['default'] . '"';
                            break;
                        case 'object':
                            $member .= get_class($value['default']) . '()';
                            // TODO: implement object with parameters -> Reflection
                            break;
                        case 'boolean':
                            $member .= ($value['default'] ? 'true' : 'false');
                            break;
                        case 'NULL':
                            $member .= 'null';
                            break;
                        default:
                            throw new \Exception('Unknown default type');
                    }
                }
            }

            $member .= ';' . PHP_EOL;
        }

        return '<?php' . PHP_EOL
               . (isset($this->namespace) ? 'namespace ' . $this->namespace . ';' . PHP_EOL : '')
               . 'abstract class' . $this->class . (isset($this->extends) ? ' extends ' . $this->extends : '') . (isset($this->implements) ? ' implements ' . implode(',', $this->implements) : '') . ' {' . PHP_EOL
               . $member
               . '}';
    }
}
