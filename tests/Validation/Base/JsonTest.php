<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
 * @testdox phpOMS\tests\Validation\Base\JsonTest: Json validator
 *
 * @internal
 */
class JsonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A json string can be validated
     * @covers phpOMS\Validation\Base\Json
     * @group framework
     */
    public function testJson() : void
    {
        self::assertTrue(Json::isValid('{}'));
        self::assertFalse(Json::isValid('{'));
    }

    /**
     * @testdox A json string can be validated against a template definition
     * @covers phpOMS\Validation\Base\Json
     * @group framework
     */
    public function testJsonTemplate() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $valid = \json_decode(\file_get_contents(__DIR__ . '/json/valid.json'), true);
        self::assertTrue(Json::validateTemplate($template, $valid));
        self::assertTrue(Json::validateTemplate($template, $valid, true));
    }

    /**
     * @testdox A json string can be validated against a template definition with additional data
     * @covers phpOMS\Validation\Base\Json
     * @group framework
     */
    public function testJsonTemplateAdditional() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $additional = \json_decode(\file_get_contents(__DIR__ . '/json/additional.json'), true);
        self::assertTrue(Json::validateTemplate($template, $additional));
    }

    /**
     * @testdox A json string cannot be validated against a template definition with additional data if an exact match is enforced
     * @covers phpOMS\Validation\Base\Json
     * @group framework
     */
    public function testJsonTemplateInvalidAdditional() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $additional = \json_decode(\file_get_contents(__DIR__ . '/json/additional.json'), true);
        self::assertFalse(Json::validateTemplate($template, $additional, true));
    }

    /**
     * @testdox A json string cannot be validated against a template definition with missing data if an exact match is enforced
     * @covers phpOMS\Validation\Base\Json
     * @group framework
     */
    public function testJsonTemplateInvalidMissing() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $incomplete = \json_decode(\file_get_contents(__DIR__ . '/json/incomplete.json'), true);
        self::assertFalse(Json::validateTemplate($template, $incomplete));
    }

    /**
     * @testdox A json string cannot be validated against a template definition if it doesn't match the template
     * @covers phpOMS\Validation\Base\Json
     * @group framework
     */
    public function testInvalidJsonTemplate() : void
    {
        $template = \json_decode(\file_get_contents(__DIR__ . '/json/template.json'), true);

        $invalid = \json_decode(\file_get_contents(__DIR__ . '/json/invalid.json'), true);
        self::assertFalse(Json::validateTemplate($template, $invalid));
    }
}
