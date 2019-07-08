<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Excel;

use phpOMS\Utils\IO\IODatabaseMapper;

class ExcelDatabaseMapper implements IODatabaseMapper
{
    private $sources = [];

    private $lineBuffer = 500;

    public function addSource(string $source) : void
    {
        $this->sources[] = $source;
    }

    public function setLineBuffer(int $buffer) : void
    {
        $this->lineBuffer = $buffer;
    }

    public function setSources(array $sources) : void
    {
        $this->sources = $sources;
    }

    public function insert() : void
    {
    }
}
