<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Query\QueryType;

/**
 * Grammar.
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class GrammarAbstract
{
    /**
     * Comment style.
     *
     * @var string
     * @since 1.0.0
     */
    public string $comment = '--';

    /**
     * String quotes style.
     *
     * @var string
     * @since 1.0.0
     */
    public string $valueQuotes = '\'';

    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    public string $systemIdentifierStart = '"';

    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    public string $systemIdentifierEnd = '"';

    /**
     * And operator.
     *
     * @var string
     * @since 1.0.0
     */
    public string $and = 'AND';

    /**
     * Or operator.
     *
     * @var string
     * @since 1.0.0
     */
    public string $or = 'OR';

    /**
     * Special keywords.
     *
     * @var string[]
     * @since 1.0.0
     */
    public array $specialKeywords = [
        'COUNT(',
        'MAX(',
        'MIN(',
        'SUM(',
        'DATE(',
        'YEAR(',
        'MONTH(',
    ];

    /**
     * Datetime format.
     *
     * @var string
     * @since 1.0.0
     */
    public string $datetimeFormat = 'Y-m-d H:i:s';

    /**
     * Set the datetime format
     *
     * @param string $format Datetime format
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDateTimeFormat(string $format) : void
    {
        $this->datetimeFormat = $format;
    }

    /**
     * Compile to query.
     *
     * @param BuilderAbstract $query Builder
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function compileQuery(BuilderAbstract $query) : string
    {
        $components  = $this->compileComponents($query);
        $queryString = '';

        foreach ($components as $component) {
            if ($component !== '') {
                $queryString .= $component . ' ';
            }
        }

        return \substr($queryString, 0, -1) . ';';
    }

    /**
     * Compile components.
     *
     * @param BuilderAbstract $query Builder
     *
     * @return string[]
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    protected function compileComponents(BuilderAbstract $query) : array
    {
        if ($query->getType() === QueryType::RAW) {
            return [$query->raw];
        }

        $sql        = [];
        $components = $this->getComponents($query->getType());

        /* Loop all possible query components and if they exist compile them. */
        foreach ($components as $component) {
            if (isset($query->{$component}) && !empty($query->{$component})) {
                $sql[$component] = $this->{'compile' . \ucfirst($component)}($query, $query->{$component});
            }
        }

        return $sql;
    }

    /**
     * Get query components based on query type.
     *
     * @param int $type Query type
     *
     * @return array Array of components to build query
     *
     * @throws \InvalidArgumentException Throws this exception if the query type is undefined
     *
     * @since 1.0.0
     */
    abstract protected function getComponents(int $type) : array;

    /**
     * Get date format.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDateFormat() : string
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * Expressionize elements.
     *
     * @param array $elements Elements
     * @param bool  $column   Is column?
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function expressionizeTableColumn(array $elements, bool $column = true) : string
    {
        $expression = '';

        foreach ($elements as $key => $element) {
            if (\is_string($element) && $element !== '*') {
                $expression .= $this->compileSystem($element) . (\is_string($key) ? ' as ' . $key : '') . ', ';
            } elseif ($element === '*') {
                $expression .= '*, ';
            } elseif ($element instanceof \Closure) {
                $expression .= $element() . (\is_string($key) ? ' as ' . $key : '') . ', ';
            } elseif ($element instanceof BuilderAbstract) {
                $expression .= $element->toSql() . (\is_string($key) ? ' as ' . $key : '') . ', ';
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return \rtrim($expression, ', ');
    }

    /**
     * Compile system.
     *
     * A system is a table, a sub query or special keyword.
     *
     * @param string $system System
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileSystem(string $system) : string
    {
        $identifierStart = $this->systemIdentifierStart;
        $identifierEnd   = $this->systemIdentifierEnd;

        foreach ($this->specialKeywords as $keyword) {
            if (\strrpos($system, $keyword, -\strlen($system)) !== false) {
                $identifierStart = '';
                $identifierEnd   = '';

                break;
            }
        }

        // The following code could have been handled with \explode more elegantly but \explode needs more memory and more time
        // Normally this wouldn't be a problem but in this case there are so many function calls to this routine,
        // that it makes sense to make this "minor" improvement.
        if (($pos = \stripos($system, '.')) !== false) {
            $split = [\substr($system, 0, $pos), \substr($system, $pos + 1)];

            return ($split[0] !== '*' ? $identifierStart : '')
                . $split[0]
                . ($split[0] !== '*' ? $identifierEnd : '')
                . '.'
                . ($split[1] !== '*' ? $identifierStart : '')
                . $split[1]
                . ($split[1] !== '*' ? $identifierEnd : '');
        }

        return ($system !== '*' ? $identifierStart : '')
            . $system
            . ($system !== '*' ? $identifierEnd : '');
    }
}
