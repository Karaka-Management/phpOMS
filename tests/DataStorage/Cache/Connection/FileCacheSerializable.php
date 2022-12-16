<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\Contract\SerializableInterface;

class FileCacheSerializable implements SerializableInterface
{
    public $val = 'asdf';

    public function serialize() : string
    {
        return 'abc';
    }

    public function unserialize(mixed $val) : void
    {
        $this->val = $val;
    }
}
