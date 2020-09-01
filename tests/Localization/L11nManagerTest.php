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
use phpOMS\Localization\Localization;

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

    /**
     * @testdox The numeric value can be printed based on the localization
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testGetNumeric() : void
    {
        $l11n = Localization::fromLanguage('en');
        self::assertEquals('1.23', $this->l11nManager->getNumeric($l11n, 1.2345, 'medium'));
        self::assertEquals('1.235', $this->l11nManager->getNumeric($l11n, 1.2345, 'long'));
        self::assertEquals('1,234.235', $this->l11nManager->getNumeric($l11n, 1234.2345, 'long'));
    }

    /**
     * @testdox The percentage value can be printed based on the localization
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testGetPercentage() : void
    {
        $l11n = Localization::fromLanguage('en');
        self::assertEquals('1.23%', $this->l11nManager->getPercentage($l11n, 1.2345, 'medium'));
        self::assertEquals('1.235%', $this->l11nManager->getPercentage($l11n, 1.2345, 'long'));
    }

    /**
     * @testdox The currency value can be printed based on the localization
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testGetCurrency() : void
    {
        $l11n = Localization::fromLanguage('en');
        self::assertEquals('USD 1.23', $this->l11nManager->getCurrency($l11n, 1.2345, 'medium'));
        self::assertEquals('USD 1.235', $this->l11nManager->getCurrency($l11n, 1.2345, 'long'));

        $this->l11nManager->loadLanguage('en', '0', ['0' => ['CurrencyK' => 'K']]);
        $this->l11nManager->loadLanguage('en', '0', ['0' => ['CurrencyM' => 'M']]);
        $this->l11nManager->loadLanguage('en', '0', ['0' => ['CurrencyB' => 'B']]);
        self::assertEquals('K$ 12.345', $this->l11nManager->getCurrency($l11n, 12345.0, 'long', '$', 1000));
        self::assertEquals('KUSD 12.345', $this->l11nManager->getCurrency($l11n, 12345.0, 'long', null, 1000));
        self::assertEquals('M$ 123.5', $this->l11nManager->getCurrency($l11n, 123456789.0, 'short', '$', 1000000));
        self::assertEquals('B$ 1.2', $this->l11nManager->getCurrency($l11n, 1234567890.0, 'short', '$', 1000000000));
    }

    /**
     * @testdox The datetime value can be printed based on the localization
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testGetDateTime() : void
    {
        $l11n = Localization::fromLanguage('en');

        $date = new \DateTime('2020-01-01 13:45:22');
        self::assertEquals('2020.01.01', $this->l11nManager->getDateTime($l11n, $date, 'medium'));
        self::assertEquals('2020.01.01 01:45', $this->l11nManager->getDateTime($l11n, $date, 'long'));
    }

    /**
     * @testdox Loading language for an invalid module throws Exception
     * @covers phpOMS\Localization\L11nManager
     * @group framework
     */
    public function testInvalidModule() : void
    {
        $this->expectException(\Exception::class);

        $expected = [
            'en' => [
                'Admin' => [
                    'Test' => 'Test string',
                ],
            ],
        ];

        $this->l11nManager->loadLanguage('en', 'doesNotExist', $expected);
    }
}
