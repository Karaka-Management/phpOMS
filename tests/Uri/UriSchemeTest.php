<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\UriScheme;

/**
 * @internal
 */
final class UriSchemeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::HTTP'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::FILE'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::MAILTO'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::FTP'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::HTTPS'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::IRC'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::TEL'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::TELNET'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::SSH'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::SKYPE'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::SSL'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::NFS'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::GEO'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::MARKET'));
        self::assertTrue(\defined('phpOMS\Uri\UriScheme::ITMS'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumUnique() : void
    {
        $values = UriScheme::getConstants();
        self::assertEquals(\count($values), \array_sum(\array_count_values($values)));
    }
}
