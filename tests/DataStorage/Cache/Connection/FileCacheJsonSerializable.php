<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

class FileCacheJsonSerializable implements \JsonSerializable
{
    public $val = 'asdf';

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return 'abc';
    }

    public function unserialize($val) : void
    {
        $this->val = \json_decode($val, true);
    }
}
