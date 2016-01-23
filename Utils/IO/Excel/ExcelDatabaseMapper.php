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
namespace phpOMS\Utils\IO\Excel;

use phpOMS\Utils\IO\IODatabaseMapper;

class ExcelDatabaseMapper implements IODatabaseMapper
{
    private $sources    = [];
    private $lineBuffer = 500;

    public function addSource(string $source)
    {
        $this->sources[] = $source;
    }

    public function setLineBuffer(int $buffer)
    {
        $this->lineBuffer = $buffer;
    }

    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    public function insert()
    {
    }
}

