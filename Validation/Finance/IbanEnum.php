<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Validation\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation\Finance;

use phpOMS\Stdlib\Base\Enum;

/**
 * Iban layout definition.
 *
 * @package phpOMS\Validation\Finance
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class IbanEnum extends Enum
{
    public const _AL = 'ALkk bbbs sssx cccc cccc cccc cccc';

    public const _AD = 'ADkk bbbb ssss cccc cccc cccc';

    public const _AT = 'ATkk bbbb bccc cccc cccc';

    public const _AZ = 'AZkk bbbb cccc cccc cccc cccc cccc ';

    public const _BH = 'BHkk bbbb cccc cccc cccc cc';

    public const _BE = 'BEkk bbbc cccc ccxx';

    public const _BA = 'BAkk bbbs sscc cccc ccxx';

    public const _BR = 'BRkk bbbb bbbb ssss sccc cccc ccct n';

    public const _BG = 'BGkk bbbb ssss ttcc cccc cc';

    public const _CR = 'CRkk bbbc cccc cccc cccc c';

    public const _HR = 'HRkk bbbb bbbc cccc cccc c';

    public const _CY = 'CYkk bbbs ssss cccc cccc cccc cccc';

    public const _CZ = 'CZkk bbbb ssss sscc cccc cccc';

    public const _DK = 'DKkk bbbb cccc cccc cc';

    public const _DO = 'DOkk bbbb cccc cccc cccc cccc cccc';

    public const _TL = 'TLkk bbbc cccc cccc cccc cxx';

    public const _EE = 'EEkk bbss cccc cccc cccx';

    public const _FO = 'FOkk bbbb cccc cccc cx';

    public const _FI = 'FIkk bbbb bbcc cccc cx';

    public const _FR = 'FRkk bbbb bsss sscc cccc cccc cxx';

    public const _GE = 'GEkk bbcc cccc cccc cccc cc';

    public const _DE = 'DEkk bbbb bbbb cccc cccc cc';

    public const _GI = 'GIkk bbbb cccc cccc cccc ccc';

    public const _GR = 'GRkk bbbs sssc cccc cccc cccc ccc';

    public const _GL = 'GLkk bbbb cccc cccc cc';

    public const _GT = 'GTkk bbbb mmtt cccc cccc cccc cccc';

    public const _HU = 'HUkk bbbs sssx cccc cccc cccc cccx';

    public const _IS = 'ISkk bbbb sscc cccc iiii iiii ii';

    public const _IE = 'IEkk aaaa bbbb bbcc cccc cc';

    public const _IL = 'ILkk bbbn nncc cccc cccc ccc';

    public const _IT = 'ITkk xbbb bbss sssc cccc cccc ccc';

    public const _JO = 'JOkk bbbb ssss cccc cccc cccc cccc cc';

    public const _KZ = 'KZkk bbbc cccc cccc cccc';

    public const _XK = 'XKkk bbbb cccc cccc cccc';

    public const _KW = 'KWkk bbbb cccc cccc cccc cccc cccc cc';

    public const _LV = 'LVkk bbbb cccc cccc cccc c';

    public const _LB = 'LBkk bbbb cccc cccc cccc cccc cccc';

    public const _LI = 'LIkk bbbb bccc cccc cccc c';

    public const _LT = 'LTkk bbbb bccc cccc cccc';

    public const _LU = 'LUkk bbbc cccc cccc cccc';

    public const _MK = 'MKkk bbbc cccc cccc cxx';

    public const _MT = 'MTkk bbbb ssss sccc cccc cccc cccc ccc';

    public const _MR = 'MRkk bbbb bsss sscc cccc cccc cxx';

    public const _MU = 'MUkk bbbb bbss cccc cccc cccc 000m mm';

    public const _MC = 'MCkk bbbb bsss sscc cccc cccc cxx';

    public const _MD = 'MDkk bbcc cccc cccc cccc cccc';

    public const _ME = 'MEkk bbbc cccc cccc cccc xx';

    public const _NL = 'NLkk bbbb cccc cccc cc';

    public const _NO = 'NOkk bbbb cccc ccx';

    public const _PK = 'PKkk bbbb cccc cccc cccc cccc';

    public const _PS = 'PSkk bbbb xxxx xxxx xccc cccc cccc c';

    public const _PL = 'PLkk bbbs sssx cccc cccc cccc cccc';

    public const _PT = 'PTkk bbbb ssss cccc cccc cccx x';

    public const _QA = 'QAkk bbbb cccc cccc cccc cccc cccc c';

    public const _RO = 'ROkk bbbb cccc cccc cccc cccc';

    public const _SM = 'SMkk xbbb bbss sssc cccc cccc ccc';

    public const _SA = 'SAkk bbcc cccc cccc cccc cccc';

    public const _RS = 'RSkk bbbc cccc cccc cccc xx';

    public const _SK = 'SKkk bbbb ssss sscc cccc cccc';

    public const _SI = 'SIkk bbss sccc cccc cxx';

    public const _ES = 'ESkk bbbb ssss xxcc cccc cccc';

    public const _SE = 'SEkk bbbc cccc cccc cccc cccc';

    public const _CH = 'CHkk bbbb bccc cccc cccc c';

    public const _TN = 'TNkk bbss sccc cccc cccc cccc';

    public const _TR = 'TRkk bbbb bxcc cccc cccc cccc cc';

    public const _UA = 'UAkk bbbb bbcc cccc cccc cccc cccc c';

    public const _AE = 'AEkk bbbc cccc cccc cccc ccc';

    public const _GB = 'GBkk bbbb ssss sscc cccc cc';

    public const _VG = 'VGkk bbbb cccc cccc cccc cccc';

    public const _SN = 'SNkk annn nnnn nnnn nnnn nnnn nnnn';

    public const _MZ = 'MZkk nnnn nnnn nnnn nnnn nnnn n';

    public const _ML = 'MLkk annn nnnn nnnn nnnn nnnn nnnn';

    public const _MG = 'MGkk nnnn nnnn nnnn nnnn nnnn nnn';

    public const _CI = 'CIkk annn nnnn nnnn nnnn nnnn nnnn';

    public const _IR = 'IRkk nnnn nnnn nnnn nnnn nnnn nn';

    public const _CV = 'CVkk nnnn nnnn nnnn nnnn nnnn n';

    public const _CM = 'CMkk nnnn nnnn nnnn nnnn nnnn nnn';

    public const _BI = 'BIkk nnnn nnnn nnnn';

    public const _BF = 'BFkk nnnn nnnn nnnn nnnn nnnn nnn';

    public const _BJ = 'BJkk annn nnnn nnnn nnnn nnnn nnnn';

    public const _AO = 'AOkk nnnn nnnn nnnn nnnn nnnn n';

    public const _DZ = 'DZkk nnnn nnnn nnnn nnnn nnnn';
}
