<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * String comparison class.
 *
 * This class helps to compare two strings
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class StringCompare
{
    /**
     * Dictionary.
     *
     * @var string[]
     * @since 1.0.0
     */
    private array $dictionary = [];

    /**
     * Constructor.
     *
     * @param string[] $dictionary Dictionary
     *
     * @since 1.0.0
     */
    public function __construct(array $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * Adds word to dictionary
     *
     * @param string $word Word to add to dictionary
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function add(string $word) : void
    {
        $this->dictionary[] = $word;
    }

    /**
     * Match word against dictionary.
     *
     * @param string $match Word to match against dictionary
     *
     * @return string Best match
     *
     * @since 1.0.0
     */
    public function matchDictionary(string $match) : string
    {
        $bestScore = \PHP_INT_MAX;
        $bestMatch = '';

        foreach ($this->dictionary as $word) {
            $score = self::fuzzyMatch($word, $match);

            if ($score < $bestScore) {
                $bestScore = $score;
                $bestMatch = $word;
            }
        }

        return $bestMatch;
    }

    /**
     * Jaro string distance
     *
     * @param string $s1 String1
     * @param string $s2 String2
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function jaro(string $s1, string $s2) : float
    {
        $s1Size = \strlen($s1);
        $s2Size = \strlen($s2);

        if ($s1Size === 0) {
            return $s2Size === 0 ? 1.0 : 0.0;
        }

        $mDistance = (int) (\max($s1Size, $s2Size) / 2 - 1);

        $matches        = 0;
        $transpositions = 0.0;

        $s1Matches = [];
        $s2Matches = [];

        for ($i = 0; $i < $s1Size; ++$i) {
            $start = \max(0, $i - $mDistance);
            $end   = \min($i + $mDistance + 1, $s2Size);

            for ($j = $start; $j < $end; ++$j) {
                if (isset($s2Matches[$j])) {
                    continue;
                }

                if ($s1[$i] !== $s2[$j]) {
                    continue;
                }

                $s1Matches[$i] = true;
                $s2Matches[$j] = true;

                ++$matches;
                break;
            }
        }

        if ($matches === 0) {
            return 0.0;
        }

        $j = 0;
        for ($i = 0; $i < $s1Size; ++$i) {
            if (!isset($s1Matches[$i])) {
                continue;
            }

            while (!isset($s2Matches[$j])) {
                ++$j;
            }

            if ($s1[$i] !== $s2[$j]) {
                ++$transpositions;
            }

            ++$j;
        }

        $transpositions /= 2.0;

        return ($matches / $s1Size
            + $matches / $s2Size
            + ($matches - $transpositions) / $matches)
            / 3.0;
    }

    /**
     * Calculate word match score.
     *
     * @param string $s1 Word 1
     * @param string $s2 Word 2
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function valueWords(string $s1, string $s2) : int
    {
        $words1 = \preg_split('/[ _-]/', $s1);
        $words2 = \preg_split('/[ _-]/', $s2);
        $total  = 0;

        if ($words1 === false || $words2 === false) {
            return \PHP_INT_MAX; // @codeCoverageIgnore
        }

        foreach ($words1 as $word1) {
            $best = \strlen($s2);

            foreach ($words2 as $word2) {
                $wordDist = \levenshtein($word1, $word2);

                if ($wordDist < $best) {
                    $best = $wordDist;
                }

                if ($wordDist === 0) {
                    break;
                }
            }

            $total += $best;
        }

        return $total;
    }

    /**
     * Calculate phrase match score.
     *
     * @param string $s1 Word 1
     * @param string $s2 Word 2
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function valuePhrase(string $s1, string $s2) : int
    {
        return \levenshtein($s1, $s2);
    }

    /**
     * Calculate word length score.
     *
     * @param string $s1 Word 1
     * @param string $s2 Word 2
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function valueLength(string $s1, string $s2) : int
    {
        return \abs(\strlen($s1) - \strlen($s2));
    }

    /**
     * Calculate fuzzy match score.
     *
     * @param string $s1           Word 1
     * @param string $s2           Word 2
     * @param float  $phraseWeight Weighting for phrase score
     * @param float  $wordWeight   Weighting for word score
     * @param float  $minWeight    Min weight
     * @param float  $maxWeight    Max weight
     * @param float  $lengthWeight Weighting for word length
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function fuzzyMatch(
        string $s1, string $s2,
        float $phraseWeight = 0.5, float $wordWeight = 1.0,
        float $minWeight = 10.0, float $maxWeight = 1.0,
        float $lengthWeight = -0.3
    ) : float
    {
        $phraseValue = self::valuePhrase($s1, $s2);
        $wordValue   = self::valueWords($s1, $s2);
        $lengthValue = self::valueLength($s1, $s2);

        return \min($phraseValue * $phraseWeight, $wordValue * $wordWeight) * $minWeight
            + \max($phraseValue * $phraseWeight, $wordValue * $wordWeight) * $maxWeight
            + $lengthValue * $lengthWeight;
    }
}
