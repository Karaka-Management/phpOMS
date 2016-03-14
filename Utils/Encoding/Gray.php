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
namespace phpOMS\Utils\Encoding;

/**
 * Gray encoding class
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
final class Gray {
    /**
     * {@inheritdoc}
     */
    public static function encode(int $source) : int
    {
        return $source ^ ($source >> 1);
    }

    /**
     * {@inheritdoc}
     */
    public static function decode(int $gray) : int
    {
        $source = $gray;

        while($gray >>= 1) {
            $source ^= $gray;
        }

        return $source;
    }
}