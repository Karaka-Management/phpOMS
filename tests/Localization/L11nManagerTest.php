<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Localization;

use phpOMS\Localization\L11nManager;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class L11nManagerTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $l11nManager = new L11nManager('Api');
        self::assertObjectHasAttribute('language', $l11nManager);
    }

    public function testDefault() : void
    {
        $l11nManager = new L11nManager('Api');
        self::assertFalse($l11nManager->isLanguageLoaded('en'));
        self::assertEquals([], $l11nManager->getModuleLanguage('en'));
        self::assertEquals([], $l11nManager->getModuleLanguage('en', 'Admin'));
        self::assertEquals('ERROR', $l11nManager->getHtml('en', 'Admin', 'Backend', 'Test2'));
        self::assertEquals('ERROR', $l11nManager->getText('en', 'Admin', 'Backend', 'Test2'));
    }

    public function testInvalidModule() : void
    {
        self::expectException(\Exception::class);

        $expected = [
            'en' => [
                'Admin' => [
                    'Test' => 'Test string',
                ],
            ],
        ];

        $localization = new L11nManager('Api');
        $localization->loadLanguage('en', 'doesNotExist', $expected);
    }

    public function testGetSet() : void
    {
        $expected = [
            'en' => [
                'Admin' => [
                    'Test' => 'Test string',
                ],
            ],
        ];

        $expected2 = [
            'en' => [
                'Admin' => [
                    'Test2' => 'Test strin&g2',
                ],
            ],
        ];

        $l11nManager = new L11nManager('Api');
        $l11nManager->loadLanguage('en', 'Admin', $expected['en']);
        $l11nManager->loadLanguage('en', 'Admin', $expected2['en']);
        self::assertTrue($l11nManager->isLanguageLoaded('en'));

        self::assertEquals('Test strin&g2', $l11nManager->getText('en', 'Admin', 'RandomThemeDoesNotMatterAlreadyLoaded', 'Test2'));
        self::assertEquals('Test strin&amp;g2', $l11nManager->getHtml('en', 'Admin', 'RandomThemeDoesNotMatterAlreadyLoaded', 'Test2'));
    }

    public function testGetSetFromFile() : void
    {
        $l11nManager2 = new L11nManager('Api');
        $l11nManager2->loadLanguageFromFile('en', 'Test', __DIR__ . '/langTestFile.php');
        self::assertEquals('value', $l11nManager2->getHtml('en', 'Test', 'RandomThemeDoesNotMatterAlreadyLoaded', 'key'));

        self::assertEquals(['Test' => ['key' => 'value']], $l11nManager2->getModuleLanguage('en'));
        self::assertEquals(['key' => 'value'], $l11nManager2->getModuleLanguage('en', 'Test'));
    }
}
