<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);
namespace phpOMS\Utils;
/**
 * Array utils.
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class TestUtils
{
    public static function setMember($obj, $name, $value) : bool
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));
        
        if(!$reflectionClass->hasProperty($name)) {
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
    
    public static function getMember($obj, $name) 
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));
        
        if(!$reflectionClass->hasProperty($name)) {
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
