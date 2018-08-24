<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Message\Http
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Message\Http;

/**
 * Rest request class.
 *
 * @package    phpOMS\Message\Http
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Rest
{

    /**
     * Make request.
     *
     * @param Request $request Request
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function request(Request $request) : string
    {
        $curl = \curl_init();

        if ($curl === false) {
            throw new \Exception();
        }

        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        switch ($request->getMethod()) {
            case RequestMethod::GET:
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case RequestMethod::PUT:
                \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case RequestMethod::DELETE:
                \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        if ($request->getMethod() !== RequestMethod::GET) {
            \curl_setopt($curl, CURLOPT_POST, 1);

            if ($request->getData() !== null) {
                \curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getData());
            }
        }

        if ($request->getUri()->getUser() !== '') {
            \curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            \curl_setopt($curl, CURLOPT_USERPWD, $request->getUri()->getUserInfo());
        }

        \curl_setopt($curl, CURLOPT_URL, $request->__toString());
        \curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = \curl_exec($curl);

        \curl_close($curl);

        return \is_bool($result) ? '' : $result;
    }
}
