<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Log\FileLogger;
use phpOMS\Module\ModuleAbstract;

/**
 * Localization class.
 *
 * @package phpOMS\Localization
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class L11nManager
{
    /**
     * Language.
     *
     * @var array
     * @since 1.0.0
     */
    private array $language = [];

    /**
     * App Name.
     *
     * @var string
     * @since 1.0.0
     */
    private string $appName;

    /**
     * Construct.
     *
     * @since 1.0.0
     */
    public function __construct(string $appName)
    {
        $this->appName = $appName;
    }

    /**
     * Verify if language is loaded.
     *
     * @param string $language Language iso code
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isLanguageLoaded(string $language) : bool
    {
        return isset($this->language[$language]);
    }

    /**
     * Load language.
     *
     * One module can only be loaded once. Once the module got loaded it's not
     * possible to load more language files later on.
     *
     * @param string                               $language    Language iso code
     * @param string                               $from        Module name
     * @param array<string, array<string, string>> $translation Language files content
     *
     * @return void
     *
     * @throws \UnexpectedValueException this exception is thrown when no language definitions for the defined source `$from` exist
     *
     * @since 1.0.0
     */
    public function loadLanguage(string $language, string $from, array $translation) : void
    {
        if (!isset($translation[$from])) {
            throw new \UnexpectedValueException($from);
        }

        if (!isset($this->language[$language][$from])) {
            $this->language[$language][$from] = $translation[$from];
        } else {
            /** @noinspection PhpWrongStringConcatenationInspection */
            $this->language[$language][$from] = $translation[$from] + $this->language[$language][$from];
        }
    }

    /**
     * Load language from file.
     *
     * One module can only be loaded once. Once the module got loaded it's not
     * possible to load more language files later on.
     *
     * @param string $language Language iso code
     * @param string $from     Module name
     * @param string $file     File to import language from
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function loadLanguageFromFile(string $language, string $from, string $file) : void
    {
        $lang = [];
        if (\file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            $lang = include $file;
        }

        $this->loadLanguage($language, $from, $lang);
    }

    /**
     * Get application language.
     *
     * @param string $language Language iso code
     * @param string $module   Module name
     *
     * @return array<string, string>|array<string, array<string, string>>
     *
     * @since 1.0.0
     */
    public function getModuleLanguage(string $language, string $module = null) : array
    {
        if ($module === null && isset($this->language[$language])) {
            return $this->language[$language];
        } elseif (isset($this->language[$language], $this->language[$language][$module])) {
            return $this->language[$language][$module];
        }

        return [];
    }

    /**
     * Get translation.
     *
     * @param string $code        Country code
     * @param string $module      Module name
     * @param string $theme       Theme
     * @param mixed  $translation Text
     *
     * @return string In case the language element couldn't be found 'ERROR' will be returned
     *
     * @since 1.0.0
     */
    public function getText(string $code, string $module, string $theme, $translation) : string
    {
        if (!isset($this->language[$code][$module][$translation])) {
            try {
                /** @var ModuleAbstract $class */
                $class = '\Modules\\' . $module . '\\Controller\\' . $this->appName . 'Controller';
                $this->loadLanguage($code, $module, $class::getLocalization($code, $theme));

                if (!isset($this->language[$code][$module][$translation])) {
                    return 'ERROR';
                }
            } catch (\Throwable $e) {
                // @codeCoverageIgnoreStart
                FileLogger::getInstance()->warning(FileLogger::MSG_FULL, [
                    'message' => 'Undefined translation for \'' . $code . '/' . $module . '/' . $translation . '\'.',
                ]);

                return 'ERROR';
                // @codeCoverageIgnoreEnd
            }
        }

        return $this->language[$code][$module][$translation];
    }

    /**
     * Get translation html escaped.
     *
     * @param string $code        Country code
     * @param string $module      Module name
     * @param string $theme       Theme
     * @param mixed  $translation Text
     *
     * @return string In case the language element couldn't be found 'ERROR' will be returned
     *
     * @since 1.0.0
     */
    public function getHtml(string $code, string $module, string $theme, $translation) : string
    {
        return \htmlspecialchars($this->getText($code, $module, $theme, $translation));
    }
}
