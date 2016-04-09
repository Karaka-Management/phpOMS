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
namespace phpOMS\Utils\Parser\Php;

/**
 * Array parser class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @category   Framework
 * @package    phpOMS\Utils\Parser
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ClassParser
{
    const INDENT = 4;
    
    private $isFinal = false;

    private $isAbstract = false;

    private $type = ClassType::_CLASS;

    private $extends = null;

    private $namespace = null;

    private $includes = [];

    private $requires = [];

    private $use = [];

    private $name = '';

    private $implements = [];

    private $traits = [];

    private $members = [];

    private $functions = [];

    public function __construct() {}

    /**
     * Saving class to file.
     *
     * @param string $path Path
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function createFile(string $path)
    {
    }

    public function setFinal(bool $final) 
    {
        $this->isFinal = $final;
    }

    public function isFinal() : bool
    {
        return $this->isFinal;
    }

    public function setAbstract(bool $abstract)
    {
        $this->isAbstract = $abstract;
    }

    public function isAbstract() : bool
    {
        return $this->isAbstract;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function setExtends(string $extends) 
    {
        $this->extends = $extends;
    }

    public function getExtends() : string
    {
        return $this->extends;
    }

    public function removeExtends() 
    {
        $this->extends = null;
    }

    public function setNamespace(string $namespace) 
    {
        $this->namespace = $namespace;
    }

    public function getNamespace() : string
    {
        return $this->namespace;
    }

    public function removeNamespace() 
    {
        $this->namespace = null;
    }

    public function addUse(string $namespace, string $as = null)
    {
        if (isset($as)) {
            $this->use[$as] = $namespace;
        } else {
            $this->use[] = $namespace;
        }
    }

    public function removeUse($id) : bool
    {
        if (isset($this->use[$id])) {
            unset($this->use[$id]);

            return true;
        }

        return false;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function addImplements(string $implements) 
    {
        $this->implements[] = $implements;

        array_unique($this->implements);
    }

    public function addInclude(string $include) 
    {
        $this->include[] = $include;

        array_unique($this->include);
    }

    public function addRequire(string $require) 
    {
        $this->require[] = $require;

        array_unique($this->require);
    }

    public function addTrait(string $trait, string $as = null)
    {
        if (isset($as)) {
            $this->traits[$as] = $trait;
        } else {
            $this->traits[] = $trait;
        }
    }

    public function addMember(MemberParser $member) 
    {
        $this->members[$member->getName()] = $member;
    }

    public function removeMember(string $name) : bool
    {
        if (isset($this->members[$name])) {
            unset($this->members[$name]);

            return true;
        }

        return false;
    }

    public function getMember(string $name) : MemberParser
    {
        return $this->members[$name] ?? new MemberParser();
    }

    public function addFunction(FunctionParser $function) 
    {
        $this->functions[$function->getName()] = $function;
    }

    public function removeFunction(string $name) : bool
    {
        if (isset($this->functions[$name])) {
            unset($this->functions[$name]);

            return true;
        }

        return false;
    }

    public function getFunction(string $name) : FunctionParser
    {
        return $this->functions[$name] ?? new FunctionParser();
    }

    public function parse() : string 
    {
        $class = '';

        if (!empty($this->requires)) {
            foreach ($this->requires as $require) {
                $class .= 'require_once "' . $require . '";' . PHP_EOL;
            }

            $class .= PHP_EOL;
        }

        if (!empty($this->includes)) {
            foreach ($this->includes as $include) {
                $class .= 'include_once "' . $include . '";' . PHP_EOL;
            }

            $class .= PHP_EOL;
        }

        if (isset($namespace)) {
            $class = $namespace . ';' . PHP_EOL . PHP_EOL;
        }

        if (!empty($this->use)) {
            foreach ($this->use as $as => $use) {
                $class .= 'use ' . $use . (is_string($as) ? ' as ' . $as : '') . ';' . PHP_EOL;
            }

            $class .= PHP_EOL;
        }

        if ($this->isfinal) {
            $class .= 'final ';
        }

        if ($this->isAbstract) {
            $class .= 'abstract ';
        }

        $class .= $this->type . ' ' . $this->name . ' ';

        if (isset($this->extends)) {
            $class .= 'extends ' . $this->extends . ' ';
        }

        if (!empty($this->implements)) {
            $class .= 'implements ' . implode(', ', $this->implements) . PHP_EOL;
        }

        $class .= '{' . PHP_EOL . PHP_EOL;

        if (!empty($this->traits)) {
            foreach ($this->traits as $as => $trait) {
                $class .= 'use ' . $trait . ';' . PHP_EOL;
            }

            $class .= PHP_EOL;
        }

        foreach ($this->members as $name => $member) {
            $class .= $member->parse() . PHP_EOL . PHP_EOL;
        }

        foreach ($this->functions as $name => $function) {
            $class .= $function->parse() . PHP_EOL . PHP_EOL;
        }

        $class .= '}';

        return $class;
    }
}