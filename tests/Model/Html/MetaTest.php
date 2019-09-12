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

namespace phpOMS\tests\Model\Html;

use phpOMS\Model\Html\Meta;

/**
 * @internal
 */
class MetaTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $meta = new Meta();
        self::assertEquals('', $meta->getDescription());
        self::assertEquals('', $meta->getCharset());
        self::assertEquals('', $meta->getAuthor());
        self::assertEquals([], $meta->getKeywords());
        self::assertEquals('<meta name="generator" content="Orange Management">', $meta->render());
    }

    public function testGetSet() : void
    {
        $meta = new Meta();

        $meta->addKeyword('orange');
        self::assertEquals(['orange'], $meta->getKeywords());

        $meta->setAuthor('oms');
        self::assertEquals('oms', $meta->getAuthor());

        $meta->setCharset('utf-8');
        self::assertEquals('utf-8', $meta->getCharset());

        $meta->setDescription('some description');
        self::assertEquals('some description', $meta->getDescription());

        $meta->setProperty('og:title', 'TestProperty');
        $meta->setItemprop('title', 'TestItemprop');
        $meta->setName('title', 'TestName');

        self::assertEquals(
            '<meta name="keywords" content="orange">'
            . '<meta name="author" content="oms">'
            . '<meta name="description" content="some description">'
            . '<meta charset="utf-8">'
            . '<meta name="generator" content="Orange Management">'
            . '<meta property="og:title" content="TestProperty">'
            . '<meta itemprop="title" content="TestItemprop">'
            . '<meta name="title" content="TestName">',
            $meta->render()
        );
    }
}
