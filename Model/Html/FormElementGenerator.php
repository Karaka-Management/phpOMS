<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Model\Html
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Html;

use phpOMS\Stdlib\Base\SmartDateTime;

/**
 * Form element generator class.
 *
 * @package phpOMS\Model\Html
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class FormElementGenerator
{
    /**
     * Generate a form element from a json object
     *
     * @param array    $json  Json object representing the form element
     * @param mixed    $value Null means the default value in the json array will be used
     * @param string[] $lang  Language array
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function generate(array $json, mixed $value = null, array $lang = []) : string
    {
        if (!isset($json['type'])) {
            return 'INVALID';
        }

        if ($json['type'] === 'select') {
            return self::generateSelect($json, $value, $lang);
        } elseif ($json['type'] === 'input') {
            return self::generateInput($json, $value, $lang);
        } elseif ($json['type'] === 'label') {
            return self::generateLabel($json, $lang);
        } elseif ($json['type'] === 'textarea') {
            return self::generateTextarea($json, $value);
        }

        return 'INVALID';
    }

    /**
     * Generate a form element from a json object
     *
     * @param array    $json  Json object representing the form element
     * @param mixed    $value Null means the default value in the json array will be used
     * @param string[] $lang  Language array
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function generateInput(array $json, mixed $value = null, array $lang = []) : string
    {
        $element = '<input';
        foreach ($json['attributes'] as $attribute => $val) {
            $element .= ' ' . $attribute . '="' . $val .  '"';
        }

        $value ??= $json['default']['value'] ?? '';

        $element .= (isset($json['default']) || $value !== null ? ' value="' . ($json['subtype'] === 'datetime' ? (new SmartDateTime($value))->format($json['default']['format']) : $value) .  '"' : '');

        $element .= ($json['subtype'] === 'checkbox' || $json['subtype'] === 'radio') && $json['default']['checked'] ? ' checked' : '';
        $element .= '>';
        $element .= $json['subtype'] === 'checkbox' || $json['subtype'] === 'radio' ? '<label for="' . $json['attributes']['id'] . '">' . ($lang[$json['default']['content']] ?? $json['default']['content']) . '</label>' : '';

        return $element;
    }

    /**
     * Generate a form element from a json object
     *
     * @param array    $json  Json object representing the form element
     * @param mixed    $value Null means the default value in the json array will be used
     * @param string[] $lang  Language array
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function generateSelect(array $json, mixed $value = null, array $lang = []) : string
    {
        $element = '<select';
        foreach ($json['attributes'] as $attribute => $val) {
            $element .= ' ' . $attribute . '="' . $val .  '"';
        }

        $element .= '>';

        $value ??= $json['default']['value'];

        foreach ($json['options'] as $val => $text) {
            $element .= '<option value="' . $val . '"' . (isset($json['default']) && $val === $value ? ' selected' : '') . '>' . ($lang[$text] ?? $text) .  '</option>';
        }

        $element .= '</select>';

        return $element;
    }

    /**
     * Generate a form element from a json object
     *
     * @param array $json  Json object representing the form element
     * @param mixed $value Null means the default value in the json array will be used
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function generateTextarea(array $json, mixed $value = null) : string
    {
        $element = '<textarea';
        foreach ($json['attributes'] as $attribute => $val) {
            $element .= ' ' . $attribute . '="' . $val .  '"';
        }

        $value ??= $json['default']['value'];

        $element .= '>';
        $element .= isset($json['default']) ? $value : '';
        $element .= '</textarea>';

        return $element;
    }

    /**
     * Generate a form element from a json object
     *
     * @param array    $json Json object representing the form element
     * @param string[] $lang Language array
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function generateLabel(array $json, array $lang = []) : string
    {
        $element = '<label';
        foreach ($json['attributes'] as $attribute => $val) {
            $element .= ' ' . $attribute . '="' . $val .  '"';
        }

        $element .= '>';
        $element .= $lang[$json['default']['value']] ?? $json['default']['value'];
        $element .= '</label>';

        return $element;
    }
}
