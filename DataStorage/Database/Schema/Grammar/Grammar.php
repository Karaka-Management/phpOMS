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

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\GrammarAbstract;
use phpOMS\DataStorage\Database\Schema\Builder;
use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * Database query grammar.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Grammar extends GrammarAbstract
{
    /**
     * Select components.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected $selectComponents = [
        'selects',
        'from',
    ];

    /**
     * Select components.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected $dropComponents = [
        'drop',
    ];

    public function compileComponents(BuilderAbstract $query) : array
    {
        $sql = [];

        switch ($query->getType()) {
            case QueryType::DROP:
                $components = $this->dropComponents;
                break;
            default:
                throw new \InvalidArgumentException('Unknown query type.');
        }

        /* Loop all possible query components and if they exist compile them. */
        foreach ($components as $component) {
            if (isset($query->{$component}) && !empty($query->{$component})) {
                $sql[$component] = $this->{'compile' . ucfirst($component)}($query, $query->{$component});
            }
        }

        return $sql;
    }

    protected function compileDrop(Builder $query, array $tables) : \string
    {
        $expression = $this->expressionizeTableColumn($tables, $query->getPrefix());

        if ($expression === '') {
            $expression = '*';
        }

        return 'DROP TABLE ' . $expression;
    }
}
