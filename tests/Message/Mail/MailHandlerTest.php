<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Mail;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Mail\MailHandler;

/**
 * @testdox phpOMS\tests\Message\MailHandlerTest: Abstract mail handler
 *
 * @internal
 */
final class MailHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected MailHandler $handler;

    public static function setUpBeforeClass() : void
    {
        $pk = \openssl_pkey_new(
            [
                'private_key_bits' => 2048,
                'private_key_type' => \OPENSSL_KEYTYPE_RSA,
            ]
        );
        \openssl_pkey_export_to_file($pk, __DIR__ . '/dkim.pem');

        $password = 'password';
        $dn       = [
            'countryName'            => 'DE',
            'stateOrProvinceName'    => 'Hesse',
            'localityName'           => 'Frankfurt',
            'organizationName'       => 'Karaka',
            'organizationalUnitName' => 'Framework',
            'commonName'             => 'Karaka Test',
            'emailAddress'           => 'test@karaka.email',
        ];
        $keyconfig = [
            'digest_alg'       => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => \OPENSSL_KEYTYPE_RSA,
        ];

        $pk   = \openssl_pkey_new($keyconfig);
        $csr  = \openssl_csr_new($dn, $pk);
        $cert = \openssl_csr_sign($csr, null, $pk, 1);
        \openssl_x509_export($cert, $certout);
        \file_put_contents(__DIR__ . '/cert.pem', $certout);
        \openssl_pkey_export($pk, $pkeyout, $password);
        \file_put_contents(__DIR__ . '/key.pem', $pkeyout);
    }

    public static function tearDownAfterClass() : void
    {
        if (\is_file(__DIR__ . '/dkim.pem')) {
            \unlink(__DIR__ . '/dkim.pem');
        }

        if (\is_file(__DIR__ . '/cert.pem')) {
            \unlink(__DIR__ . '/cert.pem');
        }

        if (\is_file(__DIR__ . '/key.pem')) {
            \unlink(__DIR__ . '/key.pem');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->handler = new MailHandler();
    }

    use MailHandlerMailTrait;
    use MailHandlerSendmailTrait;
    use MailHandlerSmtpTrait;
}
