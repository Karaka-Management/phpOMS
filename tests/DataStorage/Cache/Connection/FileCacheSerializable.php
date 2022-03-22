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

class FileCacheSerializable
{
    public $val = 'asdf';

    public function __serialize()
    {
        return ['abc'];
    }

    public function __unserialize($val) : void
    {
        $this->val = $val;
    }
}
