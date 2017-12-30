<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\Utils\RnG;

/**
 * DateTime generator.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class DateTime
{

    /**
     * Get a random \DateTime.
     *
     * @param \DateTime $start Start date
     * @param \DateTime $end   End date
     *
     * @return \DateTime
     *
     * @since  1.0.0
     */
    public static function generateDateTime(\DateTime $start, \DateTime $end) : \DateTime
    {
        $rng = new \DateTime();
        
        return $rng->setTimestamp(mt_rand($start->getTimestamp(), $end->getTimestamp()));
    }
}
