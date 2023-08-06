<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\LanguageDetection
 * @author    Patrick Schur <patrick_schur@outlook.de>
 * @copyright Patrick Schur
 * @license   https://opensource.org/licenses/mit-license.html MIT
 * @link      https://github.com/patrickschur/language-detection
 */
declare(strict_types=1);

namespace phpOMS\Localization\LanguageDetection;

/**
 * Langauge training class
 *
 * @package phpOMS\Localization\LanguageDetection
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @link    https://github.com/patrickschur/language-detection
 * @since   1.0.0
 */
class Trainer extends NgramParser
{
    /**
     * Generates language profiles for all language files
     *
     * @param string $dirname Name of the directory where the translations files are located
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function learn(string $dirname = '') : void
    {
        if (empty($dirname)) {
            $dirname = __DIR__ . '/resources/*/*.txt';
        } elseif (!\is_dir($dirname) || !\is_readable($dirname)) {
            throw new \InvalidArgumentException('Provided directory could not be found or is not readable');
        } else {
            $dirname  = \rtrim($dirname, '/');
            $dirname .= '/*/*.txt';
        }

        /** @var \GlobIterator $txt */
        foreach (new \GlobIterator($dirname) as $txt) {
            $content = \file_get_contents($txt->getPathname());
            if ($content === false) {
                break;
            }

            $content = \mb_strtolower($content);

            \file_put_contents(
                \substr_replace($txt->getPathname(), 'php', -3),
                \sprintf("<?php\n\nreturn %s;\n", \var_export([$txt->getBasename('.txt') => $this->getNgrams($content)], true))
            );
        }
    }
}
