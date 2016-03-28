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
 * Member parser class.
 *
 * Parsing/serializing variables
 *
 * @category   System
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class FunctionParser
{
    const INDENT = 4;

    private $name = '';

    private $visibility = Visibility::_PUBLIC;

    private $isStatic = false;

    private $isAbstract = false;

    private $isFinal = false;

    private $return = null;

    private $parameters = [];

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->string;
    }

    public function setVisibility(string $visibility) 
    {
        $this->visibility = $visibility;
    }

    public function getVisibility() : string
    {
        return $this->visibility;
    }

    public function setStatic(bool $static) {
        $this->isStatic = $static;
    }

    public function isStatic() : bool 
    {
        return $this->isStatic;
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

    public function setReturn(string $return) 
    {
        $this->return = $return;
    }

    public function removeReturn() 
    {
        $this->return = null;
    }

    public function getReturn()
    {
        return $this->return;
    }

    public function parse() : string
    {
        $function = '';
        $member .= str_repeat(' ', self::INDENT);

        if($this->isFinal) {
            $member .= 'final ';
        }

        if($this->isAbstract) {
            $member .= 'abstract ';
        }

        $member .= $this->visibility . ' ';

        if($this->isStatic) {
            $member .= 'static ';
        }

        $member .= 'fucntion ' . $this->name . '(';

        $parameters = '';
        foreach($this->parameters as $name => $para) {
            $parameters = (isset($para['typehint'])  ? $para['typehint'] . ' ': '') . $para['name'] . (array_key_exists('default', $para) ? ' = ' . MemberParser::parseVariable($para['default']) : '') . ', ';
        }

        $member .= rtrim($parameters, ', ') . ') ';
        $member .= ($this->return ?? '') . PHP_EOL;
        $member .= '{' . PHP_EOL . $this->body . PHP_EOL . '}';

        return $member;
    }
}