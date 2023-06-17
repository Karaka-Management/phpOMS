<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\LanguageDetection\Tokenizer
 * @author    Patrick Schur <patrick_schur@outlook.de>
 * @copyright Patrick Schur
 * @license   https://opensource.org/licenses/mit-license.html MIT
 * @link      https://github.com/patrickschur/language-detection
 */
declare(strict_types=1);

namespace phpOMS\Localization\LanguageDetection\Tokenizer;

/**
 * Whitespace tokenizer
 *
 * @package phpOMS\Localization\LanguageDetection\Tokenizer
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @link    https://github.com/patrickschur/language-detection
 * @since   1.0.0
 */
class WhitespaceTokenizer
{
    /**
     * Tokenize string
     *
     * @param string $str String to tokenize
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function tokenize(string $str) : array
    {
        $split = \preg_split('/[^\pL]+(?<![\x27\x60\x{2019}])/u', $str, -1, \PREG_SPLIT_NO_EMPTY);
        if ($split === false) {
            return [];
        }

        return \array_map(
            function ($word) {
                return "_{$word}_";
            },
            $split
        );
    }
}
