<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Localization\LanguageDetection;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\LanguageDetection\Language;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\LanguageDetection\LanguageTest: Language detection')]
final class LanguageTest extends \PHPUnit\Framework\TestCase
{
    public function testDetection() : void
    {
        $detector = new Language();

        $files = \scandir(__DIR__ . '/languages');
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $language = \explode('.', $file)[0];
            $content  = \file_get_contents(__DIR__ . '/languages/' . $file);
            $detected = $detector->detect($content)->bestResults()->close();

            self::assertEquals($language, \array_keys($detected)[0] ?? '');
        }
    }
}
