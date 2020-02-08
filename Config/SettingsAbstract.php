<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Config
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Config;

use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Settings class.
 *
 * Responsible for providing a database/cache bound settings manger
 *
 * @package phpOMS\Config
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class SettingsAbstract implements OptionsInterface
{
    use OptionsTrait;

    /**
     * Cache manager (pool).
     *
     * @var null|CachePool
     * @since 1.0.0
     */
    protected ?CachePool $cache = null;

    /**
     * Database connection instance.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected ConnectionAbstract $connection;

    /**
     * Settings table.
     *
     * @var null|string
     * @since 1.0.0
     */
    protected static ?string $table = null;

    /**
     * Columns to identify the value.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $columns = [
        'id',
    ];

    /**
     * Field where the actual value is stored.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $valueField = 'option';

    /**
     * Get option.
     *
     * Possible usage:
     *      - Use column key
     *      - Use combination of module, group, account and name without column key
     *
     * @param null|int|int[]|string|string[] $columns Column values for filtering
     * @param null|string                    $name    Setting name @todo consider to make this an integer?!
     * @param null|string                    $module  Module name
     * @param null|int                       $group   Group id
     * @param null|int                       $account Account id
     *
     * @return mixed Option value
     *
     * @since 1.0.0
     */
    public function get(
        $columns = null,
        string $name = null,
        string $module = null,
        int $group = null,
        int $account = null
    ) {
        $options = [];
        $keys    = [];

        if ($columns === null) {
            $key = ($name ?? '') . ':' . ($module ?? '') . ':' . ($group ?? '') . ':' . ($account ?? '');
            if ($this->exists($key)) {
                $options[$key] = $this->getOption($key);

                return \count($options) > 1 ? $options : \reset($options);
            }
        } else {
            if (!\is_array($columns)) {
                $keys = [$columns];
            } else {
                $keys = [];
                foreach ($columns as $key) {
                    $keys[] = \is_string($key) ? (int) \preg_replace('/[^0-9.]/', '', $key) : $key;
                }
            }

            foreach ($keys as $key) {
                if ($this->exists($key)) {
                    $options[$key] = $this->getOption($key);
                    unset($keys[$key]);
                }
            }

            if (empty($keys)) {
                return \count($options) > 1 ? $options : \reset($options);
            }
        }

        try {
            $dbOptions = [];
            $query     = new Builder($this->connection);
            $query->select(...static::$columns)
                ->from($this->connection->prefix . static::$table);

            if (!empty($columns)) {
                $query->where(static::$columns[0], 'in', $keys);
            } else {
                if ($name !== null) {
                    $query->where(static::$columns['name'], '=', $name);
                }

                if ($module !== null) {
                    $query->andWhere(static::$columns['module'], '=', $module);
                }

                if ($group !== null) {
                    $query->andWhere(static::$columns['group'], '=', $group);
                }

                if ($account !== null) {
                    $query->andWhere(static::$columns['account'], '=', $account);
                }
            }

            $sql = $query->toSql();

            $sth = $this->connection->con->prepare($sql);
            $sth->execute();

            $dbOptions = $sth->fetchAll(\PDO::FETCH_KEY_PAIR);
            $options  += $dbOptions === false ? [] : $dbOptions;

            if ($dbOptions === false) {
                return \count($options) > 1 ? $options : \reset($options); // @codeCoverageIgnore
            }

            $this->setOptions($dbOptions);
        } catch (\Throwable $e) {
            throw $e;
        }

        return \count($options) > 1 ? $options : \reset($options);
    }

    /**
     * Set option by key.
     *
     * @param string[] $options Column values for filtering
     * @param bool     $store   Save this Setting immediately to database
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function set(array $options, bool $store = false) : void
    {
        $this->setOptions($options);

        if ($store) {
            $this->connection->con->beginTransaction();

            foreach ($options as $key => $option) {
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

    /**
     * Save options.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function save() : void
    {
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
