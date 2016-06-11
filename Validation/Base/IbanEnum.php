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
namespace phpOMS\Validation\Base;

use phpOMS\Datatypes\Enum;

/**
 * Country codes ISO list.
 *
 * @category   Framework
 * @package    phpOMS\Localization
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class IbanEnum extends Enum
{
    const C_AL = 'ALkk bbbs sssx cccc cccc cccc cccc';
    const C_AD = 'ADkk bbbb ssss cccc cccc cccc';
    const C_AT = 'ATkk bbbb bccc cccc cccc';
    const C_AZ = 'AZkk bbbb cccc cccc cccc cccc cccc ';
    const C_BH = 'BHkk bbbb cccc cccc cccc cc';
    const C_BE = 'BEkk bbbc cccc ccxx';
    const C_BA = 'BAkk bbbs sscc cccc ccxx';
    const C_BR = 'BRkk bbbb bbbb ssss sccc cccc ccct n';
    const C_BG = 'BGkk bbbb ssss ttcc cccc cc';
    const C_CR = 'CRkk bbbc cccc cccc cccc c';
    const C_HR = 'HRkk bbbb bbbc cccc cccc c';
    const C_CY = 'CYkk bbbs ssss cccc cccc cccc cccc';
    const C_CZ = 'CZkk bbbb ssss sscc cccc cccc';
    const C_DK = 'DKkk bbbb cccc cccc cc';
    const C_DO = 'DOkk bbbb cccc cccc cccc cccc cccc';
    const C_TL = 'TLkk bbbc cccc cccc cccc cxx';
    const C_EE = 'EEkk bbss cccc cccc cccx';
    const C_FO = 'FOkk bbbb cccc cccc cx';
    const C_FI = 'FIkk bbbb bbcc cccc cx';
    const C_FR = 'FRkk bbbb bsss sscc cccc cccc cxx';
    const C_GE = 'GEkk bbcc cccc cccc cccc cc';
    const C_DE = 'DEkk bbbb bbbb cccc cccc cc';
    const C_GI = 'GIkk bbbb cccc cccc cccc ccc';
    const C_GR = 'GRkk bbbs sssc cccc cccc cccc ccc';
    const C_GL = 'GLkk bbbb cccc cccc cc';
    const C_GT = 'GTkk bbbb mmtt cccc cccc cccc cccc';
    const C_HU = 'HUkk bbbs sssk cccc cccc cccc cccx';
    const C_IS = 'ISkk bbbb sscc cccc iiii iiii ii';
    const C_IE = 'IEkk aaaa bbbb bbcc cccc cc';
    const C_IL = 'ILkk bbbn nncc cccc cccc ccc';
    const C_IT = 'ITkk xbbb bbss sssc cccc cccc ccc';
    const C_JO = 'JOkk bbbb ssss cccc cccc cccc cccc cc';
    const C_KZ = 'KZkk bbbc cccc cccc cccc';
    const C_XK = 'XKkk bbbb cccc cccc cccc';
    const C_KW = 'KWkk bbbb cccc cccc cccc cccc cccc cc';
    const C_LV = 'LVkk bbbb cccc cccc cccc c';
    const C_LB = 'LBkk bbbb cccc cccc cccc cccc cccc';
    const C_LI = 'LIkk bbbb bccc cccc cccc c';
    const C_LT = 'LTkk bbbb bccc cccc cccc';
    const C_LU = 'LUkk bbbc cccc cccc cccc';
    const C_MK = 'MKkk bbbc cccc cccc cxx';
    const C_MT = 'MTkk bbbb ssss sccc cccc cccc cccc ccc';
    const C_MR = 'MRkk bbbb bsss sscc cccc cccc cxx';
    const C_MU = 'MUkk bbbb bbss cccc cccc cccc 000m mm';
    const C_MC = 'MCkk bbbb bsss sscc cccc cccc cxx';
    const C_MD = 'MDkk bbcc cccc cccc cccc cccc';
    const C_ME = 'MEkk bbbc cccc cccc cccc xx';
    const C_NL = 'NLkk bbbb cccc cccc cc';
    const C_NO = 'NOkk bbbb cccc ccx';
    const C_PK = 'PKkk bbbb cccc cccc cccc cccc';
    const C_PS = 'PSkk bbbb xxxx xxxx xccc cccc cccc c';
    const C_PL = 'PLkk bbbs sssx cccc cccc cccc cccc';
    const C_PT = 'PTkk bbbb ssss cccc cccc cccx x';
    const C_QA = 'QAkk bbbb cccc cccc cccc cccc cccc c';
    const C_RO = 'ROkk bbbb cccc cccc cccc cccc';
    const C_SM = 'SMkk xbbb bbss sssc cccc cccc ccc';
    const C_SA = 'SAkk bbcc cccc cccc cccc cccc';
    const C_RS = 'RSkk bbbc cccc cccc cccc xx';
    const C_SK = 'SKkk bbbb ssss sscc cccc cccc';
    const C_SI = 'SIkk bbss sccc cccc cxx';
    const C_ES = 'ESkk bbbb ssss xxcc cccc cccc';
    const C_SE = 'SEkk bbbc cccc cccc cccc cccc';
    const C_CH = 'CHkk bbbb bccc cccc cccc c';
    const C_TN = 'TNkk bbss sccc cccc cccc cccc';
    const C_TR = 'TRkk bbbb bxcc cccc cccc cccc cc';
    const C_UA = 'UAkk bbbb bbcc cccc cccc cccc cccc c';
    const C_AE = 'AEkk bbbc cccc cccc cccc ccc';
    const C_GB = 'GBkk bbbb ssss sscc cccc cc';
    const C_VG = 'VGkk bbbb cccc cccc cccc cccc';
    const C_SN = 'SNkk annn nnnn nnnn nnnn nnnn nnnn';
    const C_MZ = 'MZkk nnnn nnnn nnnn nnnn nnnn n';
    const C_ML = 'MLkk annn nnnn nnnn nnnn nnnn nnnn';
    const C_MG = 'MGkk nnnn nnnn nnnn nnnn nnnn nnn';
    const C_CI = 'CIkk annn nnnn nnnn nnnn nnnn nnnn';
    const C_IR = 'IRkk nnnn nnnn nnnn nnnn nnnn nn';
    const C_CV = 'CVkk nnnn nnnn nnnn nnnn nnnn n';
    const C_CM = 'CMkk nnnn nnnn nnnn nnnn nnnn nnn';
    const C_BI = 'BIkk nnnn nnnn nnnn';
    const C_BF = 'BFkk nnnn nnnn nnnn nnnn nnnn nnn';
    const C_BJ = 'BJkk annn nnnn nnnn nnnn nnnn nnnn';
    const C_AO = 'AOkk nnnn nnnn nnnn nnnn nnnn n';
    const C_DZ = 'DZkk nnnn nnnn nnnn nnnn nnnn';
}
