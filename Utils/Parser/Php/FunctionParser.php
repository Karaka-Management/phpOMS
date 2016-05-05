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
    private $name = '';

    private $visibility = Visibility::_PUBLIC;

    private $isStatic = false;

    private $isAbstract = false;

    private $isFinal = false;

    private $return = null;

    private $parameters = [];

    private $body = '';

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function seBody(string $body)
    {
        $this->body = $body;
    }

    public function getBody() : string
    {
        return $this->body;
    }

    public function removeBody()
    {
        $this->body = null;
    }

    public function setVisibility(string $visibility)
    {
        $this->visibility = $visibility;
    }

    public function getVisibility() : string
    {
        return $this->visibility;
    }

    public function setStatic(bool $static)
    {
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

        if ($this->isAbstract) {
            $this->body = null;
        } elseif (!$this->isAbstract && !isset($this->body)) {
            $this->body = '';
        }
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

    public function addParameter(string $name, string $typehint, $default = null)
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

    public function parse() : string
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

    private function addIndent(string $body) : string
    {
        $body = preg_split('/\r\n|\r|\n/', $body);

        foreach ($body as &$line) {
            $line = str_repeat(' ', ClassParser::INDENT) . $line;
        }

        return $body;
    }
}