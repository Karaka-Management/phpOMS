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
namespace phpOMS\Utils\IO\Zip;
/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Gz implements ArchiveInterface
{
    /**
     * Create zip.
     *
     * @param string   $sources     Files and directories to compress
     * @param string   $destination Output destination
     * @param bool     $overwrite   Overwrite if destination is existing
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function pack(string $source, string $destination, bool $overwrite = true) : bool
    {
        $destination = str_replace('\\', '/', realpath($destination));
        if (!$overwrite && file_exists($destination)) {
            return false;
        }
        
        if(($gz = gzopen($destination, 'w')) === false) {
            return false;
        }
        
        $src = fopen($source, 'r');
        while(!feof($src)) {
            gzwrite($gz, fgets($src));
        }
        
        fclose($src);
        
        return gzclose($gz);
    }
    
    public static function unpack(string $source, string $destination) : bool
    {
        $destination = str_replace('\\', '/', realpath($destination));
        if (!$overwrite && file_exists($destination)) {
            return false;
        }
        
        if(($gz = gzopen($source, 'w')) === false) {
            return false;
        }
        
        $dest = fopen($destination, 'w');
        while (!gzeof($handle)) {
            fwrite($dest, gzgets($handle, 4096));
        }
        
        fclose($dest);
        
        return gzclose($gz);
    }
}
