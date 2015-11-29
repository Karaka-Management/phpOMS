<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Message;

use phpOMS\Datatypes\Enum;

/**
 * Request status enum.
 *
 * @category   Request
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class RequestStatus extends Enum
{
    const R_100 = 'Continue';

    const R_101 = 'Switching Protocols';

    const R_102 = 'Processing';

    const R_200 = 'OK';

    const R_201 = 'Created';

    const R_202 = 'Accepted';

    const R_203 = 'Non-Authoritative Information';

    const R_204 = 'No Content';

    const R_205 = 'Reset Content';

    const R_206 = 'Partial Content';

    const R_207 = 'Multi-Status';

    const R_300 = 'Multiple Choices';

    const R_301 = 'Moved Permanently';

    const R_302 = 'Found';

    const R_303 = 'See Other';

    const R_304 = 'Not Modified';

    const R_305 = 'Use Proxy';

    const R_306 = 'Switch Proxy';

    const R_307 = 'Temporary Redirect';

    const R_400 = 'Bad Request';

    const R_401 = 'Unauthorized';

    const R_402 = 'Payment Required';

    const R_403 = 'Forbidden';

    const R_404 = 'Not Found';

    const R_405 = 'Method Not Allowed';

    const R_406 = 'Not Acceptable';

    const R_407 = 'Proxy Authentication Required';

    const R_408 = 'Request Timeout';

    const R_409 = 'Conflict';

    const R_410 = 'Gone';

    const R_411 = 'Length Required';

    const R_412 = 'Precondition Failed';

    const R_413 = 'Request Entity Too Large';

    const R_414 = 'Request-URI Too Long';

    const R_415 = 'Unsupported Media Type';

    const R_416 = 'Requested Range Not Satisfiable';

    const R_417 = 'Expectation Failed';

    const R_418 = 'I\'m a teapot';

    const R_422 = 'Unprocessable Entity';

    const R_423 = 'Locked';

    const R_424 = 'Failed Dependency';

    const R_425 = 'Unordered Collection';

    const R_426 = 'Upgrade Required';

    const R_449 = 'Retry With';

    const R_450 = 'Blocked by Windows Parental Controls';

    const R_500 = 'Internal Server Error';

    const R_501 = 'Not Implemented';

    const R_502 = 'Bad Gateway';

    const R_503 = 'Service Unavailable';

    const R_504 = 'Gateway Timeout';

    const R_505 = 'HTTP Version Not Supported';

    const R_506 = 'Variant Also Negotiates';

    const R_507 = 'Insufficient Storage';

    const R_509 = 'Bandwidth Limit Exceeded';

    const R_510 = 'Not Extended';
}
