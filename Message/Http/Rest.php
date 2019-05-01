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
     * @return string Returns the request result
     *
     * @throws \Exception this exception is thrown if an internal curl_init error occurs
     *
     * @since  1.0.0
     */
    public static function request(Request $request) : Response
    {
        $curl = \curl_init();

        if ($curl === false) {
            throw new \Exception('Internal curl_init error.'); // @codeCoverageIgnore
        }

        \curl_setopt($curl, \CURLOPT_NOBODY, true);

        $headers = $request->getHeader()->get();
        foreach ($headers as $key => $header) {
            $headers[$key] = $key . ': ' . \implode('', $header);
        }

        curl_setopt($curl, \CURLOPT_HTTPHEADER, $headers);
        \curl_setopt($curl, \CURLOPT_HEADER, true);

        switch ($request->getMethod()) {
            case RequestMethod::GET:
                \curl_setopt($curl, \CURLOPT_HTTPGET, true);
                break;
            case RequestMethod::PUT:
                \curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case RequestMethod::DELETE:
                \curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        if ($request->getMethod() !== RequestMethod::GET) {
            \curl_setopt($curl, \CURLOPT_POST, 1);

            if ($request->getData() !== null) {
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $request->getData());
            }
        }

        if ($request->getUri()->getUser() !== '') {
            \curl_setopt($curl, \CURLOPT_HTTPAUTH, \CURLAUTH_BASIC);
            \curl_setopt($curl, \CURLOPT_USERPWD, $request->getUri()->getUserInfo());
        }

        $cHeaderString = '';
        $response      = new Response();

        curl_setopt($curl, \CURLOPT_HEADERFUNCTION,
            function($curl, $header) use ($response, &$cHeaderString) {
                $cHeaderString .= $header;

                $length = \strlen($header);
                $header = \explode(':', $header, 2);

                if (\count($header) < 2) {
                    return $length;
                }

                $name = \strtolower(\trim($header[0]));
                $response->getHeader()->set($name, \trim($header[1]));

                return $length;
            }
        );

        \curl_setopt($curl, \CURLOPT_URL, $request->__toString());
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);

        $result = \curl_exec($curl);

        \curl_close($curl);

        $response->set('', \substr($result, \strlen($cHeaderString)));

        return $response;
    }
}
