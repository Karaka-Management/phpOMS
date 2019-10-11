<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Validation\Base
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Validation\Base;

use phpOMS\Utils\StringUtils;
use phpOMS\Validation\ValidatorAbstract;

/**
 * Validate json.
 *
 * @package phpOMS\Validation\Base
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class Json extends ValidatorAbstract
{

    /**
     * {@inheritdoc}
     */
    public static function isValid($value, array $constraints = null) : bool
    {
        \json_decode($value);

        return \json_last_error() == \JSON_ERROR_NONE;
    }

    /**
     * Validate array against a template array.
     *
     * @param array $template Template structure
     * @param array $source   Source structure
     * @param bool  $perfect  No additional elements in source allowed
     *
     * @return bool Returns true if the template validates the source otherwise false
     *
     * @since 1.0.0
     */
    public static function validateTemplate(array $template, array $source, bool $perfect = false) : bool
    {
        $templatePaths = self::createAllViablePaths($template, '');
        $sourcePaths   = self::createAllViablePaths($source, '');

        $isComplete = self::isCompleteSource($templatePaths, $sourcePaths);
        if (!$isComplete) {
            return false;
        }

        if ($perfect) {
            $perfectFit = self::hasTemplateDefinition($templatePaths, $sourcePaths);
            if (!$perfectFit) {
                return false;
            }
        }

        $isValid = self::isValidSource($templatePaths, $sourcePaths);
        if (!$isValid) {
            return false;
        }

        return true;
    }

    /**
     * Create all viable paths and their values
     *
     * @param array  $arr  Array
     * @param string $path Current path
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createAllViablePaths(array $arr, string $path = '') : array
    {
        $paths = [];
        foreach ($arr as $key => $value) {
            $tempPath = $path . '/' . $key;

            if (\is_array($value)) {
                $paths += self::createAllViablePaths($value, $tempPath);
            } else {
                $paths[$tempPath] = $value;
            }
        }

        return $paths;
    }

    /**
     * Check if source array has additional elements.
     *
     * @param array $template Template structure
     * @param array $source   Source structure
     *
     * @return bool Returns false in case of undefined elements
     *
     * @since 1.0.0
     */
    private static function hasTemplateDefinition(array $template, array $source) : bool
    {
        $completePaths = [];
        foreach ($template as $key => $value) {
            $key                 = \str_replace('/0', '/.*', $key);
            $completePaths[$key] = $value;
        }

        foreach ($source as $sPath => $sValue) {
            $hasDefinition = false;

            foreach ($completePaths as $tPath => $tValue) {
                if ($tPath === $sPath
                    || \preg_match('~' . \str_replace('/', '\\/', $tPath) . '~', $sPath) === 1
                ) {
                    $hasDefinition = true;
                    break;
                }
            }

            if (!$hasDefinition) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if source array is complete
     *
     * @param array $template Template structure
     * @param array $source   Source structure
     *
     * @return bool Returns true if the source implements all required elements otherwise false is returned
     *
     * @since 1.0.0
     */
    private static function isCompleteSource(array $template, array $source) : bool
    {
        $completePaths = [];
        foreach ($template as $key => $value) {
            $key = \str_replace('/0', '/.*', $key);

            if (\stripos($key, '/.*') !== false) {
                continue;
            }

            $completePaths[$key] = $value;
        }

        foreach ($completePaths as $tPath => $tValue) {
            $sourceIsComplete = false;

            foreach ($source as $sPath => $sValue) {
                if ($tPath === $sPath
                    || \preg_match('~' . \str_replace('/', '\\/', $tPath) . '~', $sPath) === 1
                ) {
                    unset($completePaths[$tPath]);
                    break;
                }
            }
        }

        return \count($completePaths) === 0;
    }

    /**
     * Check if source array is correct
     *
     * @param array $template Template structure
     * @param array $source   Source structure
     *
     * @return bool Returns true if the source is correct in relation to the template otherwise false is returned
     *
     * @since 1.0.0
     */
    private static function isValidSource(array $template, array $source) : bool
    {
        $validPaths = [];
        foreach ($template as $key => $value) {
            $key              = \str_replace('/0', '/\d*', $key);
            $validPaths[$key] = $value;
        }

        foreach ($source as $sPath => $sValue) {
            $isValidValue = false;
            $pathFound    = false;

            foreach ($validPaths as $tPath => $tValue) {
                if ($tPath === $sPath
                    || \preg_match('~' . \str_replace('/', '\\/', $tPath) . '~', $sPath) === 1
                ) {
                    $pathFound = true;
                    $sValue    = StringUtils::stringify($sValue);

                    if (($tValue === $sValue
                        || \preg_match('~' . ((string) $tValue) . '~', (string) $sValue) === 1)
                    ) {
                        $isValidValue = true;
                        break;
                    }
                }
            }

            if (!$isValidValue && $pathFound) {
                return false;
            }
        }

        return true;
    }
}
