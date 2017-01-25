<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Utils\Parser\Php;

/**
 * Class parser class.
 *
 * Parsing/serializing classes, interfaces to and from php file
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
    /**
     * Indention.
     *
     * @var int
     * @since 1.0.0
     */
    /* public */ const INDENT = 4;

    /**
     * Is final?
     *
     * @var bool
     * @since 1.0.0
     */
    private $isFinal = false;

    /**
     * Is abstract?
     *
     * @var bool
     * @since 1.0.0
     */
    private $isAbstract = false;

    /**
     * Type.
     *
     * @var string
     * @since 1.0.0
     */
    private $type = ClassType::_CLASS;

    /**
     * Extends.
     *
     * @var string
     * @since 1.0.0
     */
    private $extends = '';

    /**
     * Namespace.
     *
     * @var null|string
     * @since 1.0.0
     */
    private $namespace = '';

    /**
     * Includes.
     *
     * @var array
     * @since 1.0.0
     */
    private $includes = [];

    /**
     * Requires.
     *
     * @var array
     * @since 1.0.0
     */
    private $requires = [];

    /**
     * Uses.
     *
     * @var array
     * @since 1.0.0
     */
    private $use = [];

    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Implements.
     *
     * @var array
     * @since 1.0.0
     */
    private $implements = [];

    /**
     * Traits.
     *
     * @var array
     * @since 1.0.0
     */
    private $traits = [];

    /**
     * Members.
     *
     * @var MemberParser[]
     * @since 1.0.0
     */
    private $members = [];

    /**
     * Functions.
     *
     * @var FunctionParser[]
     * @since 1.0.0
     */
    private $functions = [];

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
    public function createFile(string $path) /* : void */
    {
        // todo: implement
    }

    /**
     * Set final.
     *
     * @param bool $final Is final
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setFinal(bool $final) /* : void */
    {
        $this->isFinal = $final;
    }

    /**
     * Is final?
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function isFinal() : bool
    {
        return $this->isFinal;
    }

    /**
     * Set abstract.
     *
     * @param bool $abstract Is abstract
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setAbstract(bool $abstract) /* : void */
    {
        $this->isAbstract = $abstract;
    }

    /**
     * Is abstract?
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function isAbstract() : bool
    {
        return $this->isAbstract;
    }

    /**
     * Get type.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * Available types are ClassType::
     *
     * @param string $type Set type
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setType(string $type) /* : void */
    {
        $this->type = $type;
    }

    /**
     * Get extends.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getExtends() : string
    {
        return $this->extends;
    }

    /**
     * Set extends.
     *
     * @param string $extends Extended class
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setExtends(string $extends) /* : void */
    {
        $this->extends = $extends;
    }

    /**
     * Remove extends.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeExtends() /* : void */
    {
        $this->extends = '';
    }

    /**
     * Get namespace.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getNamespace() : string
    {
        return $this->namespace;
    }

    /**
     * Set namespace.
     *
     * @param string $namespace Namespace
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setNamespace(string $namespace) /* : void */
    {
        $this->namespace = $namespace;
    }

    /**
     * Remove namespace.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeNamespace() /* : void */
    {
        $this->namespace = '';
    }

    /**
     * Add use.
     *
     * @param string $namespace Namespace to use
     * @param string $as        Namespace as
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addUse(string $namespace, string $as = null) /* : void */
    {
        if (isset($as)) {
            $this->use[$as] = $namespace;
        } else {
            $this->use[] = $namespace;
        }
    }

    /**
     * Remove use.
     *
     * @param string $id Namespace numerical id or 'as' if used.
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeUse($id) : bool
    {
        if (isset($this->use[$id])) {
            unset($this->use[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get name.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name Class name
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setName(string $name) /* : void */
    {
        $this->name = $name;
    }

    /**
     * Add implements.
     *
     * @param string $implements Implement
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addImplements(string $implements) /* : void */
    {
        $this->implements[] = $implements;

        array_unique($this->implements);
    }

    /**
     * Add include.
     *
     * @param string $include Include
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addInclude(string $include) /* : void */
    {
        $this->includes[] = $include;

        array_unique($this->includes);
    }

    /**
     * Add $require.
     *
     * @param string $require Require
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addRequire(string $require) /* : void */
    {
        $this->requires[] = $require;

        array_unique($this->requires);
    }

    /**
     * Add trait.
     *
     * @param string $trait Trait to use
     * @param string $as    Trait as
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addTrait(string $trait, string $as = null) /* : void */
    {
        if (isset($as)) {
            $this->traits[$as] = $trait;
        } else {
            $this->traits[] = $trait;
        }
    }

    /**
     * Remove trait.
     *
     * @param string $id Namespace numerical id or 'as' if used.
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeTrait($id) : bool
    {
        if (isset($this->traits[$id])) {
            unset($this->traits[$id]);

            return true;
        }

        return false;
    }

    /**
     * Add member.
     *
     * @param MemberParser $member Member
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addMember(MemberParser $member) /* : void */
    {
        $this->members[$member->getName()] = $member;
    }

    /**
     * Remove member by name.
     *
     * @param string $name Member name
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeMember(string $name) : bool
    {
        if (isset($this->members[$name])) {
            unset($this->members[$name]);

            return true;
        }

        return false;
    }

    /**
     * Get member by name.
     *
     * @param string $name Member name
     *
     * @return MemberParser
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getMember(string $name) : MemberParser
    {
        return $this->members[$name] ?? new MemberParser();
    }

    /**
     * Add function.
     *
     * @param FunctionParser $function Function
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addFunction(FunctionParser $function)
    {
        $this->functions[$function->getName()] = $function;
    }

    /**
     * Remove function by name.
     *
     * @param string $name Function name
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeFunction(string $name) : bool
    {
        if (isset($this->functions[$name])) {
            unset($this->functions[$name]);

            return true;
        }

        return false;
    }

    /**
     * Get function by name.
     *
     * @param string $name Function name
     *
     * @return FunctionParser
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getFunction(string $name) : FunctionParser
    {
        return $this->functions[$name] ?? new FunctionParser();
    }

    /**
     * Serialize class.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function serialize() : string
    {
        $class = '';

        $class .= $this->serializeRequire('require_once', $this->requires);
        $class .= $this->serializeRequire('include_once', $this->includes);
        $class .= $this->serializeNamespace();
        $class .= $this->serializeUse($this->use);
        $class .= $this->serializeClass();
        $class .= '{' . PHP_EOL . PHP_EOL;
        $class .= $this->serializeUse($this->traits);

        foreach ($this->members as $name => $member) {
            $class .= $member->serialize() . PHP_EOL . PHP_EOL;
        }

        foreach ($this->functions as $name => $function) {
            $class .= $function->serialize() . PHP_EOL . PHP_EOL;
        }

        $class .= '}';

        return $class;
    }

    /**
     * Serialize require.
     *
     * @param string $keyword Keyword (e.g. include, require, include_once)
     * @param array  $source  Require source
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function serializeRequire(string $keyword, array $source) : string
    {
        $serialze = '';
        if (!empty($source)) {
            foreach ($source as $require) {
                $serialze .= $keyword . ' "' . $require . '";' . PHP_EOL;
            }

            $serialze .= PHP_EOL;
        }

        return $serialze;
    }

    /**
     * Serialize namespace.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function serializeNamespace() : string
    {
        $serialze = '';
        if (!empty($this->namespace)) {
            $serialze = $this->namespace . ';' . PHP_EOL . PHP_EOL;
        }

        return $serialze;
    }

    /**
     * Serialize use.
     *
     * @param array $source Use source
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function serializeUse(array $source) : string
    {
        $serialze = '';
        if (!empty($source)) {
            foreach ($source as $as => $use) {
                $serialze .= 'use ' . $use . (is_string($as) ? ' as ' . $as : '') . ';' . PHP_EOL;
            }

            $serialze .= PHP_EOL;
        }

        return $serialze;
    }

    /**
     * Serialize class.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function serializeClass() : string
    {
        $serialze = '';
        if ($this->isFinal) {
            $serialze .= 'final ';
        }

        if ($this->isAbstract) {
            $serialze .= 'abstract ';
        }

        $serialze .= $this->type . ' ' . $this->name . ' ';

        if (!empty($this->extends)) {
            $serialze .= 'extends ' . $this->extends . ' ';
        }

        if (!empty($this->implements)) {
            $serialze .= 'implements ' . implode(', ', $this->implements) . PHP_EOL;
        }

        return $serialze;
    }

}