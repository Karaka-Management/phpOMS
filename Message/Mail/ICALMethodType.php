<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

use phpOMS\Stdlib\Base\Enum;

/**
 * Calendar message types enum.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class ICALMethodType extends Enum
{
    public const REQUEST = 'REQUEST';

    public const PUBLISH = 'PUBLISH';

    public const REPLY = 'REPLY';

    public const ADD = 'ADD';

    public const CANCEL = 'CANCEL';

    public const REFRESH = 'REFRESH';

    public const COUNTER = 'COUNTER';

    public const DECLINECOUNTER = 'DECLINECOUNTER';
}
