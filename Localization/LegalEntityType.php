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

use phpOMS\Stdlib\Base\Enum;

/**
 * Legal entity types.
 *
 * @package phpOMS\Localization
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class LegalEntityEnum extends Enum
{
    public const DEU_EINZELUNTERNEHMEN = 1;

    public const DEU_KAUFMANN = 2;

    public const DEU_GBR = 3;

    public const DEU_OHG = 4;

    public const DEU_KG = 5;

    public const DEU_KGAA = 6;

    public const DEU_GMBH = 7;

    public const DEU_UG = 8;

    public const DEU_GMBH_CO_KG = 9;

    public const DEU_GMBH_CO_KGAA = 10;

    public const DEU_AG = 11;

    public const DEU_AG_CO_KG = 12;

    public const DEU_AG_CO_KGAA = 13;

    public const DEU_SE_CO_KGAA = 14;

    public const DEU_GMBH_CO_OHG = 15;

    public const DEU_PARTG = 16;

    public const DEU_PARTGMBBH = 17;

    public const DEU_EV = 18;

    public const DEU_RV = 19;

    public const DEU_EG = 20;

    public const DEU_KOERPERSCHAFT_OEFFENTLICHEN_RECHTS = 21;

    public const DEU_STIFTUNG = 22;

    public const DEU_STIFTUNG_OEFFENTLICHEN_RECHTS = 23;

    public const DEU_ANSTALT_OEFFENTLICHEN_RECHTS = 24;
}
