<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache\Connection;

class FileCacheJsonSerializable implements \JsonSerializable
{
    public $val = 'asdf';

    public function jsonSerialize()
    {
        return 'abc';
    }

    public function unserialize($val) : void
    {
        $this->val = \json_decode($val, true);
    }
}