<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Schema\Grammar
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Query\Grammar\Grammar as QueryGrammar;
use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * Database query grammar.
 *
 * @package    phpOMS\DataStorage\Database\Schema\Grammar
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Grammar extends QueryGrammar
{
    /**
     * Select components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $dropComponents = [
        'drop',
    ];

    /**
     * Get query components based on query type.
     *
     * @param int $type Query type
     *
     * @return array Array of components to build query
     *
     * @since  1.0.0
     */
    private function getComponents(int $type) : array
    {
        switch ($type) {
            case QueryType::DROP:
                return $this->dropComponents;
            default:
                return parent::getComponents($type);
        }
    }

    /**
     * Compile drop query.
     *
     * @param BuilderAbstract $query  Query
     * @param array           $tables Tables to drop
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileDrop(BuilderAbstract $query, array $tables) : string
    {
        $expression = $this->expressionizeTableColumn($tables, $query->getPrefix());

        if ($expression === '') {
            $expression = '*';
        }

        return 'DROP TABLE ' . $expression;
    }
}
