<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\IO\Spreadsheet
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Spreadsheet;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;
use phpOMS\Utils\IO\IODatabaseMapper;
use phpOMS\Utils\StringUtils;

/**
 * Spreadsheet database mapper.
 *
 * @package phpOMS\Utils\IO\Spreadsheet
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class SpreadsheetDatabaseMapper implements IODatabaseMapper
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
     * @param ConnectionAbstract $con Database connection
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
        $reader = null;
        if (StringUtils::endsWith($path, '.xlsx')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif (StringUtils::endsWith($path, '.ods')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $reader->setReadDataOnly(true);
        $sheet = $reader->load($path);

        $tables = $sheet->getSheetCount();
        for ($i = 0; $i < $tables; ++$i) {
            $sheet->setActiveSheetIndex($i);

            $workSheet = $sheet->getSheet($i);
            $table     = \strtr(empty($table) ? $workSheet->getTitle() : $table, ' ', '_');
            $titles    = [];

            // get column titles
            $column = 1;
            while (!empty($value = $workSheet->getCell(StringUtils::intToAlphabet($column) . 1)->getCalculatedValue())) {
                if (!\is_string($value)) {
                    continue;
                }

                $value = \strtr(\trim($value), ' ', '_');
                $value = \preg_replace('/[^a-zA-Z0-9_]/', '', $value);

                if ($value === null) {
                    continue;
                }

                $titles[] = $value;

                ++$column;
            }

            $columns = \count($titles);
            if ($columns === 0) {
                continue;
            }

            $query = new SchemaBuilder($this->con);
            $query->createTable($table);

            $line = 2;
            if (empty($workSheet->getCell('A' . $line)->getCalculatedValue())) {
                continue;
            }

            for ($j = 1; $j <= $columns; ++$j) {
                $cells[] = $workSheet->getCell(StringUtils::intToAlphabet($j) . $line)->getCalculatedValue();
            }

            foreach ($cells as $idx => $cell) {
                $datatype = SchemaBuilder::getTypeFromVariable($cell);

                $query->field($titles[$idx], $datatype);
            }

            $query->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function import(string $path, string $table = '', ?\Closure $transform = null) : void
    {
        $reader = null;
        if (StringUtils::endsWith($path, '.xlsx')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif (StringUtils::endsWith($path, '.ods')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $reader->setReadDataOnly(true);
        $sheet = $reader->load($path);

        $tables = $sheet->getSheetCount();
        for ($i = 0; $i < $tables; ++$i) {
            $sheet->setActiveSheetIndex($i);

            $workSheet = $sheet->getSheet($i);
            $tableName = \strtr(empty($table) ? $workSheet->getTitle() : $table, ' ', '_');
            $titles    = [];

            // get column titles
            $column = 1;
            while (!empty($value = $workSheet->getCell(StringUtils::intToAlphabet($column) . 1)->getCalculatedValue())) {
                if (!\is_string($value)) {
                    continue;
                }

                $value    = \strtr(\trim($value), ' ', '_');
                $value    = \preg_replace('/[^a-zA-Z0-9_]/', '', $value);
                $titles[] = $value;

                ++$column;
            }

            $columns = \count($titles);
            if ($columns === 0) {
                continue;
            }

            $line = 2;

            do {
                $counter = 0;

                // insert data
                $query = new Builder($this->con);
                $query->insert(...$titles)->into($tableName);

                while ($hasData = !empty($workSheet->getCell('A' . $line)->getCalculatedValue())) {
                    $cells = [];
                    for ($j = 1; $j <= $columns; ++$j) {
                        $cells[] = $workSheet->getCell(StringUtils::intToAlphabet($j) . $line)->getCalculatedValue();
                    }

                    if ($transform !== null) {
                        foreach ($cells as $idx => $cell) {
                            $cells[$idx] = $transform($titles[$idx], $cell);
                        }
                    }

                    ++$line;

                    $query->values(...$cells);
                    ++$counter;

                    if ($counter > 250) {
                        break;
                    }
                }

                $query->execute();
            } while ($hasData);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function export(string $path, array $queries) : void
    {
        $sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet->getProperties()
            ->setCreator('Jingga')
            ->setLastModifiedBy('Jingga')
            ->setTitle('Database export')
            ->setSubject('Database export')
            ->setDescription('This document is automatically generated from a database export.');

        $sheetCount = $sheet->getSheetCount();

        foreach ($queries as $key => $query) {
            $results = $query->execute()?->fetchAll(\PDO::FETCH_ASSOC);
            if (!\is_array($results)) {
                continue;
            }

            if ($key > $sheetCount - 1) {
                $sheet->createSheet($key);
            }

            $workSheet = $sheet->setActiveSheetIndex($key);
            $rows      = \count($results);

            if ($rows < 1) {
                break;
            }

            $colCount = \count($results[0]);
            $columns  = \array_keys($results[0]);

            // set column titles
            for ($i = 1; $i <= $colCount; ++$i) {
                $workSheet->setCellValue(StringUtils::intToAlphabet($i) . 1, $columns[$i - 1]);
            }

            // set data
            $row = 2;
            foreach ($results as $result) {
                $col = 1;
                foreach ($result as $value) {
                    $workSheet->setCellValue(StringUtils::intToAlphabet($col) . $row, $value);
                    ++$col;
                }

                ++$row;
            }
        }

        if (StringUtils::endsWith($path, '.xlsx')) {
            (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet))->save($path);
        } elseif (StringUtils::endsWith($path, '.ods')) {
            (new \PhpOffice\PhpSpreadsheet\Writer\Ods($sheet))->save($path);
        } else {
            (new \PhpOffice\PhpSpreadsheet\Writer\Xls($sheet))->save($path);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(string $path, string $table = '') : void
    {
        $reader = null;
        if (StringUtils::endsWith($path, '.xlsx')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif (StringUtils::endsWith($path, '.ods')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $reader->setReadDataOnly(true);
        $sheet = $reader->load($path);

        $tables = $sheet->getSheetCount();
        for ($i = 0; $i < $tables; ++$i) {
            $sheet->setActiveSheetIndex($i);

            $workSheet = $sheet->getSheet($i);
            $tableName = \strtr(empty($table) ? $workSheet->getTitle() : $table, ' ', '_');
            $titles    = [];

            // get column titles
            $column = 1;
            while (!empty($value = $workSheet->getCell(StringUtils::intToAlphabet($column) . 1)->getCalculatedValue())) {
                if (!\is_string($value)) {
                    continue;
                }

                $value    = \strtr(\trim($value), ' ', '_');
                $value    = \preg_replace('/[^a-zA-Z0-9_]/', '', $value);
                $titles[] = $value;

                ++$column;
            }

            $columns = \count($titles);
            if ($columns === 0) {
                continue;
            }

            $idCol = (string) \array_shift($titles);

            // update data
            $line = 2;
            while (!empty($workSheet->getCell('A' . $line)->getCalculatedValue())) {
                $query = new Builder($this->con);
                $query->update($tableName);

                for ($j = 2; $j <= $columns; ++$j) {
                    $query->sets((string) $titles[$j - 2], $workSheet->getCell(StringUtils::intToAlphabet($j) . $line)->getCalculatedValue());
                }

                $query->where($idCol, '=', $workSheet->getCell('A' . $line)->getCalculatedValue());
                $query->execute();

                ++$line;
            }
        }
    }
}
