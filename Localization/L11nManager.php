<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Localization;

use phpOMS\Log\FileLogger;
use phpOMS\Log\LoggerInterface;

/**
 * Localization class.
 *
 * @category   Framework
 * @package    phpOMS\Localization
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class L11nManager
{

    /**
     * Language.
     *
     * @var string[][]
     * @since 1.0.0
     */
    private $language = [];

    /**
     * Logger.
     *
     * @var LoggerInterface
     * @since 1.0.0
     */
    private $logger = null;

    /**
     * Construct.
     *
     * @param LoggerInterface $logger Logger
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Verify if language is loaded.
     *
     * @param string $language Language iso code
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @param string     $language    Language iso code
     * @param string     $from        Module name
     * @param string[][] $translation Language files content
     *
     * @return void
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function loadLanguage(string $language, string $from, array $translation)
    {
        if (!isset($translation[$from])) {
            throw new \Exception('Unexpected language key: ' . $from);
        }

        if (!isset($this->language[$language][$from])) {
            $this->language[$language][$from] = $translation[$from];
        } else {
            /** @noinspection PhpWrongStringConcatenationInspection */
            $this->language[$language][$from] = $translation[$from] + $this->language[$language][$from];
        }
    }

    /**
     * Get application language.
     *
     * @param string $language Language iso code
     * @param string $module   Module name
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getModuleLanguage(string $language, string $module = null) : array
    {
        if (!isset($module) && isset($this->language[$language])) {
            return $this->language[$language];
        } elseif (isset($this->language[$language])) {
            return $this->language[$language][$module];
        } else {
            return [];
        }
    }

    /**
     * Get translation.
     *
     * @param string $code        Country code
     * @param string $module      Module name
     * @param string $translation Text
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getText(string $code, string $module, string $translation)
    {
        if (!isset($this->language[$code][$module][$translation])) {
            $class = '\Modules\\' . $module . '\\Controller';
            $this->loadLanguage($code, $module, $class::getLocalization($code, $module));

            if (!isset($this->language[$code][$module][$translation])) {
                $this->logger->warning(FileLogger::MSG_FULL, [
                    'message' => 'Undefined translation for \'' . $code . '/' . $module . '/' . $translation . '\'.'
                ]);

                return 'ERROR';
            }
        }

        return $this->language[$code][$module][$translation];
    }
}
