<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\TestModel;

final class NullBaseModel extends BaseModel
{
    public function __construct(int $id = 0)
    {
        $this->id = $id;
    }
}
