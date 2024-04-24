<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Model\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Message;

use phpOMS\Stdlib\Base\Enum;

/**
 * DomAction class.
 *
 * @package phpOMS\Model\Message
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DomAction extends Enum
{
    public const CREATE_BEFORE = 0;

    public const CREATE_AFTER = 1;

    public const DELETE = 2;

    public const REPLACE = 3;

    public const MODIFY = 4;

    public const SHOW = 5;

    public const HIDE = 6;

    public const ACTIVATE = 7;

    public const DEACTIVATE = 8;
}
