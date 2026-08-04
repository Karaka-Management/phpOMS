<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Cache\Connection;

class FileCacheJsonSerializable implements \JsonSerializable
{
    public $val = 'asdf';

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return 'abc';
    }

    public function unserialize(mixed $val) : void
    {
        $this->val = \json_decode($val, true);
    }
}
