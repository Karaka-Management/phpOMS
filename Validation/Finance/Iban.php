<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Validation\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation\Finance;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Iban validation.
 *
 * @package phpOMS\Validation\Finance
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Iban extends ValidatorAbstract
{
    /**
     * {@inheritdoc}
     */
    public static function isValid(mixed $value, ?array $constraints = null) : bool
    {
        if (!\is_string($value)) {
            return false;
        }

        $value = \str_replace(' ', '', \strtolower($value));

        $temp = \substr($value, 0, 2);
        if ($temp === false) {
            return false; // @codeCoverageIgnore
        }

        $enumName = '_' . \strtoupper($temp);

        if (!IbanEnum::isValidName($enumName)) {
            self::$error = IbanErrorType::INVALID_COUNTRY;

            return false;
        }

        /** @var string $enumVal */
        $enumVal = IbanEnum::getByName($enumName);
        $layout  = \str_replace(' ', '', $enumVal);

        if (\strlen($value) !== \strlen($layout)) {
            self::$error = IbanErrorType::INVALID_LENGTH;

            return false;
        }

        if (!self::validateZeros($value, $layout)) {
            self::$error = IbanErrorType::EXPECTED_ZERO;

            return false;
        }

        if (!self::validateNumeric($value, $layout)) {
            self::$error = IbanErrorType::EXPECTED_NUMERIC;

            return false;
        }

        if (!self::validateChecksum($value)) {
            self::$error = IbanErrorType::INVALID_CHECKSUM;

            return false;
        }

        return true;
    }

    /**
     * Validate positions that should have zeros
     *
     * @param string $iban   Iban to validate
     * @param string $layout Iban layout
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private static function validateZeros(string $iban, string $layout) : bool
    {
        if (\strpos($layout, '0') === false) {
            return true;
        }

        $lastPos = 0;
        while (($lastPos = \strpos($layout, '0', $lastPos)) !== false) {
            if ($iban[$lastPos] !== '0') {
                return false;
            }

            ++$lastPos;
        }

        return true;
    }

    /**
     * Validate positions that should be numeric
     *
     * @param string $iban   Iban to validate
     * @param string $layout Iban layout
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private static function validateNumeric(string $iban, string $layout) : bool
    {
        if (\strpos($layout, 'n') === false) {
            return true;
        }

        $lastPos = 0;
        while (($lastPos = \strpos($layout, 'n', $lastPos)) !== false) {
            if (!\is_numeric($iban[$lastPos])) {
                return false;
            }

            ++$lastPos;
        }

        return true;
    }

    /**
     * Validate checksum
     *
     * @param string $iban Iban to validate
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private static function validateChecksum(string $iban) : bool
    {
        $chars = [
            'a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18,
            'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22, 'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27,
            's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35,
        ];
        $moved      = \substr($iban, 4) . \substr($iban, 0, 4);
        $movedArray = (array) \str_split($moved);
        $new        = '';

        foreach ($movedArray as $key => $_) {
            if (!\is_numeric($movedArray[$key])) {
                $movedArray[$key] = $chars[$movedArray[$key]];
            }

            $new .= $movedArray[$key];
        }

        return \bcmod($new, '97') == 1;
    }
}
