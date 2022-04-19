<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * Text generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Text
{
    /**
     * Vocabulary.
     *
     * @var string[]
     * @since 1.0.0
     */
    public const LOREM_IPSUM = [
        'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'curabitur', 'vel', 'hendrerit', 'libero',
        'eleifend', 'blandit', 'nunc', 'ornare', 'odio', 'ut', 'orci', 'gravida', 'imperdiet', 'nullam', 'purus', 'lacinia', 'a',
        'pretium', 'quis', 'congue', 'praesent', 'sagittis', 'laoreet', 'auctor', 'mauris', 'non', 'velit', 'eros', 'dictum',
        'proin', 'accumsan', 'sapien', 'nec', 'massa', 'volutpat', 'venenatis', 'sed', 'eu', 'molestie', 'lacus', 'quisque',
        'porttitor', 'ligula', 'dui', 'mollis', 'tempus', 'at', 'magna', 'vestibulum', 'turpis', 'ac', 'diam', 'tincidunt', 'id',
        'condimentum', 'enim', 'sodales', 'in', 'hac', 'habitasse', 'platea', 'dictumst', 'aenean', 'neque', 'fusce', 'augue',
        'leo', 'eget', 'semper', 'mattis', 'tortor', 'scelerisque', 'nulla', 'interdum', 'tellus', 'malesuada', 'rhoncus', 'porta',
        'sem', 'aliquet', 'et', 'nam', 'suspendisse', 'potenti', 'vivamus', 'luctus', 'fringilla', 'erat', 'donec', 'justo',
        'vehicula', 'ultricies', 'varius', 'ante', 'primis', 'faucibus', 'ultrices', 'posuere', 'cubilia', 'curae', 'etiam',
        'cursus', 'aliquam', 'quam', 'dapibus', 'nisl', 'feugiat', 'egestas', 'class', 'aptent', 'taciti', 'sociosqu', 'ad',
        'litora', 'torquent', 'per', 'conubia', 'nostra', 'inceptos', 'himenaeos', 'phasellus', 'nibh', 'pulvinar', 'vitae',
        'urna', 'iaculis', 'lobortis', 'nisi', 'viverra', 'arcu', 'morbi', 'pellentesque', 'metus', 'commodo', 'ut', 'facilisis',
        'felis', 'tristique', 'ullamcorper', 'placerat', 'aenean', 'convallis', 'sollicitudin', 'integer', 'rutrum', 'duis', 'est',
        'etiam', 'bibendum', 'donec', 'pharetra', 'vulputate', 'maecenas', 'mi', 'fermentum', 'consequat', 'suscipit', 'aliquam',
        'habitant', 'senectus', 'netus', 'fames', 'quisque', 'euismod', 'curabitur', 'lectus', 'elementum', 'tempor', 'risus',
        'cras',
    ];

    /**
     * Text has random formatting.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasFormatting = false;

    /**
     * Text has paragraphs.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $hasParagraphs = false;

    /**
     * Amount of sentences of the last generated text.
     *
     * @var int
     * @since 1.0.0
     */
    public int $sentences = 0;

    /**
     * Constructor
     *
     * @param bool $hasFormatting Text should have formatting
     * @param bool $hasParagraphs Text should have paragraphs
     *
     * @since 1.0.0
     */
    public function __construct(bool $hasFormatting = false, bool $hasParagraphs = false)
    {
        $this->hasFormatting = $hasFormatting;
        $this->hasParagraphs = $hasParagraphs;
    }

    /**
     * Get a random string.
     *
     * @param int      $length Text length
     * @param string[] $words  Vocabulary
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function generateText(int $length, array $words = null) : string
    {
        if ($length === 0) {
            return '';
        }

        if ($words === null) {
            $words = self::LOREM_IPSUM;
        }

        $punctuation      = $this->generatePunctuation($length);
        $punctuationCount = \array_count_values(
                \array_map(
                    function ($item) {
                        return $item[1];
                    },
                    $punctuation
                )
            ) + ['.' => 0, '!' => 0, '?' => 0];

        $this->sentences = $punctuationCount['.'] + $punctuationCount['!'] + $punctuationCount['?'];

        if ($this->hasParagraphs) {
            $paragraph = $this->generateParagraph($this->sentences);
        }

        if ($this->hasFormatting) {
            $formatting = $this->generateFormatting($length);
        }

        $sentenceCount = 0;
        $text          = '';
        $puid          = 0;
        $paid          = 0;
        $wordCount     = \count($words);

        for ($i = 0; $i < $length + 1; ++$i) {
            $newSentence = false;

            $lastChar = \substr($text, -1);

            if ($lastChar === '.' || $lastChar === '!' || $lastChar === '?' || !$lastChar) {
                $newSentence = true;
            }

            $word = $words[\mt_rand(0, $wordCount - 1)] ?? '';

            if ($newSentence) {
                $word = \ucfirst($word);
                ++$sentenceCount;

                /** @noinspection PhpUndefinedVariableInspection */
                if ($this->hasParagraphs) {
                    ++$paid;

                    $text .= '</p><p>';
                }
            }

            /** @noinspection PhpUndefinedVariableInspection */
            if ($this->hasFormatting && isset($formatting[$i])) {
                $word = '<' . $formatting[$i] . '>' . $word . '</' . $formatting[$i] . '>';
            }

            $text .= ' ' . $word;

            if ($punctuation[$puid][0] === $i) {
                $text .= $punctuation[$puid][1];
                ++$puid;
            }
        }

        $text = \ltrim($text);

        if ($this->hasParagraphs) {
            $text = '<p>' . $text . '</p>';
        }

        return $text;
    }

    /**
     * Generate punctuation.
     *
     * @param int $length Text length
     *
     * @return array<int, array<int, int|string>>
     *
     * @since 1.0.0
     */
    private function generatePunctuation(int $length) : array
    {
        $minSentences    = 4;
        $maxSentences    = 20;
        $minCommaSpacing = 3;
        $probComma       = 0.2;
        $probDot         = 0.8;
        $probExc         = 0.4;

        $punctuation = [];

        for ($i = 0; $i < $length;) {
            $sentenceLength = \mt_rand($minSentences, $maxSentences);

            if ($i + $sentenceLength > $length || $length - ($i + $sentenceLength) < $minSentences) {
                $sentenceLength = $length - $i;
            }

            /* Handle comma */
            $commaHere = (\mt_rand(0, 100) <= $probComma * 100 && $sentenceLength >= 2 * $minCommaSpacing ? true : false);
            $posComma  = [];

            if ($commaHere) {
                $posComma[]    = \mt_rand($minCommaSpacing, $sentenceLength - $minCommaSpacing);
                $punctuation[] = [$i + $posComma[0], ','];

                $commaHere = (\mt_rand(0, 100) <= $probComma * 100 && $sentenceLength > $posComma[0] + $minCommaSpacing * 2 ? true : false);

                if ($commaHere) {
                    $posComma[]    = \mt_rand($posComma[0] + $minCommaSpacing, $sentenceLength - $minCommaSpacing);
                    $punctuation[] = [$i + $posComma[1], ','];
                }
            }

            $i += $sentenceLength;

            /* Handle sentence ending */
            $isDot = (\mt_rand(0, 100) <= $probDot * 100 ? true : false);

            if ($isDot) {
                $punctuation[] = [$i, '.'];
                continue;
            }

            $isEx = (\mt_rand(0, 100) <= $probExc * 100 ? true : false);

            if ($isEx) {
                $punctuation[] = [$i, '!'];
                continue;
            }

            $punctuation[] = [$i, '?'];
        }

        return $punctuation;
    }

    /**
     * Generate paragraphs.
     *
     * @param int $length Amount of sentences
     *
     * @return int[]
     *
     * @since 1.0.0
     */
    private function generateParagraph(int $length) : array
    {
        $minSentence = 3;
        $maxSentence = 10;

        $paragraph = [];

        for ($i = 0; $i < $length;) {
            $paragraphLength = \mt_rand($minSentence, $maxSentence);

            if ($i + $paragraphLength > $length || $length - ($i + $paragraphLength) < $minSentence) {
                $paragraphLength = $length - $i;
            }

            $i          += $paragraphLength;
            $paragraph[] = $i;
        }

        return $paragraph;
    }

    /**
     * Generate random formatting.
     *
     * @param int $length Amount of words
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    private function generateFormatting(int $length) : array
    {
        $probCursive = 0.005;
        $probBold    = 0.005;
        $probUline   = 0.005;

        $formatting = [];

        for ($i = 0; $i < $length; ++$i) {
            $isCursive = (\mt_rand(0, 1000) <= 1000 * $probCursive ? true : false);
            $isBold    = (\mt_rand(0, 1000) <= 1000 * $probBold ? true : false);
            $isUline   = (\mt_rand(0, 1000) <= 1000 * $probUline ? true : false);

            if ($isUline) {
                $formatting[$i] = 'u';
            }

            if ($isBold) {
                $formatting[$i] = 'b';
            }

            if ($isCursive) {
                $formatting[$i] = 'i';
            }
        }

        return $formatting;
    }
}
