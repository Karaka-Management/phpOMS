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
 * @testdox phpOMS\tests\Localization\L11nManagerTest: Localization manager for view templates
 *
 * @internal
 */
class L11nManagerTest extends \PHPUnit\Framework\TestCase
{
    protected L11nManager $l11nManager;

    protected function setUp() : void
    {
        $this->l11nManager = new L11nManager('Api');
    }

    /**
     * @testdox The localization manager has the expected member variables
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testAttributes() : void
    {
        self::assertObjectHasAttribute('language', $this->l11nManager);
    }

    /**
     * @testdox The localization manager has the expected default values after initialization
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertFalse($this->l11nManager->isLanguageLoaded('en'));
        self::assertEquals([], $this->l11nManager->getModuleLanguage('en'));
        self::assertEquals([], $this->l11nManager->getModuleLanguage('en', 'Admin'));
        self::assertEquals('ERROR', $this->l11nManager->getHtml('en', 'Admin', 'Backend', 'Test2'));
        self::assertEquals('ERROR', $this->l11nManager->getText('en', 'Admin', 'Backend', 'Test2'));
    }

    /**
     * @testdox Loading language for an invalid module throws Exception
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
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

        $this->l11nManager->loadLanguage('en', 'doesNotExist', $expected);
    }

    /**
     * @testdox Language data can be loaded and output as plain text or html
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testLanguageInputOutput() : void
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

        $this->l11nManager->loadLanguage('en', 'Admin', $expected['en']);
        $this->l11nManager->loadLanguage('en', 'Admin', $expected2['en']);
        self::assertTrue($this->l11nManager->isLanguageLoaded('en'));

        self::assertEquals('Test strin&g2', $this->l11nManager->getText('en', 'Admin', 'RandomThemeDoesNotMatterAlreadyLoaded', 'Test2'));
        self::assertEquals('Test strin&amp;g2', $this->l11nManager->getHtml('en', 'Admin', 'RandomThemeDoesNotMatterAlreadyLoaded', 'Test2'));
    }

    /**
     * @testdox Language data can be loaded from a file
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testLanguageFile() : void
    {
        $this->l11nManager2 = new L11nManager('Api');
        $this->l11nManager2->loadLanguageFromFile('en', 'Test', __DIR__ . '/langTestFile.php');
        self::assertEquals('value', $this->l11nManager2->getHtml('en', 'Test', 'RandomThemeDoesNotMatterAlreadyLoaded', 'key'));

        self::assertEquals(['Test' => ['key' => 'value']], $this->l11nManager2->getModuleLanguage('en'));
        self::assertEquals(['key' => 'value'], $this->l11nManager2->getModuleLanguage('en', 'Test'));
    }
}
