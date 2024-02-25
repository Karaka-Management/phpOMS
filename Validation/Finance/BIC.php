<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Validation\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation\Finance;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Validate BIC
 *
 * @package phpOMS\Validation\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BIC extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public static function isValid(mixed $value, ?array $constraints = null) : bool
    {
        if (!\is_string($value)) {
            return false;
        }

        return (bool) \preg_match('/^[a-z]{6}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $value);
    }
}
