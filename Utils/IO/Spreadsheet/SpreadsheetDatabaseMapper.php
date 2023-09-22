<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\IO\Spreadsheet
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Spreadsheet;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\IO\IODatabaseMapper;
use phpOMS\Utils\StringUtils;

/**
 * Spreadsheet database mapper.
 *
 * @package phpOMS\Utils\IO\Spreadsheet
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class SpreadsheetDatabaseMapper implements IODatabaseMapper
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
        $reader = null;
        if (StringUtils::endsWith($this->path, '.xlsx')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif (StringUtils::endsWith($this->path, '.ods')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $reader->setReadDataOnly(true);
        $sheet = $reader->load($this->path);

        $tables = $sheet->getSheetCount();
        for ($i = 0; $i < $tables; ++$i) {
            $sheet->setActiveSheetIndex($i);

            $workSheet = $sheet->getSheet($i);
            $table     = $workSheet->getTitle();
            $titles    = [];

            // get column titles
            $column = 1;
            while (!empty($value = $workSheet->getCell(StringUtils::intToAlphabet($column) . 1)->getCalculatedValue())) {
                $titles[] = $value;
                ++$column;
            }

            $columns = \count($titles);

            // insert data
            $query = new Builder($this->con);
            $query->insert(...$titles)->into($table);

            $line = 2;
            while (!empty($workSheet->getCell('A' . $line)->getCalculatedValue())) {
                $cells = [];
                for ($j = 1; $j <= $columns; ++$j) {
                    $cells[] = $workSheet->getCell(StringUtils::intToAlphabet($j) . $line)->getCalculatedValue();
                }

                ++$line;

                $query->values(...$cells);
            }

            $query->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function select(array $queries) : void
    {
        $sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet->getProperties()
            ->setCreator('Karaka')
            ->setLastModifiedBy('Karaka')
            ->setTitle('Database export')
            ->setSubject('Database export')
            ->setDescription('This document is automatically generated from a database export.');

        $sheetCount = $sheet->getSheetCount();

        foreach ($queries as $key => $query) {
            $results = $query->execute()?->fetchAll(\PDO::FETCH_ASSOC);
            if ($results === null) {
                continue;
            }

            if ($key > $sheetCount - 1) {
                $sheet->createSheet($key);
            }

            $workSheet = $sheet->setActiveSheetIndex($key);
            $rows      = $results === null ? 0 : \count($results);

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

        if (StringUtils::endsWith($this->path, '.xlsx')) {
            (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($sheet))->save($this->path);
        } elseif (StringUtils::endsWith($this->path, '.ods')) {
            (new \PhpOffice\PhpSpreadsheet\Writer\Ods($sheet))->save($this->path);
        } else {
            (new \PhpOffice\PhpSpreadsheet\Writer\Xls($sheet))->save($this->path);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update() : void
    {
        $reader = null;
        if (StringUtils::endsWith($this->path, '.xlsx')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif (StringUtils::endsWith($this->path, '.ods')) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $reader->setReadDataOnly(true);
        $sheet = $reader->load($this->path);

        $tables = $sheet->getSheetCount();
        for ($i = 0; $i < $tables; ++$i) {
            $sheet->setActiveSheetIndex($i);

            $workSheet = $sheet->getSheet($i);
            $table     = $workSheet->getTitle();
            $titles    = [];

            // get column titles
            $column = 1;
            while (!empty($value = $workSheet->getCell(StringUtils::intToAlphabet($column) . 1)->getCalculatedValue())) {
                $titles[] = $value;
                ++$column;
            }

            $columns = \count($titles);

            // update data
            $line = 2;
            while (!empty($row = $workSheet->getCell('A' . $line)->getCalculatedValue())) {
                $query = new Builder($this->con);
                $query->update($table)->into($table);

                for ($j = 2; $j <= $columns; ++$j) {
                    $query->sets($titles[$j - 1], $workSheet->getCell(StringUtils::intToAlphabet($j) . $line)->getCalculatedValue());
                }

                $query->where($titles[0], '=', $workSheet->getCell('A' . $line)->getCalculatedValue());
                $query->execute();

                ++$line;
            }
        }
    }
}
