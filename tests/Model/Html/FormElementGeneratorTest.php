<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Model\Html;

use phpOMS\Model\Html\FormElementGenerator;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Model\Html\FormElementGenerator::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Model\Html\FormElementGeneratorTest: Form element generator')]
final class FormElementGeneratorTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A text input element can be generated')]
    public function testGenerateTextInput() : void
    {
        $element = [
            'type'       => 'input',
            'subtype'    => 'text',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
                'type' => 'text',
            ],
            'default' => [
                'value' => 'testValue',
            ],
        ];

        self::assertEquals(
            '<input id="testId" name="testName" type="text" value="testValue">',
            FormElementGenerator::generate($element)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A text input element can be generated with a custom value')]
    public function testGenerateTextInputWithValue() : void
    {
        $element = [
            'type'       => 'input',
            'subtype'    => 'text',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
                'type' => 'text',
            ],
            'default' => [
                'value' => 'testValue',
            ],
        ];

        self::assertEquals(
            '<input id="testId" name="testName" type="text" value="manualValue">',
            FormElementGenerator::generate($element, 'manualValue')
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A datetime input element can be generated with custom formatting')]
    public function testGenerateDateTimeInput() : void
    {
        $element = [
            'type'       => 'input',
            'subtype'    => 'datetime',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
                'type' => 'datetime',
            ],
            'default' => [
                "value"  => "2019-02-03 01:23",
                "format" => "Y-m-d",
            ],
        ];

        self::assertEquals(
            '<input id="testId" name="testName" type="datetime" value="2019-02-03">',
            FormElementGenerator::generate($element)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A checkbox element can be generated')]
    public function testGenerateCheckboxInput() : void
    {
        $element = [
            'type'       => 'input',
            'subtype'    => 'checkbox',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
                'type' => 'checkbox',
            ],
            'default' => [
                'value'   => 'testValue',
                'checked' => true,
                'content' => 'testContent',
            ],
        ];

        self::assertEquals(
            '<input id="testId" name="testName" type="checkbox" value="testValue" checked><label for="testId">testContent</label>',
            FormElementGenerator::generate($element)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A checkbox element can be generated with a localized label element')]
    public function testGenerateCheckboxWithLanguageInput() : void
    {
        $element = [
            'type'       => 'input',
            'subtype'    => 'checkbox',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
                'type' => 'checkbox',
            ],
            'default' => [
                'value'   => 'testValue',
                'checked' => false,
                'content' => 'testContent',
            ],
        ];

        self::assertEquals(
            '<input id="testId" name="testName" type="checkbox" value="testValue"><label for="testId">langContent</label>',
            FormElementGenerator::generate($element, null, ['testContent' => 'langContent'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A radio element can be generated')]
    public function testGenerateRadioInput() : void
    {
        $element = [
            'type'       => 'input',
            'subtype'    => 'radio',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
                'type' => 'radio',
            ],
            'default' => [
                'value'   => 'testValue',
                'checked' => true,
                'content' => 'testContent',
            ],
        ];

        self::assertEquals(
            '<input id="testId" name="testName" type="radio" value="testValue" checked><label for="testId">testContent</label>',
            FormElementGenerator::generate($element)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A radio element can be generated with a localized label element')]
    public function testGenerateRadioWithLanguageInput() : void
    {
        $element = [
            'type'       => 'input',
            'subtype'    => 'radio',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
                'type' => 'radio',
            ],
            'default' => [
                'value'   => 'testValue',
                'checked' => false,
                'content' => 'testContent',
            ],
        ];

        self::assertEquals(
            '<input id="testId" name="testName" type="radio" value="testValue"><label for="testId">langContent</label>',
            FormElementGenerator::generate($element, null, ['testContent' => 'langContent'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A label element can be generated')]
    public function testGenerateLabel() : void
    {
        $element = [
            'type'       => 'label',
            'attributes' => [
                'for' => 'testId',
            ],
            'default' => [
                'value' => 'testValue',
            ],
        ];

        self::assertEquals(
            '<label for="testId">testValue</label>',
            FormElementGenerator::generate($element)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A localized label element can be generated')]
    public function testGenerateWithLanguageLabel() : void
    {
        $element = [
            'type'       => 'label',
            'attributes' => [
                'for' => 'testId',
            ],
            'default' => [
                'value' => 'testValue',
            ],
        ];

        self::assertEquals(
            '<label for="testId">langValue</label>',
            FormElementGenerator::generate($element, null, ['testValue' => 'langValue'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A textarea element can be generated')]
    public function testGenerateTextarea() : void
    {
        $element = [
            'type'       => 'textarea',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
            ],
            'default' => [
                'value' => 'testValue',
            ],
        ];

        self::assertEquals(
            '<textarea id="testId" name="testName">testValue</textarea>',
            FormElementGenerator::generate($element)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A textarea element can be generated with a custom value')]
    public function testGenerateWithValueTextarea() : void
    {
        $element = [
            'type'       => 'textarea',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
            ],
            'default' => [
                'value' => 'testValue',
            ],
        ];

        self::assertEquals(
            '<textarea id="testId" name="testName">manualValue</textarea>',
            FormElementGenerator::generate($element, 'manualValue')
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A select element can be generated')]
    public function testGenerateSelect() : void
    {
        $element = [
            'type'       => 'select',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
            ],
            'options' => [
                'option1' => 'value1',
                'option2' => 'value2',
                'option3' => 'value3',
            ],
            'default' => [
                'value' => 'option2',
            ],
        ];

        self::assertEquals(
            '<select id="testId" name="testName"><option value="option1">value1</option><option value="option2" selected>value2</option><option value="option3">value3</option></select>',
            FormElementGenerator::generate($element)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A localized select element can be generated')]
    public function testGenerateWithLanguageSelect() : void
    {
        $element = [
            'type'       => 'select',
            'attributes' => [
                'id'   => 'testId',
                'name' => 'testName',
            ],
            'options' => [
                'option1' => 'value1',
                'option2' => 'value2',
                'option3' => 'value3',
            ],
            'default' => [
                'value' => 'option2',
            ],
        ];

        self::assertEquals(
            '<select id="testId" name="testName"><option value="option1">value1</option><option value="option2" selected>lang2</option><option value="option3">value3</option></select>',
            FormElementGenerator::generate($element, null, ['value2' => 'lang2'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A missing or invalid element type generates a INVALID output')]
    public function testInvalidElementType() : void
    {
        self::assertEquals(
            'INVALID',
            FormElementGenerator::generate([])
        );

        self::assertEquals(
            'INVALID',
            FormElementGenerator::generate(['type' => 'somethingInvalid'])
        );
    }
}
