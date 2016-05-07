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
class FunctionParser
{
    /**
     * Function name.
     *
     * @var string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Function visibility.
     *
     * @var string
     * @since 1.0.0
     */
    private $visibility = Visibility::_PUBLIC;

    /**
     * Is static?
     *
     * @var bool
     * @since 1.0.0
     */
    private $isStatic = false;

    /**
     * Is abstract?
     *
     * @var bool
     * @since 1.0.0
     */
    private $isAbstract = false;

    /**
     * Is final?
     *
     * @var bool
     * @since 1.0.0
     */
    private $isFinal = false;

    /**
     * Return type.
     *
     * @var string
     * @since 1.0.0
     */
    private $return = null;

    /**
     * Parameters.
     *
     * @var array
     * @since 1.0.0
     */
    private $parameters = [];

    /**
     * Function body.
     *
     * @var string
     * @since 1.0.0
     */
    private $body = '';

    /**
     * Set function name.
     *
     * @param string $name Function name
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get function name.
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
     * Set function body.
     *
     * @param string $body Function body
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function seBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * Get function body.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * Remove body.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeBody()
    {
        $this->body = '';
    }

    /**
     * Set visibility.
     *
     * @param string $visibility Function visibility
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setVisibility(string $visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * Get function visibility.
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
     * Set static.
     *
     * @param bool $static Is static
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setStatic(bool $static)
    {
        $this->isStatic = $static;
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
     * Set final.
     *
     * @param bool $final Is final
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setFinal(bool $final)
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
    public function setAbstract(bool $abstract)
    {
        $this->isAbstract = $abstract;

        if ($this->isAbstract) {
            $this->body = null;
        } elseif (!$this->isAbstract && !isset($this->body)) {
            $this->body = '';
        }
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
     * Set return type.
     *
     * @param string $return Return type
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setReturn(string $return)
    {
        $this->return = $return;
    }

    /**
     * Remove return type.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeReturn()
    {
        $this->return = null;
    }

    /**
     * Get return type.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getReturn() : string
    {
        return $this->return;
    }

    /**
     * Add parameter to function.
     *
     * @param string $name     Parameter name
     * @param string $typehint Typehint
     * @param string $default  Default value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addParameter(string $name, string $typehint = null, string $default = null)
    {
        $this->parameters[$name]['name']     = $name;
        $this->parameters[$name]['typehint'] = $typehint;

        if (isset($default)) {
            if ($default === 'null') {
                $default = null;
            }

            $this->parameters[$name]['default'] = $default;
        }
    }

    /**
     * Serialize function.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function serialize()
    {
        $function = '';
        $function .= str_repeat(' ', ClassParser::INDENT);

        if ($this->isFinal) {
            $function .= 'final ';
        }

        if ($this->isAbstract) {
            $function .= 'abstract ';
        }

        $function .= $this->visibility . ' ';

        if ($this->isStatic) {
            $function .= 'static ';
        }

        $function .= 'function ' . $this->name . '(';

        $parameters = '';
        foreach ($this->parameters as $name => $para) {
            $parameters = (isset($para['typehint']) ? $para['typehint'] . ' ' : '') . $para['name'] . (array_key_exists('default', $para) ? ' = ' . MemberParser::parseVariable($para['default']) : '') . ', ';
        }

        $function .= rtrim($parameters, ', ') . ') ';
        $function .= ($this->return ?? '') . PHP_EOL;

        if (isset($this->body)) {
            $function .= str_repeat(' ', ClassParser::INDENT) . '{' . PHP_EOL . $this->addIndent($this->body) . PHP_EOL . str_repeat(' ', ClassParser::INDENT) . '}';
        } else {
            $function .= ';';
        }

        return $function;
    }

    /**
     * Add indention for body.
     *
     * @param string $body Function body to indent
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function addIndent(string $body) : string
    {
        $body = preg_split('/\r\n|\r|\n/', $body);

        foreach ($body as &$line) {
            $line = str_repeat(' ', ClassParser::INDENT) . $line;
        }

        return $body;
    }
}