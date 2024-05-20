<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\Contract\SerializableInterface;
use phpOMS\DataStorage\Database\Query\ColumnName;
use phpOMS\DataStorage\Database\Query\Parameter;

/**
 * Grammar.
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 2.2
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
     * Compile post queries.
     *
     * These are queries, which should be run after the main query (e.g. table alters, trigger definitions etc.)
     *
     * @param BuilderAbstract $query Builder
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function compilePostQueries(BuilderAbstract $query) : array
    {
        return [];
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
    abstract public function compileComponents(BuilderAbstract $query) : array;

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
     * @param array           $elements Elements
     * @param BuilderAbstract $query    Builder
     * @param bool            $column   Is column?
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function expressionizeTableColumn(array $elements, ?BuilderAbstract $query = null, bool $column = true) : string
    {
        $expression = '';

        foreach ($elements as $key => $element) {
            if (\is_string($element)) {
                $expression .= $this->compileSystem($element) . (\is_string($key) ? ' AS ' . $key : '') . ', ';
            } elseif (\is_int($element)) {
                // example: select 1
                $expression .= $element . ', ';
            } elseif ($element instanceof BuilderAbstract) {
                $expression .= $element->toSql() . (\is_string($key) ? ' AS ' . $key : '') . ', ';

                if ($query->usePreparedStmt && $element->usePreparedStmt && $query !== null) {
                    // If we have a subquery, we need to copy the binds over
                    foreach ($element->binds as $bind) {
                        $query->bind($bind);
                    }
                }
            } elseif ($element instanceof \Closure) {
                $expression .= $element() . (\is_string($key) ? ' AS ' . $key : '') . ', ';
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

        // The order of this if/elseif statement is important!!!
        if ($system === '*'
            || \stripos($system, '(') !== false
            || \is_numeric($system)
        ) {
            $identifierStart = '';
            $identifierEnd   = '';
        } elseif ((\stripos($system, '.')) !== false) {
            // This is actually slower than \explode(), despite knowing the first index
            // $split = [\substr($system, 0, $pos), \substr($system, $pos + 1)];

            // Faster! But might require more memory?
            $split = \explode('.', $system);

            $identifierTwoStart = $identifierStart;
            $identifierTwoEnd   = $identifierEnd;

            if ($split[1] === '*') {
                $identifierTwoStart = '';
                $identifierTwoEnd   = '';
            }

            return $identifierStart . $split[0] . $identifierEnd
                . '.'
                . $identifierTwoStart . $split[1] . $identifierTwoEnd;
        }

        return $identifierStart . $system . $identifierEnd;
    }

    /**
     * Compile value.
     *
     * If the query builder uses prepared statements it will use these prepared statements + data binds.
     * If the query builder doesn't use prepared statements but the setting usePreparedStmt is set to true
     * this function will try and convert every value that can be prepared to a prepared statement
     *
     * @param BuilderAbstract $query Query builder
     * @param mixed           $value Value
     *
     * @return string returns a string representation of the value
     *
     * @throws \InvalidArgumentException throws this exception if the value to compile is not supported by this function
     *
     * @since 1.0.0
     */
    protected function compileValue(BuilderAbstract $query, mixed $value) : string
    {
        $compiled = '';
        $type     = -1;

        if (\is_string($value)) {
            if ($query->usePreparedStmt) {
                $type     = \PDO::PARAM_STR;
                $compiled = $value;
            } else {
                $compiled = $query->quote($value);
            }
        } elseif (\is_int($value)) {
            if ($query->usePreparedStmt) {
                $type     = \PDO::PARAM_INT;
                $compiled = $value;
            } else {
                $compiled = (string) $value;
            }
        } elseif (\is_array($value)) {
            $value  = \array_values($value);
            $count  = \count($value) - 1;
            $values = '(';

            for ($i = 0; $i < $count; ++$i) {
                $values .= $this->compileValue($query, $value[$i]) . ', ';
            }

            $compiled = $values . $this->compileValue($query, $value[$count]) . ')';
        } elseif ($value instanceof \DateTimeInterface) {
            if ($query->usePreparedStmt) {
                $type     = \PDO::PARAM_STR;
                $compiled = $value->format($this->datetimeFormat);
            } else {
                $compiled = $query->quote($value->format($this->datetimeFormat));
            }
        } elseif ($value === null) {
                $compiled = 'NULL';
        } elseif (\is_bool($value)) {
            if ($query->usePreparedStmt) {
                $type     = \PDO::PARAM_BOOL;
                $compiled = $value;
            } else {
                $compiled = (string) ((int) $value);
            }
        } elseif (\is_float($value)) {
            $compiled = \rtrim(\rtrim(\number_format($value, 5, '.', ''), '0'), '.');
        } elseif ($value instanceof ColumnName) {
            $compiled = $this->compileSystem($value->name);
        } elseif ($value instanceof BuilderAbstract) {
            $compiled = '(' . \rtrim($value->toSql(), ';') . ')';
            if ($query->usePreparedStmt && $value->usePreparedStmt) {
                $type = -2;
            }
        } elseif ($value instanceof \JsonSerializable) {
            $encoded = \json_encode($value);

            if ($query->usePreparedStmt) {
                $type     = $encoded ? \PDO::PARAM_STR : \PDO::PARAM_STR;
                $compiled = $encoded ? $value : null;
            } else {
                $compiled = $encoded ? $query->quote($encoded) : 'NULL';
            }
        } elseif ($value instanceof SerializableInterface) {
            $compiled = $value->serialize();
        } elseif ($value instanceof Parameter) {
            $compiled = $value->__toString();
        } else {
            throw new \InvalidArgumentException(\gettype($value));
        }

        if ($query->usePreparedStmt && $type !== -1) {
            if ($type === -2) {
                // If we have a subquery, we need to copy the binds over
                foreach ($value->binds as $bind) {
                    $query->bind($bind);
                }
            } else {
                $query->bind([
                    'value' => $compiled,
                    'type'  => $type,
                ]);

                $compiled = '?';
            }
        }

        return $compiled;
    }
}
