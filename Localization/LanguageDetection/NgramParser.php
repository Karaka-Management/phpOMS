<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\LanguageDetection
 * @author    Patrick Schur <patrick_schur@outlook.de>
 * @copyright Patrick Schur
 * @license   https://opensource.org/licenses/mit-license.html MIT
 * @link      https://github.com/patrickschur/language-detection
 */
declare(strict_types = 1);

namespace phpOMS\Localization\LanguageDetection;

use phpOMS\Localization\LanguageDetection\Tokenizer\WhitespaceTokenizer;

/**
 * Ngram parser class
 *
 * @package phpOMS\Localization\LanguageDetection
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @link    https://github.com/patrickschur/language-detection
 * @since   1.0.0
 */
abstract class NgramParser
{
    /**
     * Minimum length
     *
     * @var int
     * @since 1.0.0
     */
    public int $minLength = 1;

    /**
     * Maximum length
     *
     * @var int
     * @since 1.0.0
     */
    public int $maxLength = 3;

    /**
     * Maximum amount of ngrams
     *
     * @var int
     * @since 1.0.0
     */
    public int $maxNgrams = 310;

    /**
     * Tokenizer to use
     *
     * @var null|WhitespaceTokenizer
     * @since 1.0.0
     */
    public ?WhitespaceTokenizer $tokenizer = null;

    /**
     * Tokenize string
     *
     * @param string $str String to tokenize
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function tokenize(string $str) : array
    {
        if ($this->tokenizer === null) {
            $this->tokenizer = new WhitespaceTokenizer();
        }

        return $this->tokenizer->tokenize($str);
    }

    /**
     * Get ngrams
     *
     * @param string $str String to parse
     *
     * @return array
     *
     * @since 1.0.0
     */
    protected function getNgrams(string $str): array
    {
        $tokens = [];

        foreach ($this->tokenize($str) as $word) {
            $l   = \mb_strlen($word);
            $tmp = 0;

            for ($i = $this->minLength; $i <= $this->maxLength; ++$i) {
                for ($j = 0; ($i + $j - 1) < $l; ++$j, ++$tmp) {
                    $tmp = &$tokens[$i][\mb_substr($word, $j, $i)];
                }
            }
        }

        foreach ($tokens as $i => $token) {
            $sum = \array_sum($token);

            foreach ($token as $j => $value) {
                $tokens[$i][$j] = $value / $sum;
            }
        }

        if (empty($tokens)) {
            return [];
        }

        $tokens = \array_merge(...$tokens);
        unset($tokens['_']);

        \arsort($tokens, \SORT_NUMERIC);

        return \array_slice(
            \array_keys($tokens),
            0,
            $this->maxNgrams
        );
    }
}
