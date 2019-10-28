<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    test
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\tests\Socket\Server;

use phpOMS\Socket\Client\Client;

require_once __DIR__ . '/../../../Autoloader.php';
$config = require_once __DIR__ . '/../../../../config.php';

\sleep(5);

function handleSocketError($sock) : void
{
    if (\is_resource($sock) && ($err = \socket_last_error($sock)) !== 0) {
        \file_put_contents(__DIR__ . '/client.log', \socket_strerror($err) . "\n", \FILE_APPEND);
        \socket_clear_error();
    }
}

try {
    $sock = @\socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);
    \socket_set_nonblock($sock);
    @\socket_connect($sock, '127.0.0.1', $config['socket']['master']['port']);
    handleSocketError($sock);

    $msgs = [
        'help' . "\r",
        'shutdown' . "\r",
    ];

    foreach ($msgs as $msg) {
        var_dump($msg);
        @\socket_write($sock, $msg, \strlen($msg));
        handleSocketError($sock);

        $data = @\socket_read($sock, 1024);
        handleSocketError($sock);

        /* Server no data */
        if ($data === false) {
            continue;
        }

        /* Normalize */
        $data = \trim($data);

        \file_put_contents(__DIR__ . '/client.log', $data, \FILE_APPEND);
    }

    handleSocketError($sock);
    \socket_close($sock);
} catch (\Throwable $e) {
    \file_put_contents(__DIR__ . '/client.log', $e->getMessage(), \FILE_APPEND);
}

handleSocketError($sock);
$sock = null;