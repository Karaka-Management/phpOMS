<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Model\Html
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Model\Html;

use phpOMS\Stdlib\Base\SmartDateTime;

/**
 * Form element generator class.
 *
 * @package phpOMS\Model\Html
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class FormElementGenerator
{
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
    public static function generate(array $json, array $lang = []) : string
    {
        if ($json['type'] === 'select') {
            return self::generateSelect($json, $lang);
        } elseif ($json['type'] === 'input') {
            return self::generateInput($json, $lang);
        } elseif ($json['type'] === 'label') {
            return self::generateLabel($json, $lang);
        } elseif ($json['type'] === 'textarea') {
            return self::generateTextarea($json);
        }

        return 'INVALID';
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
    private static function generateInput(array $json, array $lang = []) : string
    {
        $element = '<input';
        foreach ($json['attributes'] as $attribute => $value) {
            $element .= ' ' . $attribute . '="' . $value .  '"';
        }

        $element .= (isset($json['default']) ? ' value="' . ($json['subtype'] === 'datetime' ? (new SmartDateTime($json['default']['value']))->format($json['default']['format']) : $json['default']['value']) .  '"' : '');

        $element .= ($json['subtype'] === 'checkbox' || $json['subtype'] === 'radio') && $json['default']['checked'] ? ' checked' : '';
        $element .= '>';
        $element .= $json['subtype'] === 'checkbox' || $json['subtype'] === 'radio' ? '<label for="' . $json['attributes']['id'] . '">' . ($lang[$json['default']['content']] ?? $json['default']['content']) . '</label>' : '';

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
    private static function generateSelect(array $json, array $lang = []) : string
    {
        $element = '<select';
        foreach ($json['attributes'] as $attribute => $value) {
            $element .= ' ' . $attribute . '="' . $value .  '"';
        }

        $element .= '>';

        foreach ($json['options'] as $value => $text) {
            $element .= '<option value="' . $value . '"' . (isset($json['default']) && $value === $json['default']['value'] ? ' selected' : '') . '>' . ($lang[$text] ?? $text) .  '</option>';
        }

        $element .= '</select>';

        return $element;
    }

    /**
     * Generate a form element from a json object
     *
     * @param array $json Json object representing the form element
     *
     * @return string
     *
     * @since 1.0.0
     */
    private static function generateTextarea(array $json) : string
    {
        $element = '<textarea';
        foreach ($json['attributes'] as $attribute => $value) {
            $element .= ' ' . $attribute . '="' . $value .  '"';
        }

        $element .= '>';
        $element .= isset($json['default']) ? ' value="' . $json['default']['value'] .  '"' : '';
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
        foreach ($json['attributes'] as $attribute => $value) {
            $element .= ' ' . $attribute . '="' . $value .  '"';
        }

        $element .= '>';
        $element .= isset($json['default']) ? ' value="' . ($lang[$json['default']['value']] ?? $json['default']['value']) .  '"' : '';
        $element .= '</label>';

        return $element;
    }
}
