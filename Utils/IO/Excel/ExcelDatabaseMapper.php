<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\IO\Excel
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Excel;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\IO\IODatabaseMapper;
use phpOMS\Utils\StringUtils;

/**
 * Excel database mapper.
 *
 * @package phpOMS\Utils\IO\Excel
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ExcelDatabaseMapper implements IODatabaseMapper
{
    /**
     * Database connection
     *
     * @var   ConnectionAbstract
     * @since 1.0.0
     */
    private ConnectionAbstract $con;

    /**
     * Path to source or destination
     *
     * @var   string
     * @since 1.0.0
     */
    private string $path = '';

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
     * Add path
     *
     * This is the path of the source data in case of inserting/updating data or the destination file for selecting data.
     *
     * @param string $path File path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPath(string $path) : void
    {
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
            $sheet = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $sheet = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
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
            while (!empty($value = $workSheet->getCellByColmnAndRow($column, 1)->getValue())) {
                $titles[] = $value;
            }

            $columns = \count($titles);

            // insert data
            $query = new Builder($this->con);
            $query->insert(...$titles)->into($table);

            $line = 2;
            while (!empty($row = $workSheet->getCellByColumnAndRow(1, $line)->getValue())) {
                $cells = [];
                for ($j = 1; $j <= $columns; ++$j) {
                    $cells[] = $workSheet->getCellByColumnAndRow(j, $line)->getValue();
                }

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
        $sheet = new Spreadsheet();
        $sheet->getProperties()
            ->setCreator('Orange-Management')
            ->setLastModifiedBy('Orange-Management')
            ->setTitle('Database export')
            ->setSubject('Database export')
            ->setDescription('This document is automatically generated from a database export.');

        $sheetCount = $sheet->getSheetCount();

        foreach ($queries as $key => $query) {
            $results = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);

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
                $workSheet->setCellValueByColumnAndRow($i, 1, $columns[0][$i - 1]);
            }

            // set data
            foreach ($results as $key => $result) {
                for ($i = 1; $i <= $colCount; ++$i) {
                    $workSheet->setCellValueByColumnAndRow($i, $key + 1, $result[$i - 1]);
                }
            }
        }

        if (StringUtils::endsWith($this->path, '.xlsx')) {
            (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx())->save($this->path);
        } elseif (StringUtils::endsWith($this->path, '.ods')) {
            (new \PhpOffice\PhpSpreadsheet\Writer\Ods())->save($this->path);
        } else {
            (new \PhpOffice\PhpSpreadsheet\Writer\Xls())->save($this->path);
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
            $sheet = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $sheet = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
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
            while (!empty($value = $workSheet->getCellByColmnAndRow($column, 1)->getValue())) {
                $titles[] = $value;
            }

            $columns = \count($titles);

            // insert data
            $line = 2;
            while (!empty($row = $workSheet->getCellByColumnAndRow(1, $line)->getValue())) {
                $query = new Builder($this->con);
                $query->update(...$titles)->into($table);

                $cells = [];
                for ($j = 1; $j <= $columns; ++$j) {
                    $cells[] = $workSheet->getCellByColumnAndRow(j, $line)->getValue();
                }

                $query->values(...$cells)->where($titles[0], '=', $cells[0]);
                $query->execute();
            }
        }
    }
}
