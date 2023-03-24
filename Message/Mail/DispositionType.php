<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Stdlib\Base\Enum;

/**
 * Disposition enum.
 *
 * @package  phpOMS\Message\Mail
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DispositionType extends Enum
{
    public const PLAIN = 'plain';

    public const ALT = 'alt';

    public const ATTACHMENT = 'attach';

    public const INLINE = 'inline';
}
