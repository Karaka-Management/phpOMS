<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Views;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\ApplicationAbstract;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\Response;
use phpOMS\Uri\Http;
use phpOMS\Views\View;
use phpOMS\Views\ViewAbstract;

/**
 * @internal
 */
class ViewTest extends \PHPUnit\Framework\TestCase
{
    protected $dbPool = null;

    protected $app = null;

    protected function setUp() : void
    {
        $this->dbPool = new DatabasePool();
        /** @var array $CONFIG */
        $this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']);

        $this->app = new class() extends ApplicationAbstract
        {
            protected $appName = 'Api';
        };

        $this->app->l11nManager = new L11nManager($this->app->appName);
        $this->app->dbPool      = $this->dbPool;
    }

    public function testDefault() : void
    {
        $view = new View($this->app, new Request(new Http('')), new Response(new Localization()));

        self::assertEmpty($view->getTemplate());
        self::assertEmpty($view->getViews());
        self::assertIsArray($view->getViews());
        self::assertFalse($view->getView('0'));
        self::assertFalse($view->removeView('0'));
        self::assertNull($view->getData('0'));
        self::assertFalse($view->removeData('0'));
        self::assertEmpty($view->toArray());
    }

    public function testGetText() : void
    {
        $view = new View($this->app, $request = new Request(new Http('')), $response = new Response());
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
        self::assertEquals('&lt;a href=&quot;test&quot;&gt;Test&lt;/a&gt;', $view->getHtml('Test'));
    }

    public function testGetSet() : void
    {
        $view = new View($this->app, $request = new Request(new Http('')), $response = new Response());

        $view->setData('key', 'value');
        self::assertEquals('value', $view->getData('key'));

        self::assertTrue($view->addData('key2', 'valu2'));
        self::assertFalse($view->addData('key2', 'valu3'));
        self::assertEquals('valu2', $view->getData('key2'));

        self::assertTrue($view->removeData('key2'));
        self::assertFalse($view->removeData('key3'));

        self::assertEquals($request, $view->getRequest());
        self::assertEquals($response, $view->getResponse());

        self::assertEquals('&lt;a href=&quot;test&quot;&gt;Test&lt;/a&gt;', $view->printHtml('<a href="test">Test</a>'));
        self::assertEquals('&lt;a href=&quot;test&quot;&gt;Test&lt;/a&gt;', ViewAbstract::html('<a href="test">Test</a>'));

        $tView = new View($this->app, $request, $response);
        self::assertTrue($view->addView('test', $tView));
        self::assertEquals($tView, $view->getView('test'));
        self::assertCount(1, $view->getViews());
        self::assertTrue($view->removeView('test'));
        self::assertFalse($view->removeView('test'));
        self::assertFalse($view->getView('test'));
    }

    public function testOverwritingView() : void
    {
        $view  = new View();
        $tView = new View();

        self::assertTrue($view->addView('test', $tView));
        self::assertTrue($view->addView('test', $tView, 0, true));
        self::assertFalse($view->addView('test', $tView));
        self::assertFalse($view->addView('test', $tView, 0, false));
    }

    public function testRender() : void
    {
        $view = new View();

        $view->setTemplate('/phpOMS/tests/Views/testTemplate');
        self::assertEquals('<strong>Test</strong>', $view->render());
    }

    public function testSerialize() : void
    {
        $view = new View();
        self::assertEquals('[]', $view->serialize());

        $view->setTemplate('/phpOMS/tests/Views/testTemplate');
        self::assertEquals('<strong>Test</strong>', $view->serialize());
    }

    public function testArray() : void
    {
        $view = new View();
        self::assertEquals([], $view->toArray());

        $view->setTemplate('/phpOMS/tests/Views/testTemplate');

        $view2 = new View();
        $view2->setTemplate('/phpOMS/tests/Views/testTemplate');

        $view->addView('sub', $view2, 1);
        self::assertEquals([
                0 => '<strong>Test</strong>',
                'sub' => ['<strong>Test</strong>'],
            ],
            $view->toArray()
        );
    }

    public function testRenderException() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        $view = new View($this->app);
        $view->setTemplate('something.txt');

        $view->render();
    }

    public function testSerializeException() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        $view = new View($this->app);
        $view->setTemplate('something.txt');

        $view->serialize();
    }
}
