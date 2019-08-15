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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Text;

/**
 * @internal
 */
class TextTest extends \PHPUnit\Framework\TestCase
{
    public function testRnG() : void
    {
        $text = new Text(true, true);

        self::assertEquals('', $text->generateText(0));

        self::assertNotEquals(
            $text->generateText(300),
            $text->generateText(300)
        );

        self::assertGreaterThan(0, $text->getSentences());
    }
}
