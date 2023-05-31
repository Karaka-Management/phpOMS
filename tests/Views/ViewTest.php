<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Views;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Uri\HttpUri;
use phpOMS\Views\View;
use phpOMS\Views\ViewAbstract;

/**
 * @testdox phpOMS\tests\Views\ViewTest: View for response rendering
 *
 * @internal
 */
final class ViewTest extends \PHPUnit\Framework\TestCase
{
    protected DatabasePool $dbPool;

    protected ApplicationAbstract $app;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->dbPool = new DatabasePool();
        /** @var array $CONFIG */
        $this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']);

        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->l11nManager = new L11nManager();
        $this->app->dbPool      = $this->dbPool;
    }

    /**
     * @testdox The view has the expected default values after initialization
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testDefault() : void
    {
        $view = new View($this->app->l11nManager);

        self::assertEmpty($view->getTemplate());
        self::assertEmpty($view->getViews());
        self::assertIsArray($view->getViews());
        self::assertFalse($view->hasData('0'));
        self::assertFalse($view->getView('0'));
        self::assertFalse($view->removeView('0'));
        self::assertNull($view->getData('0'));
        self::assertFalse($view->removeData('0'));
        self::assertEmpty($view->toArray());
    }

    /**
     * @testdox The view data can be checked for existence
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testHasData() : void
    {
        $view            = new View($this->app->l11nManager);
        $view->data['a'] = 1;

        self::assertTrue($view->hasData('a'));
        self::assertFalse($view->hasData('b'));
    }

    /**
     * @testdox The view can output text from the localization manager
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetText() : void
    {
        $view = new View($this->app->l11nManager);
        $view->setTemplate('/Modules/Admin/Theme/Backend/accounts-list');

        $expected = [
            'en' => [
                'Admin' => [
                    'Test' => '<a href="test">Test</a>',
                ],
            ],
        ];

        $this->app->l11nManager = new L11nManager('Api');
        $this->app->l11nManager->loadLanguage('en', 'Admin', $expected['en']);

        self::assertEquals('<a href="test">Test</a>', $view->getText('Test'));
    }

    /**
     * @testdox The view can output html escaped text from the localization manager
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetHtml() : void
    {
        $view = new View($this->app->l11nManager);
        $view->setTemplate('/Modules/Admin/Theme/Backend/accounts-list');

        $expected = [
            'en' => [
                'Admin' => [
                    'Test' => '<a href="test">Test</a>',
                ],
            ],
        ];

        $this->app->l11nManager = new L11nManager('Api');
        $this->app->l11nManager->loadLanguage('en', 'Admin', $expected['en']);

        self::assertEquals('&lt;a href=&quot;test&quot;&gt;Test&lt;/a&gt;', $view->getHtml('Test'));
    }

    /**
     * @testdox The numeric value can be printed based on the localization
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetNumeric() : void
    {
        $view = new View($this->app->l11nManager, null, new HttpResponse(Localization::fromLanguage('en')));
        self::assertEquals('1.23', $view->getNumeric(1.2345, 'medium'));
        self::assertEquals('1.235', $view->getNumeric(1.2345, 'long'));
        self::assertEquals('1,234.235', $view->getNumeric(1234.2345, 'long'));
    }

    /**
     * @testdox The percentage value can be printed based on the localization
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetPercentage() : void
    {
        $view = new View($this->app->l11nManager, null, new HttpResponse(Localization::fromLanguage('en')));
        self::assertEquals('1.23%', $view->getPercentage(1.2345, 'medium'));
        self::assertEquals('1.235%', $view->getPercentage(1.2345, 'long'));
    }

    /**
     * @testdox The currency value can be printed based on the localization
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetCurrency() : void
    {
        $view = new View($this->app->l11nManager, null, new HttpResponse(Localization::fromLanguage('en')));
        self::assertEquals('USD 1.23', $view->getCurrency(1.2345, 'USD'));
        self::assertEquals('USD 1.235', $view->getCurrency(1.2345, 'USD'));

        $this->app->l11nManager->loadLanguage('en', '0', ['0' => ['CurrencyK' => 'K']]);
        self::assertEquals('K$ 12.345', $view->getCurrency(12345.0, 'long', '$', 1000));
        self::assertEquals('KUSD 12.345', $view->getCurrency(12345.0, 'long', null, 1000));
    }

    /**
     * @testdox The datetime value can be printed based on the localization
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetDateTime() : void
    {
        $view = new View($this->app->l11nManager, null, new HttpResponse(Localization::fromLanguage('en')));

        $date = new \DateTime('2020-01-01 13:45:22');
        self::assertEquals('2020.01.01', $view->getDateTime($date, 'medium'));
        self::assertEquals('2020.01.01 01:45', $view->getDateTime($date, 'long'));
    }

    /**
     * @testdox View data can be set and returned
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testDataInputOutput() : void
    {
        $view = new View($this->app->l11nManager);

        $view->data['key'] = 'value';
        self::assertEquals('value', $view->getData('key'));
    }

    /**
     * @testdox View data can be added and returned
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testDataAdd() : void
    {
        $view = new View($this->app->l11nManager);

        self::assertTrue($view->data['key2'] = 'valu2');
        self::assertEquals('valu2', $view->getData('key2'));
    }

    /**
     * @testdox View data cannot be added if it already exists
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testInvalidDataOverwrite() : void
    {
        $view = new View($this->app->l11nManager);

        $view->data['key2'] = 'valu2';
        self::assertFalse($view->data['key2'] = 'valu3');
        self::assertEquals('valu2', $view->getData('key2'));
    }

    /**
     * @testdox View data can be removed
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testRemove() : void
    {
        $view = new View($this->app->l11nManager);

        $view->data['key2'] = 'valu2';
        self::assertTrue($view->removeData('key2'));
    }

    /**
     * @testdox None-existing view data cannot be removed
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testInvalidDataRemove() : void
    {
        $view = new View($this->app->l11nManager);

        self::assertFalse($view->removeData('key3'));
    }

    /**
     * @testdox The request can be returned
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetRequest() : void
    {
        $view = new View($this->app->l11nManager, $request = new HttpRequest(new HttpUri('')), $response = new HttpResponse());

        self::assertEquals($request, $view->request);
        self::assertEquals($response, $view->response);
    }

    /**
     * @testdox The response can be returned
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testGetResponse() : void
    {
        $view = new View($this->app->l11nManager, new HttpRequest(new HttpUri('')), $response = new HttpResponse());

        self::assertEquals($response, $view->response);
    }

    /**
     * @testdox Text can be html escaped
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testPrintHtml() : void
    {
        $view = new View($this->app->l11nManager, $request = new HttpRequest(new HttpUri('')), $response = new HttpResponse());

        self::assertEquals('&lt;a href=&quot;test&quot;&gt;Test&lt;/a&gt;', $view->printHtml('<a href="test">Test</a>'));
        self::assertEquals('&lt;a href=&quot;test&quot;&gt;Test&lt;/a&gt;', ViewAbstract::html('<a href="test">Test</a>'));
    }

    /**
     * @testdox Views can be added and returned from a view
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testViewInputOutput() : void
    {
        $view = new View($this->app->l11nManager);

        $tView = new View($this->app->l11nManager);
        self::assertTrue($view->addView('test', $tView));
        self::assertEquals($tView, $view->getView('test'));
        self::assertCount(1, $view->getViews());
    }

    /**
     * @testdox None-existing views cannot be returned
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testInvalidViewGet() : void
    {
        $view = new View($this->app->l11nManager);

        self::assertFalse($view->getView('test'));
    }

    /**
     * @testdox Views can be removed
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testViewRemove() : void
    {
        $view = new View($this->app->l11nManager);

        $tView = new View($this->app->l11nManager);
        $view->addView('test', $tView);
        self::assertTrue($view->removeView('test'));
    }

    /**
     * @testdox None-existing views cannot be removed
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testInvalidViewRemove() : void
    {
        $view = new View($this->app->l11nManager);

        self::assertFalse($view->removeView('test'));
    }

    /**
     * @testdox A view can be forcefully overwritten
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testOverwritingView() : void
    {
        $view  = new View();
        $tView = new View();

        $view->addView('test', $tView);
        self::assertTrue($view->addView('test', $tView, true));
    }

    /**
     * @testdox By default a view is not overwritten
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testInvalidOverwritingView() : void
    {
        $view  = new View();
        $tView = new View();

        $view->addView('test', $tView);
        self::assertFalse($view->addView('test', $tView));
    }

    /**
     * @testdox A view template can be rendered
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testRender() : void
    {
        $view = new View();

        $view->setTemplate('/phpOMS/tests/Views/testTemplate');
        self::assertEquals('<strong>Test</strong>', $view->render());
    }

    /**
     * @testdox A view template can be build
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testBuild() : void
    {
        $view = new View();

        $view->setTemplate('/phpOMS/tests/Views/testReturnTemplate');
        self::assertEquals('<strong>Test</strong>', $view->build());
    }

    /**
     * @testdox A view template can be serialized
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testSerialize() : void
    {
        $view = new View();
        self::assertEquals('[]', $view->serialize());

        $view->setTemplate('/phpOMS/tests/Views/testTemplate');
        self::assertEquals('<strong>Test</strong>', $view->serialize());
    }

    /**
     * @testdox A view can be turned into an array containing the rendered templates
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testArray() : void
    {
        $view = new View();
        self::assertEquals([], $view->toArray());

        $view->setTemplate('/phpOMS/tests/Views/testTemplate');

        $view2 = new View();
        $view2->setTemplate('/phpOMS/tests/Views/testTemplate');

        $view->addView('sub', $view2);
        self::assertEquals([
                0     => '<strong>Test</strong>',
                'sub' => ['<strong>Test</strong>'],
            ],
            $view->toArray()
        );
    }

    /**
     * @testdox Rendering a invalid template throws a PathException
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testRenderException() : void
    {
        $view = new View($this->app->l11nManager);
        $view->setTemplate('something.txt');

        self::assertEquals('', $view->render());
    }

    /**
     * @testdox Building a invalid template throws a PathException
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testBuildException() : void
    {
        $view = new View($this->app->l11nManager);
        $view->setTemplate('something.txt');

        self::assertEquals('', $view->build());
    }

    /**
     * @testdox Serializing a invalid template throws a PathException
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testSerializeException() : void
    {
        $view = new View($this->app->l11nManager);
        $view->setTemplate('something.txt');

        self::assertEquals('', $view->serialize());
    }

    /**
     * @testdox Getting the text without defining a module throws a InvalidModuleException exception
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testTextWithoutModuleAndTemplate() : void
    {
        $this->expectException(\phpOMS\Module\Exception\InvalidModuleException::class);

        $view = new View($this->app->l11nManager);
        $view->getText('InvalidText');
    }

    /**
     * @testdox Getting the text with an invalid template path throws a InvalidModuleException exception
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testTextFromInvalidTemplatePath() : void
    {
        $this->expectException(\phpOMS\Module\Exception\InvalidModuleException::class);

        $view = new View($this->app->l11nManager);
        $view->setTemplate('/Modules/ABC');
        $view->getText('InvalidText');
    }

    /**
     * @testdox Getting the text without defining a template throws a InvalidThemeException exception
     * @covers phpOMS\Views\View<extended>
     * @group framework
     */
    public function testTextInvalidTemplate() : void
    {
        $this->expectException(\phpOMS\Module\Exception\InvalidThemeException::class);

        $view = new View($this->app->l11nManager);
        $view->getText('InvalidText', 'Admin');
    }
}
