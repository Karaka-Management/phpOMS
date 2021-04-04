<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Mail
 * @license   GLGPL 2.1 License
 * @version   1.0.0
 * @link      https://orange-management.org
 *
 * Extended based on:
 * GLGPL 2.1 License
 * (c) 2012 - 2015 Marcus Bointon, 2010 - 2012 Jim Jagielski, 2004 - 2009 Andy Prevost
 * (c) PHPMailer
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Smtp mail class.
 *
 * @package phpOMS\Message\Mail
 * @license GLGPL 2.1 License
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Smtp
{
    /**
     * The maximum line length allowed
     *
     * @var int
     * @since 1.0.0
     */
    const MAX_REPLY_LENGTH = 512;

    /**
     * SMTP RFC standard line ending
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $LE = "\r\n";

    /**
     * Whether to use VERP.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $doVerp = false;

    /**
     * The timeout value for connection, in seconds.
     *
     * @var int
     * @since 1.0.0
     */
    public int $timeout = 300;

    /**
     * How long to wait for commands to complete, in seconds.
     *
     * @var int
     * @since 1.0.0
     */
    public int $timeLimit = 300;

    /**
     * The last transaction ID issued in response to a DATA command,
     * if one was detected.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $lastSmtpTransactionId = '';

    /**
     * The socket for the server connection.
     *
     * @var ?resource
     * @since 1.0.0
     */
    protected $con;

    /**
     * The reply the server sent to us for HELO.
     * If empty no HELO string has yet been received.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $heloRply = '';

    /**
     * The set of SMTP extensions sent in reply to EHLO command.
     *
     * @var array
     * @since 1.0.0
     */
    protected array $serverCaps = [];

    /**
     * The most recent reply received from the server.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $lastReply = '';

    /**
     * Connect to an SMTP server.
     *
     * @param string $host    SMTP server IP or host name
     * @param int    $port    The port number to connect to
     * @param int    $timeout How long to wait for the connection to open
     * @param array  $options An array of options for stream_context_create()
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function connect(string $host, int $port = 25, int $timeout = 30, array $options = []) : bool
    {
        if ($this->isConnected()) {
            return false;
        }

        $this->con = $this->getSMTPConnection($host, $port, $timeout, $options);
        if ($this->con === null) {
            return false;
        }

        $this->lastReply = $this->getLines();
        $responseCode    = (int) \substr($this->lastReply, 0, 3);
        if ($responseCode === 220) {
            return true;
        }

        if ($responseCode === 554) {
            $this->quit();
        }

        $this->close();

        return false;
    }

    /**
     * Create connection to the SMTP server.
     *
     * @param string $host    SMTP server IP or host name
     * @param int    $port    The port number to connect to
     * @param int    $timeout How long to wait for the connection to open
     * @param array  $options An array of options for stream_context_create()
     *
     * @return null|resource
     *
     * @since 1.0.0
     */
    protected function getSMTPConnection(string $host, int $port = 25, int $timeout = 30, array $options = []) : mixed
    {
        static $streamok;
        if ($streamok === null) {
            $streamok = \function_exists('stream_socket_client');
        }

        $errno  = 0;
        $errstr = '';

        if ($streamok) {
            $socketContext = \stream_context_create($options);
            $connection    = \stream_socket_client($host . ':' . $port, $errno, $errstr, $timeout, \STREAM_CLIENT_CONNECT, $socketContext);
        } else {
            //Fall back to fsockopen which should work in more places, but is missing some features
            $connection = \fsockopen($host, $port, $errno, $errstr, $timeout);
        }

        if (!\is_resource($connection)) {
            return null;
        }

        // SMTP server can take longer to respond, give longer timeout for first read
        // Windows does not have support for this timeout function
        if (\strpos(\PHP_OS, 'WIN') !== 0) {
            $max = (int) \ini_get('max_execution_time');
            if ($max !== 0 && $timeout > $max && \strpos(\ini_get('disable_functions'), 'set_time_limit') === false) {
                \set_time_limit($timeout);
            }

            \stream_set_timeout($connection, $timeout, 0);
        }

        return $connection === false ? null : $connection;
    }

    /**
     * Initiate a TLS (encrypted) session.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function startTLS() : bool
    {
        if (!$this->sendCommand('STARTTLS', 'STARTTLS', 220)) {
            return false;
        }

        $crypto_method = \STREAM_CRYPTO_METHOD_TLS_CLIENT;
        if (\defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
            $crypto_method |= \STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            $crypto_method |= \STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT;
        }

        return (bool) \stream_socket_enable_crypto($this->con, true, $crypto_method);
    }

    /**
     * Perform SMTP authentication.
     * Must be run after hello().
     *
     * @param string $username The user name
     * @param string $password The password
     * @param string $authtype The auth type (CRAM-MD5, PLAIN, LOGIN, XOAUTH2)
     * @param OAuth  $oauth    An optional OAuth instance for XOAUTH2 authentication
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function authenticate(
        string $username,
        string $password,
        string $authtype = '',
        mixed $oauth = null
    ) : bool {
        if (empty($this->serverCaps)) {
            return false;
        }

        if (isset($this->serverCaps['EHLO'])) {
            // SMTP extensions are available; try to find a proper authentication method
            if (!isset($this->serverCaps['AUTH'])) {
                // 'at this stage' means that auth may be allowed after the stage changes
                // e.g. after STARTTLS
                return false;
            }

            //If we have requested a specific auth type, check the server supports it before trying others
            if ($authtype !== '' && !\in_array($authtype, $this->serverCaps['AUTH'], true)) {
                $authtype = '';
            }

            if ($authtype !== '') {
                //If no auth mechanism is specified, attempt to use these, in this order
                //Try CRAM-MD5 first as it's more secure than the others
                foreach (['CRAM-MD5', 'LOGIN', 'PLAIN', 'XOAUTH2'] as $method) {
                    if (\in_array($method, $this->serverCaps['AUTH'], true)) {
                        $authtype = $method;
                        break;
                    }
                }

                if ($authtype === '') {
                    return false;
                }
            }

            if (!\in_array($authtype, $this->serverCaps['AUTH'], true)) {
                return false;
            }
        } elseif ($authtype === '') {
            $authtype = 'LOGIN';
        }

        switch ($authtype) {
            case 'PLAIN':
                // Start authentication
                if (!$this->sendCommand('AUTH', 'AUTH PLAIN', 334)
                    || !$this->sendCommand('User & Password',
                            \base64_encode("\0" . $username . "\0" . $password),
                            235
                        )
                ) {
                    return false;
                }
                break;
            case 'LOGIN':
                // Start authentication
                if (!$this->sendCommand('AUTH', 'AUTH LOGIN', 334)
                    || !$this->sendCommand('Username', \base64_encode($username), 334)
                    || !$this->sendCommand('Password', \base64_encode($password), 235)
                ) {
                    return false;
                }
                break;
            case 'CRAM-MD5':
                // Start authentication
                if (!$this->sendCommand('AUTH CRAM-MD5', 'AUTH CRAM-MD5', 334)) {
                    return false;
                }

                $challenge = \base64_decode(\substr($this->lastReply, 4));
                $response  = $username . ' ' . $this->hmac($challenge, $password);

                // send encoded credentials
                return $this->sendCommand('Username', \base64_encode($response), 235);
            case 'XOAUTH2':
                //The OAuth instance must be set up prior to requesting auth.
                if ($OAuth === null) {
                    return false;
                }

                $oauth = $OAuth->getOauth64();
                if (!$this->sendCommand('AUTH', 'AUTH XOAUTH2 ' . $oauth, 235)) {
                    return false;
                }
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * Calculate an MD5 HMAC hash.
     *
     * @param string $data The data to hash
     * @param string $key  The key to hash with
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function hmac(string $data, string $key) : string
    {
        // RFC 2104 HMAC implementation for php.
        // Creates an md5 HMAC.
        // by Lance Rushing
        $byteLen = 64;
        if (\strlen($key) > $byteLen) {
            $key = \pack('H*', \md5($key));
        }

        $key    = \str_pad($key, $byteLen, \chr(0x00));
        $ipad   = \str_pad('', $byteLen, \chr(0x36));
        $opad   = \str_pad('', $byteLen, \chr(0x5c));
        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return \md5($k_opad . \pack('H*', \md5($k_ipad . $data)));
    }

    /**
     * Check connection state.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isConnected() : bool
    {
        if (!\is_resource($this->con)) {
            return false;
        }

        $status = \stream_get_meta_data($this->con);
        if ($status['eof']) {
            $this->close();

            return false;
        }

        return true;
    }

    /**
     * Close the socket and clean up the state of the class.
     * Try to QUIT first!
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function close() : void
    {
        $this->serverCaps = [];
        $this->heloRply   = '';

        if (\is_resource($this->con)) {
            \fclose($this->con);
            $this->con = null;
        }
    }

    /**
     * Send an SMTP DATA command.
     *
     * @param string $msg_data Message data to send
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function data($msg_data, int $maxLineLength = 998) : bool
    {
        if (!$this->sendCommand('DATA', 'DATA', 354)) {
            return false;
        }

        /* The server is ready to accept data!
         * According to rfc821 we should not send more than 1000 characters on a single line (including the LE)
         */
        $lines = \explode("\n", \str_replace(["\r\n", "\r"], "\n", $msg_data));

        /* To distinguish between a complete RFC822 message and a plain message body, we check if the first field
         * of the first line (':' separated) does not contain a space then it _should_ be a header and we will
         * process all lines before a blank line as headers.
         */
        $field     = \substr($lines[0], 0, \strpos($lines[0], ':'));
        $inHeaders = (!empty($field) && \strpos($field, ' ') === false);

        foreach ($lines as $line) {
            $linesOut = [];
            if ($inHeaders && $line === '') {
                $inHeaders = false;
            }

            while (isset($line[$maxLineLength])) {
                $pos = \strrpos(\substr($line, 0, $maxLineLength), ' ');
                if (!$pos) {
                    $pos        = $maxLineLength - 1;
                    $linesOut[] = \substr($line, 0, $pos);
                    $line       = \substr($line, $pos);
                } else {
                    $linesOut[] = \substr($line, 0, $pos);
                    $line       = \substr($line, $pos + 1);
                }

                if ($inHeaders) {
                    $line = "\t" . $line;
                }
            }

            $linesOut[] = $line;

            foreach ($linesOut as $lineOut) {
                if (!empty($lineOut) && $lineOut[0] === '.') {
                    $lineOut = '.' . $lineOut;
                }

                $this->clientSend($lineOut . self::$LE, 'DATA');
            }
        }

        $tmpTimeLimit     = $this->timeLimit;
        $this->timeLimit *= 2;
        $result           = $this->sendCommand('DATA END', '.', 250);

        $this->recordLastTransactionId();

        $this->timeLimit = $tmpTimeLimit;

        return $result;
    }

    /**
     * Send an SMTP HELO or EHLO command.
     *
     * @param string $host The host name or IP to connect to
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hello(string $host = '') : bool
    {
        if ($this->sendHello('EHLO', $host)) {
            return true;
        }

        if (\substr($this->heloRply, 0, 3) == '421') {
            return false;
        }

        return $this->sendHello('HELO', $host);
    }

    /**
     * Send an SMTP HELO or EHLO command.
     *
     * @param string $hello The HELO string
     * @param string $host  The hostname to say we are
     *
     * @return bool
     *
     * @since 1.0.0
     */
    protected function sendHello(string $hello, string $host) : bool
    {
        $status         = $this->sendCommand($hello, $hello . ' ' . $host, 250);
        $this->heloRply = $this->lastReply;

        if ($status) {
            $this->parseHelloFields($hello);
        } else {
            $this->serverCaps = [];
        }

        return $status;
    }

    /**
     * Parse a reply to HELO/EHLO command to discover server extensions.
     *
     * @param string $type `HELO` or `EHLO`
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected function parseHelloFields(string $type) : void
    {
        $this->serverCaps = [];
        $lines            = \explode("\n", $this->heloRply);

        foreach ($lines as $n => $s) {
            //First 4 chars contain response code followed by - or space
            $s = \trim(\substr($s, 4));
            if (empty($s)) {
                continue;
            }

            $fields = \explode(' ', $s);
            if (!empty($fields)) {
                if (!$n) {
                    $name   = $type;
                    $fields = $fields[0];
                } else {
                    $name = \array_shift($fields);
                    switch ($name) {
                        case 'SIZE':
                            $fields = ($fields ? $fields[0] : 0);
                            break;
                        case 'AUTH':
                            if (!\is_array($fields)) {
                                $fields = [];
                            }
                            break;
                        default:
                            $fields = true;
                    }
                }

                $this->serverCaps[$name] = $fields;
            }
        }
    }

    /**
     * Send an SMTP MAIL command.
     *
     * @param string $from Source address of this message
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function mail(string $from) : bool
    {
        $useVerp = ($this->doVerp ? ' XVERP' : '');

        return $this->sendCommand('MAIL FROM', 'MAIL FROM:<' . $from . '>' . $useVerp, 250);
    }

    /**
     * Send an SMTP QUIT command.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function quit() : bool
    {
        $status = $this->sendCommand('QUIT', 'QUIT', 221);
        if ($status) {
            $this->close();
        }

        return $status;
    }

    /**
     * Send an SMTP RCPT command.
     *
     * @param string $address The address the message is being sent to
     * @param string $dsn     Comma separated list of DSN notifications
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function recipient(string $address, string $dsn = DsnNotificationType::NONE) : bool
    {
        if ($dsn === '') {
            $rcpt = 'RCPT TO:<' . $address . '>';
        } else {
            $notify = [];

            if (\strpos($dsn, 'NEVER') !== false) {
                $notify[] = 'NEVER';
            } else {
                foreach (['SUCCESS', 'FAILURE', 'DELAY'] as $value) {
                    if (\strpos($dsn, $value) !== false) {
                        $notify[] = $value;
                    }
                }
            }

            $rcpt = 'RCPT TO:<' . $address . '> NOTIFY=' . \implode(',', $notify);
        }

        return $this->sendCommand('RCPT TO', $rcpt, [250, 251]);
    }

    /**
     * Send an SMTP RSET command.
     *
     * @return bool s
     *
     * @since 1.0.0
     */
    public function reset() : bool
    {
        return $this->sendCommand('RSET', 'RSET', 250);
    }

    /**
     * Send a command to an SMTP server and check its return code.
     *
     * @param string    $command       The command name - not sent to the server
     * @param string    $commandstring The actual command to send
     * @param int|array $expect        One or more expected integer success codes
     *
     * @return bool
     *
     * @since 1.0.0
     */
    protected function sendCommand(string $command, string $commandstring, int | array $expect) : bool
    {
        if (!$this->isConnected()) {
            return false;
        }

        if ((\strpos($commandstring, "\n") !== false)
            || (\strpos($commandstring, "\r") !== false)
        ) {
            return false;
        }

        $this->clientSend($commandstring . self::$LE, $command);

        $this->lastReply = $this->getLines();

        $matches = [];
        if (\preg_match('/^([\d]{3})[ -](?:([\d]\\.[\d]\\.[\d]{1,2}) )?/', $this->lastReply, $matches)) {
            $code   = (int) $matches[1];
            $codeEx = \count($matches) > 2 ? $matches[2] : null;

            // Cut off error code from each response line
            $detail = \preg_replace(
                "/{$code}[ -]" .
                ($codeEx ? \str_replace('.', '\\.', $codeEx) . ' ' : '') . '/m',
                '',
                $this->lastReply
            );
        } else {
            // Fall back to simple parsing if regex fails
            $code   = (int) \substr($this->lastReply, 0, 3);
            $detail = \substr($this->lastReply, 4);
        }

        if (!\in_array($code, (array) $expect, true)) {
            return false;
        }

        return true;
    }

    /**
     * Send an SMTP SAML command.
     * Starts a mail transaction from the email address specified in $from.
     *
     * @param string $from The address the message is from
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function sendAndMail(string $from) : bool
    {
        return $this->sendCommand('SAML', "SAML FROM:${from}", 250);
    }

    /**
     * Send an SMTP VRFY command.
     *
     * @param string $name The name to verify
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function verify(string $name) : bool
    {
        return $this->sendCommand('VRFY', "VRFY ${name}", [250, 251]);
    }

    /**
     * Send an SMTP NOOP command.
     * Used to keep keep-alives alive.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function noop() : bool
    {
        return $this->sendCommand('NOOP', 'NOOP', 250);
    }

    /**
     * Send raw data to the server.
     *
     * @param string $data    The data to send
     * @param string $command Optionally, the command this is part of, used only for controlling debug output
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function clientSend(string $data, string $command = '') : int
    {
        $result = \fwrite($this->con, $data);

        return $result === false ? -1 : $result;
    }

    /**
     * Get SMTP extensions available on the server.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getServerExtList() : array
    {
        return $this->serverCaps;
    }

    /**
     * Get metadata about the SMTP server from its HELO/EHLO response.
     *
     * @param string $name Name of SMTP extension
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function getServerExt(string $name) : bool
    {
        if (empty($this->serverCaps)) {
            // HELO/EHLO has not been sent
            return false;
        }

        if (!isset($this->serverCaps[$name])) {
            if ($name === 'HELO') {
                // Server name
                //return $this->serverCaps['EHLO'];
            }

            return false;
        }

        return isset($this->serverCaps[$name]);
    }

    /**
     * Get the last reply from the server.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLastReply() : string
    {
        return $this->lastReply;
    }

    /**
     * Read the SMTP server's response.
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function getLines() : string
    {
        if (!\is_resource($this->con)) {
            return '';
        }

        $data    = '';
        $endTime = 0;

        \stream_set_timeout($this->con, $this->timeout);
        if ($this->timeLimit > 0) {
            $endTime = \time() + $this->timeLimit;
        }

        $selR  = [$this->con];
        $selW  = null;
        $tries = 0;

        while (\is_resource($this->con) && !\feof($this->con)) {
            $n = \stream_select($selR, $selW, $selW, $this->timeLimit);
            if ($n === false) {
                if ($tries < 3) {
                    ++$tries;
                    continue;
                } else {
                    break;
                }
            }

            $str   = \fgets($this->con, self::MAX_REPLY_LENGTH);
            $data .= $str;

            // If response is only 3 chars (not valid, but RFC5321 S4.2 says it must be handled),
            // or 4th character is a space or a line break char, we are done reading, break the loop.
            // String array access is a significant micro-optimisation over strlen
            if (!isset($str[3]) || $str[3] === ' ' || $str[3] === "\r" || $str[3] === "\n") {
                break;
            }

            $info = \stream_get_meta_data($this->con);
            if ($info['timed_out']) {
                break;
            }

            // Now check if reads took too long
            if ($endTime && \time() > $endTime) {
                break;
            }
        }

        return $data;
    }

    /**
     * Extract and return the ID of the last SMTP transaction
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function recordLastTransactionId() : string
    {
        $reply = $this->getLastReply();

        if ($reply === '') {
            $this->lastSmtpTransactionId = '';
        } else {
            $this->lastSmtpTransactionId = '';
            $patterns                    = SmtpTransactionPattern::getConstants();

            foreach ($patterns as $pattern) {
                $matches = [];
                if (\preg_match($pattern, $reply, $matches)) {
                    $this->lastSmtpTransactionId = \trim($matches[1]);
                    break;
                }
            }
        }

        return $this->lastSmtpTransactionId;
    }

    /**
     * Get the queue/transaction ID of the last SMTP transaction
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLastTransactionId() : string
    {
        return $this->lastSmtpTransactionId;
    }
}
