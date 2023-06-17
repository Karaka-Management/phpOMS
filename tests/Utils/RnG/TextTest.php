<?php
/**
 * Jingga
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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Text;

/**
 * @testdox phpOMS\tests\Utils\RnG\TextTest: Random text generator
 *
 * @internal
 */
final class TextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Random text can be generated
     * @covers phpOMS\Utils\RnG\Text
     * @group framework
     */
    public function testRnG() : void
    {
        $text = new Text(true, true);

        self::assertEquals('', $text->generateText(0));

        self::assertNotEquals(
            $text->generateText(300),
            $text->generateText(300)
        );
    }
}
