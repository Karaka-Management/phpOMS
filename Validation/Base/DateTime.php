<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Validation\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Validation\Base;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Validate date.
 *
 * @package phpOMS\Validation\Base
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class DateTime extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public static function isValid(mixed $value, array $constraints = null) : bool
    {
        return (bool) \strtotime($value);
    }
}
