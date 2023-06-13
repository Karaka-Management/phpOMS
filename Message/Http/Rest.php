<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Http
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Http;

use phpOMS\System\MimeType;

/**
 * Rest request class.
 *
 * @package phpOMS\Message\Http
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Rest
{
    /**
     * Make request.
     *
     * @param HttpRequest $request Request
     *
     * @return HttpResponse Returns the request result
     *
     * @throws \Exception this exception is thrown if an internal curl_init error occurs
     *
     * @since 1.0.0
     */
    public static function request(HttpRequest $request) : HttpResponse
    {
        $curl = \curl_init();

        if ($curl === false) {
            throw new \Exception('Internal curl_init error.'); // @codeCoverageIgnore
        }

        \curl_setopt($curl, \CURLOPT_NOBODY, true);

        // handle header
        $requestHeaders = $request->header->get();
        $headers        = [];

        foreach ($requestHeaders as $key => $header) {
            $headers[$key] = $key . ': ' . \implode('', $header);
        }

        \curl_setopt($curl, \CURLOPT_HTTPHEADER, $headers);
        \curl_setopt($curl, \CURLOPT_HEADER, true);
        \curl_setopt($curl, \CURLOPT_CONNECTTIMEOUT, 5);
        \curl_setopt($curl, \CURLOPT_TIMEOUT, 30);

        switch ($request->getMethod()) {
            case RequestMethod::GET:
                \curl_setopt($curl, \CURLOPT_HTTPGET, true);
                break;
            case RequestMethod::POST:
                \curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'POST');
                break;
            case RequestMethod::PUT:
                \curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case RequestMethod::DELETE:
                \curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        // handle none-get
        if ($request->getMethod() !== RequestMethod::GET) {
            \curl_setopt($curl, \CURLOPT_POST, 1);

            // handle different content types
            $contentType = $requestHeaders['content-type'] ?? [];
            if ($request->getData() !== null && (empty($contentType) || \in_array(MimeType::M_POST, $contentType))) {
                /* @phpstan-ignore-next-line */
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, \http_build_query($request->getData()));
            } elseif ($request->getData() !== null && \in_array(MimeType::M_JSON, $contentType)) {
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, \json_encode($request->getData()));
            } elseif ($request->getData() !== null && \in_array(MimeType::M_MULT, $contentType)) {
                $boundary = '----' . \uniqid();

                /* @phpstan-ignore-next-line */
                $data = self::createMultipartData($boundary, $request->getData());

                // @todo: Replace boundary/ with the correct boundary= in the future.
                //        Currently this cannot be done due to a bug. If we do it now the server cannot correclty populate php://input
                $headers['content-type']   = 'Content-Type: multipart/form-data; boundary/' . $boundary;
                $headers['content-length'] = 'Content-Length: ' . \strlen($data);

                \curl_setopt($curl, \CURLOPT_HTTPHEADER, $headers);
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $data);
            }
        }

        // handle user auth
        if ($request->uri->user !== '') {
            \curl_setopt($curl, \CURLOPT_HTTPAUTH, \CURLAUTH_BASIC);
            \curl_setopt($curl, \CURLOPT_USERPWD, $request->uri->getUserInfo());
        }

        $cHeaderString = '';
        $response      = new HttpResponse();

        \curl_setopt($curl, \CURLOPT_HEADERFUNCTION,
            function($curl, $header) use ($response, &$cHeaderString) {
                $cHeaderString .= $header;

                $length = \strlen($header);
                $header = \explode(':', $header, 2);

                if (\count($header) < 2) {
                    $response->header->set('', $line = \trim($header[0]));

                    if (\stripos(\strtoupper($line), 'HTTP/') === 0) {
                        $statusCode               = \explode(' ', $line, 3);
                        $response->header->status = (int) $statusCode[1];
                    }

                    return $length;
                }

                $name = \strtolower(\trim($header[0]));
                $response->header->set($name, \trim($header[1]));

                return $length;
            }
        );

        \curl_setopt($curl, \CURLOPT_URL, $request->__toString());
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, 1);

        $result = \curl_exec($curl);
        $len    = \strlen($cHeaderString);

        \curl_close($curl);

        $raw = \substr(\is_bool($result) ? '' : $result, $len === false ? 0 : $len);
        if (\stripos(\implode('', $response->header->get('content-type')), MimeType::M_JSON) !== false) {
            $temp = \json_decode($raw, true);
            if (!\is_array($temp)) {
                $temp = [];
            }

            $response->setResponse($temp);
        } else {
            $response->set('', $raw);
        }

        return $response;
    }

    /**
     * Create multipart data
     *
     * @param string $boundary Unique boundary id
     * @param array  $fields   Data array (key value pair)
     * @param array  $files    Files to upload
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function createMultipartData(string $boundary, array $fields = [], array $files = []) : string
    {
        $data  = '';
        $delim = $boundary;

        foreach ($fields as $name => $content) {
            $data .= '--' . $delim . "\r\n"
                . 'Content-Disposition: form-data; name="' . $name . "\"\r\n\r\n"
                . $content . "\r\n";
        }

        foreach ($files as $name => $content) {
            $data .= '--' . $delim . "\r\n"
                . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . "\"\r\n\r\n"
                . 'Content-Transfer-Encoding: binary' . "\r\n"
                . $content . "\r\n";
        }

        return $data . ('--' . $delim . "--\r\n");
    }
}
