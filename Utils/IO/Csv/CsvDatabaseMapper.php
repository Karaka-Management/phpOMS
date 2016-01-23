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
namespace phpOMS\Utils\IO\Csv;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\IO\IODatabaseMapper;

class CsvDatabaseMapper implements IODatabaseMapper
{
    use CsvSettingsTrait;

    private $db = null;

    private $sources = [];

    private $delimiter  = ';';
    private $enclosure  = '"';
    private $lineBuffer = 500;

    private $autoIdentifyCsvSettings = false;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addSource(string $source)
    {
        $this->sources[] = $source;

        $this->sources = array_unique($this->sources);
    }

    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    public function autoIdentifyCsvSettings(bool $identify)
    {
        $this->autoIdentifyCsvSettings = $identify;
    }

    public function setLineBuffer(int $buffer)
    {
        $this->lineBuffer = $buffer;
    }

    public function insert()
    {
        foreach ($this->sources as $source) {
            $file      = fopen($source, 'r');
            $header    = [];
            $delimiter = $this->autoIdentifyCsvSettings ? $this->getFileDelimiter($file, 100) : $this->delimiter;

            if (feof($file) && ($line = fgetcsv($file, 0, $delimiter)) !== false) {
                $header = $line;
            }

            $query = new Builder($this->db);
            $query->insert(...$header)->into(str_replace(' ', '', explode($source, '.')));

            while (feof($file)) {
                $c = 0;

                while (($line = fgetcsv($file)) !== false && $c < $this->lineBuffer && feof($file)) {
                    $query->values($line);
                    $c++;
                }

                $this->db->con->prepare($query->toSql())->execute();
            }

            fclose($file);
        }
    }
}
