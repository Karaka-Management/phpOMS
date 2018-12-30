<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Query\QueryType;

/**
 * Grammar.
 *
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class GrammarAbstract
{
    /**
     * Comment style.
     *
     * @var string
     * @since 1.0.0
     */
    protected $comment = '--';

    /**
     * String quotes style.
     *
     * @var string
     * @since 1.0.0
     */
    protected $valueQuotes = '\'';

    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    protected $systemIdentifier = '"';

    /**
     * And operator.
     *
     * @var string
     * @since 1.0.0
     */
    protected $and = 'AND';

    /**
     * Or operator.
     *
     * @var string
     * @since 1.0.0
     */
    protected $or = 'OR';

    /**
     * Table prefix.
     *
     * @var string
     * @since 1.0.0
     */
    protected $tablePrefix = '';

    /**
     * Special keywords.
     *
     * @var array
     * @since 1.0.0
     */
    protected $specialKeywords = [
        'COUNT('
    ];

    /**
     * Compile to query.
     *
     * @param BuilderAbstract $query Builder
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function compileQuery(BuilderAbstract $query) : string
    {
        return \trim(
            \implode(' ',
                \array_filter(
                    $this->compileComponents($query),
                    function ($value) {
                        return (string) $value !== '';
                    }
                )
            )
        ) . ';';
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
     * @since  1.0.0
     */
    protected function compileComponents(BuilderAbstract $query) : array
    {
        $sql = [];

        if ($query->getType() === QueryType::RAW) {
            return [$query->raw];
        }

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
     * @since  1.0.0
     */
    abstract protected function getComponents(int $type) : array;

    /**
     * Get date format.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getDateFormat() : string
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * Get table prefix.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getTablePrefix() : string
    {
        return $this->tablePrefix;
    }

    /**
     * Set table prefix.
     *
     * @param string $prefix Table prefix
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setTablePrefix(string $prefix) : void
    {
        $this->tablePrefix = $prefix;
    }

    /**
     * Expressionize elements.
     *
     * @param array  $elements Elements
     * @param string $prefix   Prefix for table
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function expressionizeTableColumn(array $elements, string $prefix = '') : string
    {
        $expression = '';

        foreach ($elements as $key => $element) {
            if (\is_string($element) && $element !== '*') {
                if (\strpos($element, '.') === false) {
                    $prefix = '';
                }

                $expression .= $this->compileSystem($element, $prefix) . ', ';
            } elseif (\is_string($element) && $element === '*') {
                $expression .= '*, ';
            } elseif ($element instanceof \Closure) {
                $expression .= $element() . ', ';
            } elseif ($element instanceof BuilderAbstract) {
                $expression .= $element->toSql() . ', ';
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return \rtrim($expression, ', ');
    }

    /**
     * Expressionize elements.
     *
     * @param array  $elements Elements
     * @param string $prefix   Prefix for table
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function expressionizeTable(array $elements, string $prefix = '') : string
    {
        $expression = '';

        foreach ($elements as $key => $element) {
            if (\is_string($element) && $element !== '*') {
                $expression .= $this->compileSystem($element, $prefix) . ', ';
            } elseif (\is_string($element) && $element === '*') {
                $expression .= '*, ';
            } elseif ($element instanceof \Closure) {
                $expression .= $element() . ', ';
            } elseif ($element instanceof BuilderAbstract) {
                $expression .= $element->toSql() . ', ';
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
     * @param string $prefix Prefix for table
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileSystem(string $system, string $prefix = '') : string
    {
        $identifier = $this->systemIdentifier;

        // todo: this is a bad way to handle select \count(*) which doesn't need a prefix. Maybe remove prefixes in total?
        foreach ($this->specialKeywords as $keyword) {
            if (\strrpos($system, $keyword, -\strlen($system)) !== false) {
                $prefix     = '';
                $identifier = '';

                break;
            }
        }

        $split      = \explode('.', $system);
        $fullSystem = '';

        foreach ($split as $key => $system) {
            $fullSystem .= '.' . $identifier . ($key === 0 ? $prefix : '') . $system . $identifier;
        }

        return \ltrim($fullSystem, '.');
    }
}
