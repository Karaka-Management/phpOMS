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
namespace phpOMS\Config;


use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Settings class.
 *
 * Responsible for providing a database/cache bound settings manger
 *
 * @category   Framework
 * @package    phpOMS\Config
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class SettingsAbstract implements OptionsInterface
{
    use OptionsTrait;

    /**
     * Cache manager (pool).
     *
     * @var \phpOMS\DataStorage\Cache\CacheManager
     * @since 1.0.0
     */
    protected $cache = null;

    /**
     * Database connection instance.
     *
     * @var \phpOMS\DataStorage\Database\Connection\ConnectionAbstract
     * @since 1.0.0
     */
    protected $connection = null;

    /**
     * Settings table.
     *
     * @var \string
     * @since 1.0.0
     */
    protected static $table = null;

    /**
     * Columns to identify the value.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected static $columns = [
        'id',
    ];

    /**
     * Field where the actual value is stored.
     *
     * @var \string
     * @since 1.0.0
     */
    protected $valueField = 'option';

    /**
     * Get option by key.
     *
     * @param \string[] $columns Column values for filtering
     *
     * @return mixed Option value
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function get(array $columns)
    {
        $options = [];

        switch ($this->connection->getType()) {
            case DatabaseType::MYSQL:
                $query = new Builder($this->connection);
                $sql   = $query->select(...static::$columns)
                    ->from($this->connection->prefix . static::$table)
                    ->where(static::$columns[0], 'in', $columns)
                    ->toSql();

                $sth = $this->connection->con->prepare($sql);
                $sth->execute();

                $options = $sth->fetchAll(\PDO::FETCH_KEY_PAIR);
                $this->setOptions($options);
                break;
        }

        return $options;
    }

    /**
     * Set option by key.
     *
     * @param \string[] $options   Column values for filtering
     * @param \bool     $store     Save this Setting immediately to database
     *
     * @return mixed Option value
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set(array $options, \bool $store = false)
    {
        $this->setOptions($options);

        if($store) {
            // save to db
        }
    }
}
