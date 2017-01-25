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
namespace phpOMS\Utils\Parser\Php;

/**
 * Member parser class.
 *
 * Parsing/serializing member variables
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
    /**
     * Member name.
     *
     * @var string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Member visibility.
     *
     * @var string
     * @since 1.0.0
     */
    private $visibility = Visibility::_PUBLIC;

    /**
     * Is static.
     *
     * @var bool
     * @since 1.0.0
     */
    private $isStatic = false;

    /**
     * Is const.
     *
     * @var bool
     * @since 1.0.0
     */
    private $isConst = false;

    /**
     * Default value.
     *
     * @var mixed
     * @since 1.0.0
     */
    private $default = null;

    /**
     * Get member name.
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
     * Set member name.
     *
     * @param string $name Member name
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
     * Get visibility.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getVisibility() : string
    {
        return $this->visibility;
    }

    /**
     * Set visibility.
     *
     * @param string $visibility Member visibility
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setVisibility(string $visibility) /* : void */
    {
        $this->visibility = $visibility;
    }

    /**
     * Set static.
     *
     * @param bool $static Is static
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setStatic(bool $static) /* : void */
    {
        $this->isStatic = $static;

        if ($this->isStatic) {
            $this->isConst = false;
        }
    }

    /**
     * Is static?
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function isStatic() : bool
    {
        return $this->isStatic;
    }

    /**
     * Set const.
     *
     * @param bool $const Is const
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setConst(bool $const) /* : void */
    {
        $this->isConst = $const;

        if ($this->isConst) {
            $this->isStatic = false;
        }
    }

    /**
     * Is const?
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function isConst() : bool
    {
        return $this->isConst;
    }

    /**
     * Set default value.
     *
     * @param string $default
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDefault($default) /* : void */
    {
        $this->default = $default;
    }

    /**
     * Serialize member.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function serialize() : string
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