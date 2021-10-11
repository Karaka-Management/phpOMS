<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization;

use phpOMS\Autoloader;
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
     * @var array<string, array<int|string, array<string, string>>>
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

        $this->language[$language][$from] = !isset($this->language[$language][$from])
            ? $translation[$from]
            : $translation[$from] + $this->language[$language][$from];
    }

    /**
     * Load language file which contains multiple languages.
     *
     * @param string $from Module name
     * @param string $file File to import language from
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function loadLanguageFile(string $from, string $file) : void
    {
        if (!is_file($file)) {
            return;
        }

        /** @noinspection PhpIncludeInspection */
        $lang = include $file;

        foreach ($lang as $code => $translation) {
            $this->loadLanguage($code, $from, $translation);
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
        if (!is_file($file)) {
            return;
        }

        /** @noinspection PhpIncludeInspection */
        $lang = include $file;
        $this->loadLanguage($language, $from, $lang);
    }

    /**
     * Get application language.
     *
     * @param string $language Language iso code
     * @param string $module   Module name
     *
     * @return array<int|string, array<string, string>>|array<string, string>
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
     * @param string      $code        Language code
     * @param string      $module      Module name
     * @param string      $theme       Theme
     * @param string      $translation Text
     * @param null|string $app         App name
     *
     * @return string In case the language element couldn't be found 'ERROR' will be returned
     *
     * @since 1.0.0
     */
    public function getText(string $code, string $module, string $theme, string $translation, string $app = null) : string
    {
        if (isset($this->language[$code][$module][$translation])) {
            return $this->language[$code][$module][$translation];
        }

        try {
            /** @var ModuleAbstract $class */
            $class = '\Modules\\' . $module . '\\Controller\\' . ($app ?? $this->appName) . 'Controller';

            /** @var string $class */
            if (!Autoloader::exists($class)) {
                return 'ERROR-' . $translation;
            }

            $this->loadLanguage($code, $module, $class::getLocalization($code, $theme));
        } catch (\Throwable $e) {
            // @codeCoverageIgnoreStart
            FileLogger::getInstance()->warning(FileLogger::MSG_FULL, [
                'message' => 'Undefined translation for \'' . $code . '/' . $module . '/' . $translation . '\'.',
            ]);
            // @codeCoverageIgnoreEnd
        }

        return $this->language[$code][$module][$translation] ?? 'ERROR-' . $translation;
    }

    /**
     * Get translation html escaped.
     *
     * @param string $code        Language code
     * @param string $module      Module name
     * @param string $theme       Theme
     * @param string $translation Text
     *
     * @return string In case the language element couldn't be found 'ERROR' will be returned
     *
     * @since 1.0.0
     */
    public function getHtml(string $code, string $module, string $theme, string $translation) : string
    {
        return htmlspecialchars($this->getText($code, $module, $theme, $translation));
    }

    /**
     * Print a numeric value
     *
     * @param Localization $l11n    Localization
     * @param int|float    $numeric Numeric value to print
     * @param null|string  $format  Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getNumeric(Localization $l11n, int | float $numeric, string $format = null) : string
    {
        return number_format(
            $numeric,
            $l11n->getPrecision()[$format ?? 'medium'],
            $l11n->getDecimal(),
            $l11n->getThousands()
        );
    }

    /**
     * Print a percentage value
     *
     * @param Localization $l11n       Localization
     * @param float        $percentage Percentage value to print
     * @param null|string  $format     Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPercentage(Localization $l11n, float $percentage, string $format = null) : string
    {
        return number_format(
            $percentage, $l11n->getPrecision()[$format ?? 'medium'],
            $l11n->getDecimal(),
            $l11n->getThousands()
        ) . '%';
    }

    /**
     * Print a currency
     *
     * @param Localization $l11n     Localization
     * @param int|float    $currency Currency value to print
     * @param null|string  $format   Format type to use
     * @param null|string  $symbol   Currency name/symbol
     * @param int          $divide   Divide currency by divisor
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCurrency(Localization $l11n, int | float $currency, string $format = null, string $symbol = null, int $divide = 1) : string
    {
        $language = $l11n->getLanguage() ?? 'en';
        $symbol ??= $l11n->getCurrency();

        if (\is_float($currency)) {
            $currency = (int) ($currency * pow(10, Money::MAX_DECIMALS));
        }

        if (!empty($symbol)) {
            if ($divide === 1000) {
                $symbol = $this->getHtml($language, '0', '0', 'CurrencyK') . $symbol;
            } elseif ($divide === 1000000) {
                $symbol = $this->getHtml($language, '0', '0', 'CurrencyM') . $symbol;
            } elseif ($divide === 1000000000) {
                $symbol = $this->getHtml($language, '0', '0', 'CurrencyB') . $symbol;
            }
        }

        $money = new Money(
            (int) ($currency / $divide),
            $l11n->getThousands(),
            $l11n->getDecimal(),
            $symbol ?? $l11n->getCurrency(),
            (int) $l11n->getCurrencyFormat()
        );

        return $money->getCurrency($l11n->getPrecision()[$format ?? 'medium']);
    }

    /**
     * Print a datetime
     *
     * @param Localization            $l11n     Localization
     * @param null|\DateTimeInterface $datetime DateTime to print
     * @param string                  $format   Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDateTime(Localization $l11n, \DateTimeInterface $datetime = null, string $format = null) : string
    {
        return $datetime === null
            ? ''
            : $datetime->format($l11n->getDateTime()[$format ?? 'medium']);
    }
}
