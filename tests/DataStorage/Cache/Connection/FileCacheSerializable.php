<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

class FileCacheSerializable implements \Serializable
{
    public $val = 'asdf';

    public function serialize()
    {
        return 'abc';
    }

    public function unserialize($val) : void
    {
        $this->val = $val;
    }
}
