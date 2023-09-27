<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\IO\Csv
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Csv;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\IO\IODatabaseMapper;

/**
 * Csv database mapper.
 *
 * @package phpOMS\Utils\IO\Csv
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class CsvDatabaseMapper implements IODatabaseMapper
{
    /**
     * Database connection
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    private ConnectionAbstract $con;

    /**
     * Path to source or destination
     *
     * @var string
     * @since 1.0.0
     */
    private string $path = '';

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $con  Database connection
     * @param string             $path File path
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $con, string $path)
    {
        $this->con  = $con;
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function insert() : void
    {
        $fp = \fopen($this->path, 'r');
        if ($fp === false) {
            return;
        }

        $table  = \basename($this->path, '.csv');
        $titles = [];

        // get column titles
        $titles = \fgetcsv($fp, 4096);
        if ($titles === false) {
            return;
        }

        $columns = \count($titles);
        if ($columns === 0) {
            return;
        }

        // insert data
        $query = new Builder($this->con);
        $query->insert(...$titles)->into($table);

        while (($cells = \fgetcsv($fp)) !== false) {
            $query->values(...$cells);
        }

        $query->execute();

        \fclose($fp);
    }

    /**
     * {@inheritdoc}
     */
    public function select(array $queries) : void
    {
        $fp = \fopen($this->path, 'r+');
        if ($fp === false) {
            return;
        }

        foreach ($queries as $key => $query) {
            $results = $query->execute()?->fetchAll(\PDO::FETCH_ASSOC);
            if (!\is_array($results)) {
                continue;
            }

            if ($key > 0) {
                return;
            }

            $rows = \count($results);
            if ($rows < 1) {
                break;
            }

            $colCount = \count($results[0]);
            $columns  = \array_keys($results[0]);

            // set column titles
            for ($i = 1; $i <= $colCount; ++$i) {
                \fputcsv($fp, $columns);
            }

            // set data
            foreach ($results as $result) {
                \fputcsv($fp, $result);
            }
        }

        \fclose($fp);
    }

    /**
     * {@inheritdoc}
     */
    public function update() : void
    {
        $fp = \fopen($this->path, 'r+');
        if ($fp === false) {
            return;
        }

        $table  = \basename($this->path, '.csv');
        $titles = [];

        // get column titles
        $titles = \fgetcsv($fp, 4096);
        if ($titles === false) {
            return;
        }

        $columns = \count($titles);
        if ($columns === 0) {
            return;
        }

        $idCol = (string) \array_shift($titles);

        // update data
        while (($cells = \fgetcsv($fp)) !== false) {
            $query = new Builder($this->con);
            $query->update($titles)->into($table);

            for ($j = 2; $j <= $columns; ++$j) {
                $query->sets((string) $titles[$j - 2], $cells[$j - 1]);
            }

            $query->where($idCol, '=', $cells[0]);
            $query->execute();
        }

        \fclose($fp);
    }
}
