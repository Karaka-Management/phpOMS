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
 * @category   Framework
 * @package    phpOMS\Utils\Parser
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MemberParser
{
    private $name = '';

    private $visibility = Visibility::_PUBLIC;

    private $isStatic = false;

    private $isConst = false;

    private $default = null;

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
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

        if ($this->isStatic) {
            $this->isConst = false;
        }
    }

    public function isStatic() : bool 
    {
        return $this->isStatic;
    }

    public function setConst(bool $const) 
    {
        $this->isConst = $const;

        if ($this->isConst) {
            $this->isStatic = false;
        }
    }

    public function setDefault($default) {
        $this->default = $default;
    }

    public function parse() : string
    {
        $member = '';
        $member .= str_repeat(' ', ClassParser::INDENT);

        $member .= $this->visibility . ' ';

        if ($this->isStatic) {
            $member .= 'static ';
        }

        if ($this->isConst) {
            $member .= 'const ';
        }

        $member .= (!$this->isConst ? '$' : '') . $this->name . ' = ' . self::parseVariable($this->default) . ';';

        return $member;
    }

    /**
     * Serialize value.
     *
     * @param mixed $value Value to serialzie
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function parseVariable($value) : string
    {
        if (is_array($value)) {
            return ArrayParser::serializeArray($value) . PHP_EOL;
        } elseif (is_string($value)) {
            return '"' . $value . '"';
        } elseif (is_scalar($value)) {
            return $value;
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif ($value instanceOf \Serializable) {
            return self::parseVariable($value->serialize());
        } else {
            throw new \UnexpectedValueException();
        }
    }
}