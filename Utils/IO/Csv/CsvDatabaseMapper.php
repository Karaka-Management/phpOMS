<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;
use phpOMS\Utils\IO\IODatabaseMapper;

/**
 * Csv database mapper.
 *
 * @package phpOMS\Utils\IO\Csv
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CsvDatabaseMapper implements IODatabaseMapper
{
    /**
     * Database connection
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    private ConnectionAbstract $con;

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $con  Database connection
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $con)
    {
        $this->con = $con;
    }

    /**
     * {@inheritdoc}
     */
    public function createSchema(string $path, string $table = '') : void
    {
        $fp = \fopen($path, 'r');
        if ($fp === false) {
            return;
        }

        $table  = \strtr(empty($table) ? \basename($path, '.csv') : $table, ' ', '_');
        $titles = [];

        $delim = CsvSettings::getFileDelimiter($fp, 2);

        // get column titles
        $titles = \fgetcsv($fp, 4096, $delim);
        if ($titles === false) {
            \fclose($fp);
            return;
        }

        $titles = \array_map(function(string $title) : string {
            $title = \strtr(\trim($title), ' ', '_');
            $title = \preg_replace('/[^a-zA-Z0-9_]/', '', $title);

            return \strtr($title, ' ', '_');
        }, $titles);

        $cells = \fgetcsv($fp, null, $delim);
        if ($cells === false) {
            \fclose($fp);
            return;
        }

        $query = new SchemaBuilder($this->con);
        $query->createTable($table);

        foreach ($cells as $idx => $cell) {
            $datatype = SchemaBuilder::getTypeFromVariable($cell);

            $query->field($titles[$idx], $datatype);
        }

        $query->execute();

        \fclose($fp);
    }

    /**
     * {@inheritdoc}
     */
    public function import(string $path, string $table = '', ?\Closure $transform = null) : void
    {
        $fp = \fopen($path, 'r');
        if ($fp === false) {
            return;
        }

        $table  = \strtr(empty($table) ? \basename($path, '.csv') : $table, ' ', '_');
        $titles = [];

        $delim = CsvSettings::getFileDelimiter($fp, 2);

        // get column titles
        $titles = \fgetcsv($fp, 4096, $delim);
        if ($titles === false) {
            \fclose($fp);
            return;
        }

        $columns = \count($titles);
        if ($columns === 0) {
            \fclose($fp);
            return;
        }

        $titles = \array_map(function(string $title) : string {
            $title = \strtr(\trim($title), ' ', '_');
            $title = \preg_replace('/[^a-zA-Z0-9_]/', '', $title);

            return \strtr($title, ' ', '_');
        }, $titles);

        $titleCount = \count($titles);

        do {
            $counter = 0;

            // insert data
            $query = new Builder($this->con);
            $query->insert(...$titles)->into($table);

            while (($cells = \fgetcsv($fp, null, $delim)) !== false) {
                if (\count($cells) !== $titleCount) {
                    continue;
                }

                if ($transform !== null) {
                    foreach ($cells as $idx => $cell) {
                        $cells[$idx] = $transform($titles[$idx], $cell);
                    }
                }

                $query->values(...$cells);
                ++$counter;

                if ($counter > 250) {
                    break;
                }
            }

            $query->execute();
        } while ($cells !== false);

        \fclose($fp);
    }

    /**
     * {@inheritdoc}
     */
    public function export(string $path, array $queries) : void
    {
        $fp = \fopen($path, 'r+');
        if ($fp === false) {
            return;
        }

        foreach ($queries as $key => $query) {
            $results = $query->execute()?->fetchAll(\PDO::FETCH_ASSOC);
            if (!\is_array($results)) {
                continue;
            }

            if ($key > 0) {
                \fclose($fp);
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
    public function update(string $path, string $table = '') : void
    {
        $fp = \fopen($path, 'r+');
        if ($fp === false) {
            return;
        }

        $table  = \strtr(empty($table) ? \basename($path, '.csv') : $table, ' ', '_');
        $titles = [];

        // get column titles
        $titles = \fgetcsv($fp, 4096);
        if ($titles === false) {
            \fclose($fp);
            return;
        }

        $columns = \count($titles);
        if ($columns === 0) {
            \fclose($fp);
            return;
        }

        $titles = \array_map(function(string $title) : string {
            $title = \strtr(\trim($title), ' ', '_');
            $title = \preg_replace('/[^a-zA-Z0-9_]/', '', $title);

            return \strtr($title, ' ', '_');
        }, $titles);

        $idCol = (string) \array_shift($titles);

        // update data
        while (($cells = \fgetcsv($fp)) !== false) {
            $query = new Builder($this->con);
            $query->update($table);

            for ($j = 2; $j <= $columns; ++$j) {
                $query->sets((string) $titles[$j - 2], $cells[$j - 1]);
            }

            $query->where($idCol, '=', $cells[0]);
            $query->execute();
        }

        \fclose($fp);
    }
}
