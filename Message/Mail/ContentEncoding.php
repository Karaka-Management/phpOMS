<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Message\Mail
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Stdlib\Base\Enum;

/**
 * OS type enum.
 *
 * OS Types which could be useful in order to create statistics or deliver OS specific content.
 *
 * @package    phpOMS\Message\Mail
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class ContentEncoding extends Enum
{
    public const BASE64   = 1;
    public const EIGHTBIT = 2;
    public const QPRINT   = 3;
}
