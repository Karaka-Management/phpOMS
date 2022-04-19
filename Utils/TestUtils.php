<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * Test utils.
 *
 * Only for testing purposes. MUST NOT be used for other purposes.
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class TestUtils
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Set private object member
     *
     * @param object $obj   Object to modify
     * @param string $name  Member name to modify
     * @param mixed  $value Value to set
     *
     * @return bool The function returns true after setting the member
     *
     * @since 1.0.0
     */
    public static function setMember(object $obj, string $name, mixed $value) : bool
    {
        $reflectionClass = new \ReflectionClass(\get_class($obj));

        if (!$reflectionClass->hasProperty($name)) {
            return false;
        }

        $reflectionProperty = $reflectionClass->getProperty($name);

        if (!($accessible = $reflectionProperty->isPublic())) {
            $reflectionProperty->setAccessible(true);
        }

        $reflectionProperty->setValue($obj, $value);

        if (!$accessible) {
            $reflectionProperty->setAccessible(false);
        }

        return true;
    }

    /**
     * Get private object member
     *
     * @param object $obj  Object to read
     * @param string $name Member name to read
     *
     * @return mixed Returns the member variable value
     *
     * @since 1.0.0
     */
    public static function getMember(object $obj, string $name) : mixed
    {
        $reflectionClass = new \ReflectionClass($obj);

        if (!$reflectionClass->hasProperty($name)) {
            return null;
        }

        $reflectionProperty = $reflectionClass->getProperty($name);

        if (!($accessible = $reflectionProperty->isPublic())) {
            $reflectionProperty->setAccessible(true);
        }

        $value = $reflectionProperty->getValue($obj);

        if (!$accessible) {
            $reflectionProperty->setAccessible(false);
        }

        return $value;
    }
}
