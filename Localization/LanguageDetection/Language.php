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

/**
 * Langauge detection class
 *
 * @package phpOMS\Localization\LanguageDetection
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @link    https://github.com/patrickschur/language-detection
 * @since   1.0.0
 */
class Language extends NgramParser
{
    /**
     * Tokens
     *
     * @var array
     * @since 1.0.0
     */
    protected array $tokens = [];

    /**
     * Constructor.
     *
     * @param array  $lang    List of ISO 639-1 codes, that should be used in the detection phase
     * @param string $dirname Name of the directory where the translations files are located
     *
     * @since 1.0.0
     */
    public function __construct(array $lang = [], string $dirname = '')
    {
        if (empty($dirname)) {
            $dirname = __DIR__ . '/resources/*/*.php';
        } elseif (!\is_dir($dirname) || !\is_readable($dirname)) {
            throw new \InvalidArgumentException('Provided directory could not be found or is not readable');
        } else {
            $dirname  = \rtrim($dirname, '/');
            $dirname .= '/*/*.php';
        }

        $isEmpty = empty($lang);

        $files = \glob($dirname);
        if ($files === false) {
            $files = [];
        }

        foreach ($files as $file) {
            if ($isEmpty || \in_array(\basename($file, '.php'), $lang)) {
                $this->tokens += require $file;
            }
        }
    }

    /**
     * Detects the language from a given text string
     *
     * @param string $str String to use for detection
     *
     * @return LanguageResult
     *
     * @since 1.0.0
     */
    public function detect(string $str): LanguageResult
    {
        $str     = \mb_strtolower($str);
        $samples = $this->getNgrams($str);
        $result  = [];

        if (\count($samples) > 0) {
            foreach ($this->tokens as $lang => $value) {
                $index = 0;
                $sum   = 0;
                $value = \array_flip($value);

                foreach ($samples as $v) {
                    if (isset($value[$v])) {
                        $x    = $index++ - $value[$v];
                        $y    = $x >> (\PHP_INT_SIZE * 8);
                        $sum += ($x + $y) ^ $y;
                        continue;
                    }

                    $sum += $this->maxNgrams;
                    ++$index;
                }

                $result[$lang] = 1 - ($sum / ($this->maxNgrams * $index));
            }

            \arsort($result, \SORT_NUMERIC);
        }

        return new LanguageResult($result);
    }
}
