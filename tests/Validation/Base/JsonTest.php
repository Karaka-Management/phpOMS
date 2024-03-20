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

namespace phpOMS\tests\Validation\Base;

use phpOMS\Validation\Base\Json;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Validation\Base\Json::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Validation\Base\JsonTest: Json validator')]
final class JsonTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A json string can be validated')]
    public function testJson() : void
    {
        self::assertTrue(Json::isValid('{}'));
        self::assertFalse(Json::isValid('{'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A json string can be validated against a template definition')]
    public function testJsonTemplate() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $valid = \json_decode(\file_get_contents(__DIR__ . '/json/valid.json'), true);
        self::assertTrue(Json::validateTemplate($template, $valid));
        self::assertTrue(Json::validateTemplate($template, $valid, true));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A json string can be validated against a template definition with additional data')]
    public function testJsonTemplateAdditional() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $additional = \json_decode(\file_get_contents(__DIR__ . '/json/additional.json'), true);
        self::assertTrue(Json::validateTemplate($template, $additional));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A json string cannot be validated against a template definition with additional data if an exact match is enforced')]
    public function testJsonTemplateInvalidAdditional() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $additional = \json_decode(\file_get_contents(__DIR__ . '/json/additional.json'), true);
        self::assertFalse(Json::validateTemplate($template, $additional, true));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A json string cannot be validated against a template definition with missing data if an exact match is enforced')]
    public function testJsonTemplateInvalidMissing() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $incomplete = \json_decode(\file_get_contents(__DIR__ . '/json/incomplete.json'), true);
        self::assertFalse(Json::validateTemplate($template, $incomplete));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A json string cannot be validated against a template definition if it doesn't match the template")]
    public function testInvalidJsonTemplate() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $invalid = \json_decode(\file_get_contents(__DIR__ . '/json/invalid.json'), true);
        self::assertFalse(Json::validateTemplate($template, $invalid));
    }
}
