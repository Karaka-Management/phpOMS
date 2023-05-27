<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package    test
 * @copyright  Dennis Eichhorn
 * @license    OMS License 2.0
 * @version    1.0.0
 * @link       http://karaka.com
 */
declare(strict_types=1);

namespace phpOMS\tests\Socket\Server;

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
        "handshake\r", // this needs to happen first (of course the submitted handshake data needs to be implemented correctl. just sending this is of course bad!)
        "help\r",
        "shutdown\r",
    ];

    foreach ($msgs as $msg) {
        \file_put_contents(__DIR__ . '/client.log', 'Sending: ' . $msg . "\n", \FILE_APPEND);
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

        \file_put_contents(__DIR__ . '/client.log', 'Receiving' . $data . "\n", \FILE_APPEND);
    }

    handleSocketError($sock);
    \socket_close($sock);
} catch (\Throwable $t) {
    \file_put_contents(__DIR__ . '/client.log', $t->getMessage(), \FILE_APPEND);
}

handleSocketError($sock);
$sock = null;
