<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Message\Http
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Http;

use phpOMS\Stdlib\Base\Enum;

/**
 * Request method enum.
 *
 * @package phpOMS\Message\Http
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class RequestMethod extends Enum
{
    public const GET = 'GET';    /* GET */

    public const POST = 'POST';   /* POST */

    public const PUT = 'PUT';    /* PUT */

    public const DELETE = 'DELETE'; /* DELETE */

    public const HEAD = 'HEAD';   /* HEAD */

    public const TRACE = 'TRACE';  /* TRACE */
}
