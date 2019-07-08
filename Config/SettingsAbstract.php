<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Config
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Config;

use phpOMS\DataStorage\Database\DatabaseExceptionFactory;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Settings class.
 *
 * Responsible for providing a database/cache bound settings manger
 *
 * @package    phpOMS\Config
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class SettingsAbstract implements OptionsInterface
{
    use OptionsTrait;

    /**
     * Cache manager (pool).
     *
     * @var \phpOMS\DataStorage\Cache\CachePool
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
     * @var string
     * @since 1.0.0
     */
    protected static $table = null;

    /**
     * Columns to identify the value.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $columns = [
        'id',
    ];

    /**
     * Field where the actual value is stored.
     *
     * @var string
     * @since 1.0.0
     */
    protected $valueField = 'option';

    /**
     * Get option by key.
     *
     * @param int|int[]|string|string[] $columns Column values for filtering
     *
     * @return mixed Option value
     *
     * @since  1.0.0
     */
    public function get($columns)
    {
        try {
            if (!\is_array($columns)) {
                $keys = [$columns];
            } else {
                $keys = [];
                foreach ($columns as $key) {
                    $keys[] = \is_string($key) ? (int) \preg_replace('/[^0-9.]/', '', $key) : $key;
                }
            }

            $options = [];
            $query   = new Builder($this->connection);
            $sql     = $query->select(...static::$columns)
                ->from($this->connection->prefix . static::$table)
                ->where(static::$columns[0], 'in', $keys)
                ->toSql();

            $sth = $this->connection->con->prepare($sql);
            $sth->execute();

            $options = $sth->fetchAll(\PDO::FETCH_KEY_PAIR);

            if ($options === false) {
                return []; // @codeCoverageIgnore
            }

            $this->setOptions($options);

            return \count($options) > 1 ? $options : \reset($options);
        } catch (\PDOException $e) {
            // @codeCoverageIgnoreStart
            $exception = DatabaseExceptionFactory::createException($e);
            $message   = DatabaseExceptionFactory::createExceptionMessage($e);

            throw new $exception($message);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Set option by key.
     *
     * @param string[] $options Column values for filtering
     * @param bool     $store   Save this Setting immediately to database
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function set(array $options, bool $store = false) : void
    {
        $this->setOptions($options);

        if ($store) {
            $this->connection->con->beginTransaction();

            foreach ($this->options as $key => $option) {
                if (\is_string($key)) {
                    $key = (int) \preg_replace('/[^0-9.]/', '', $key);
                }

                $query = new Builder($this->connection);
                $sql   = $query->update($this->connection->prefix . static::$table)
                    ->set([static::$columns[1] => $option])
                    ->where(static::$columns[0], '=', $key)
                    ->toSql();

                $sth = $this->connection->con->prepare($sql);
                $sth->execute();
            }

            $this->connection->con->commit();
        }
    }
}
