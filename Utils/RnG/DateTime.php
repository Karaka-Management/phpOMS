<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * DateTime generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
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
     * @since 1.0.0
     */
    public static function generateDateTime(\DateTime $start, \DateTime $end) : \DateTime
    {
        $rng = new \DateTime();

        return $rng->setTimestamp(\mt_rand($start->getTimestamp(), $end->getTimestamp()));
    }
}
