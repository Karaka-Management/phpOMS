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

namespace phpOMS\tests\Validation\Base;

use phpOMS\Validation\Base\Json;

/**
 * @internal
 */
class JsonTest extends \PHPUnit\Framework\TestCase
{
    public function testJson() : void
    {
        self::assertTrue(Json::isValid('{}'));
        self::assertFalse(Json::isValid('{'));
    }

    public function testJsonTemplate() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $valid = \json_decode(\file_get_contents(__DIR__ . '/json/valid.json'), true);
        self::assertTrue(Json::validateTemplate($template, $valid));
        self::assertTrue(Json::validateTemplate($template, $valid, true));

        $additional = \json_decode(\file_get_contents(__DIR__ . '/json/additional.json'), true);
        self::assertTrue(Json::validateTemplate($template, $additional));
        self::assertFalse(Json::validateTemplate($template, $additional, true));

        $incomplete = \json_decode(\file_get_contents(__DIR__ . '/json/incomplete.json'), true);
        self::assertFalse(Json::validateTemplate($template, $incomplete));

        $invalid = \json_decode(\file_get_contents(__DIR__ . '/json/invalid.json'), true);
        self::assertFalse(Json::validateTemplate($template, $invalid));
    }
}
