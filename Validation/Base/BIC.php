<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Validation\Base;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Validator abstract.
 *
 * @category   Validation
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class BIC extends ValidatorAbstract
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function isValid($value) : bool
    {
        return (bool) preg_match('/^[a-z]{6}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $value);
    }
}
