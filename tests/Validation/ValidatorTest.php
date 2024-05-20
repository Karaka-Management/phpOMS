<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Validation;

use phpOMS\Log\FileLogger;
use phpOMS\Validation\Validator;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Validation\Validator::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Validation\ValidatorTest: General validator')]
final class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A string can be checked if it contains a substring')]
    public function testValidationContains() : void
    {
        self::assertTrue(Validator::contains('Test string contains something.', 'contains'));
        self::assertFalse(Validator::contains('Test string contains something.', 'contains2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A string can be checked if it has a certain length')]
    public function testValidationLength() : void
    {
        self::assertTrue(Validator::hasLength('Test string contains something.'));
        self::assertTrue(Validator::hasLength('Test string contains something.', 10, 100));
        self::assertFalse(Validator::hasLength('Test string contains something.', 100, 1000));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A value can be checked if it is in range')]
    public function testValidationLimit() : void
    {
        self::assertTrue(Validator::hasLimit(1.23, 1.0, 2.0));
        self::assertTrue(Validator::hasLimit(1, 0, 2));
        self::assertFalse(Validator::hasLimit(3.0, 0, 2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A value can be checked to be of a defined type')]
    public function testValidationType() : void
    {
        self::assertTrue(Validator::isType(new FileLogger(__DIR__), '\phpOMS\Log\FileLogger'));
        self::assertFalse(Validator::isType(new FileLogger(__DIR__), '\some\namespace'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The error message and error code have the expected default values')]
    public function testValidationError() : void
    {
        Validator::resetError();
        self::assertEquals('', Validator::getMessage());
        self::assertEquals(0, Validator::getErrorCode());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Custom validators can be specified in order to validate a value')]
    public function testValidators() : void
    {
        self::assertTrue(Validator::isValid('testVar'));
        self::assertTrue(Validator::isValid('value', ['\is_string' => []]));
        self::assertFalse(Validator::isValid('value', ['\is_stringNot' => []]));
        self::assertTrue(Validator::isValid('value', ['phpOMS\Validation\Validator::hasLength' => [4]]));
    }

    public function testInvalidValidatorCall() : void
    {
        $this->expectException(\BadFunctionCallException::class);
        Validator::isValid('value', ['\invalid_call' => []]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A value can be checked to match a regular expression')]
    public function testMatching() : void
    {
        self::assertTrue(Validator::matches('ThisTestVar', '/.*/'));
        self::assertFalse(Validator::matches('ThisTestVar', '/.*\d+/'));
        self::assertTrue(Validator::matches('ThisTestVar', '/TestVar/'));
        self::assertFalse(Validator::matches('ThisTestVar', '/ThisTest$/'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testErrorMessage() : void
    {
        self::assertEquals('', Validator::getMessage());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testErrorCode() : void
    {
        self::assertEquals(0, Validator::getErrorCode());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testResetError() : void
    {
        Validator::resetError();
        self::assertEquals('', Validator::getMessage());
        self::assertEquals(0, Validator::getErrorCode());
    }
}
