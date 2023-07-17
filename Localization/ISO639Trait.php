<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization;

/**
 * ISO 639 country -> language trait.
 *
 * @package phpOMS\Localization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
trait ISO639Trait
{
    public static function getBy2Code(string $code)
    {
        return self::getByName('_' . \strtoupper($code));
    }

    /**
     * Get language from country.
     *
     * @param string $country Country 2 code
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function languageFromCountry(string $country) : array
    {
        switch (\strtoupper($country)) {
            case ISO3166TwoEnum::_AFG:
                return [self::_PS, self::_UZ, self::_TK];
            case ISO3166TwoEnum::_ATA:
                return [self::_RU, self::_EN];
            case ISO3166TwoEnum::_ALA:
                return [self::_SV];
            case ISO3166TwoEnum::_ALB:
                return [self::_SQ];
            case ISO3166TwoEnum::_DZA:
                return [self::_AR];
            case ISO3166TwoEnum::_ASM:
                return [self::_EN, self::_SM];
            case ISO3166TwoEnum::_AND:
                return [self::_CA];
            case ISO3166TwoEnum::_AGO:
                return [self::_PT];
            case ISO3166TwoEnum::_AIA:
                return [self::_EN];
            case ISO3166TwoEnum::_ATG:
                return [self::_EN];
            case ISO3166TwoEnum::_ARG:
                return [self::_ES, self::_GN];
            case ISO3166TwoEnum::_ARM:
                return [self::_HY, self::_RU];
            case ISO3166TwoEnum::_ABW:
                return [self::_NL];
            case ISO3166TwoEnum::_AUS:
                return [self::_EN];
            case ISO3166TwoEnum::_AUT:
                return [self::_DE];
            case ISO3166TwoEnum::_AZE:
                return [self::_AZ, self::_RU, self::_HY];
            case ISO3166TwoEnum::_BHS:
                return [self::_EN];
            case ISO3166TwoEnum::_BHR:
                return [self::_AR];
            case ISO3166TwoEnum::_BGD:
                return [self::_BN];
            case ISO3166TwoEnum::_BRB:
                return [self::_EN];
            case ISO3166TwoEnum::_BLR:
                return [self::_BE, self::_RU];
            case ISO3166TwoEnum::_BEL:
                return [self::_NL, self::_FR, self::_DE];
            case ISO3166TwoEnum::_BLZ:
                return [self::_EN];
            case ISO3166TwoEnum::_BEN:
                return [self::_FR];
            case ISO3166TwoEnum::_BMU:
                return [self::_EN];
            case ISO3166TwoEnum::_BTN:
                return [self::_DZ];
            case ISO3166TwoEnum::_BOL:
                return [self::_ES, self::_QU, self::_AY];
            case ISO3166TwoEnum::_BES:
                return [self::_NL, self::_EN];
            case ISO3166TwoEnum::_BIH:
                return [self::_BS, self::_HR, self::_SR];
            case ISO3166TwoEnum::_BWA:
                return [self::_EN, self::_TN];
            case ISO3166TwoEnum::_BVT:
                return [self::_NO];
            case ISO3166TwoEnum::_BRA:
                return [self::_PT];
            case ISO3166TwoEnum::_IOT:
                return [self::_EN];
            case ISO3166TwoEnum::_BRN:
                return [self::_MS];
            case ISO3166TwoEnum::_BGR:
                return [self::_BG];
            case ISO3166TwoEnum::_BFA:
                return [self::_FR];
            case ISO3166TwoEnum::_BDI:
                return [self::_RN, self::_FR];
            case ISO3166TwoEnum::_CPV:
                return [self::_PT];
            case ISO3166TwoEnum::_KHM:
                return [self::_KM];
            case ISO3166TwoEnum::_CMR:
                return [self::_FR, self::_EN];
            case ISO3166TwoEnum::_CAN:
                return [self::_EN, self::_FR];
            case ISO3166TwoEnum::_CYM:
                return [self::_EN];
            case ISO3166TwoEnum::_CAF:
                return [self::_FR, self::_SG];
            case ISO3166TwoEnum::_TCD:
                return [self::_FR, self::_AR];
            case ISO3166TwoEnum::_CHL:
                return [self::_ES];
            case ISO3166TwoEnum::_CHN:
                return [self::_ZH];
            case ISO3166TwoEnum::_CXR:
                return [self::_EN];
            case ISO3166TwoEnum::_CCK:
                return [self::_EN];
            case ISO3166TwoEnum::_COL:
                return [self::_ES];
            case ISO3166TwoEnum::_COM:
                return [self::_AR, self::_FR];
            case ISO3166TwoEnum::_COG:
                return [self::_FR, self::_LN, self::_KG, self::_SW];
            case ISO3166TwoEnum::_COD:
                return [self::_FR, self::_LN, self::_KG, self::_SW];
            case ISO3166TwoEnum::_COK:
                return [self::_EN];
            case ISO3166TwoEnum::_CRI:
                return [self::_ES];
            case ISO3166TwoEnum::_CIV:
                return [self::_FR];
            case ISO3166TwoEnum::_HRV:
                return [self::_HR];
            case ISO3166TwoEnum::_CUB:
                return [self::_ES];
            case ISO3166TwoEnum::_CUW:
                return [self::_NL, self::_PA, self::_EN];
            case ISO3166TwoEnum::_CYP:
                return [self::_EL, self::_TR];
            case ISO3166TwoEnum::_CZE:
                return [self::_CS, self::_SK];
            case ISO3166TwoEnum::_DNK:
                return [self::_DA];
            case ISO3166TwoEnum::_DJI:
                return [self::_FR, self::_AR, self::_SO];
            case ISO3166TwoEnum::_DMA:
                return [self::_EN];
            case ISO3166TwoEnum::_DOM:
                return [self::_ES];
            case ISO3166TwoEnum::_ECU:
                return [self::_ES];
            case ISO3166TwoEnum::_EGY:
                return [self::_AR];
            case ISO3166TwoEnum::_SLV:
                return [self::_ES];
            case ISO3166TwoEnum::_GNQ:
                return [self::_ES, self::_FR, self::_PT];
            case ISO3166TwoEnum::_ERI:
                return [self::_TI, self::_AR, self::_EN];
            case ISO3166TwoEnum::_EST:
                return [self::_ET];
            case ISO3166TwoEnum::_ETH:
                return [self::_AM, self::_OM, self::_TI, self::_SO, self::_AR];
            case ISO3166TwoEnum::_FLK:
                return [self::_EN];
            case ISO3166TwoEnum::_FRO:
                return [self::_FO];
            case ISO3166TwoEnum::_FJI:
                return [self::_EN, self::_FJ, self::_HI, self::_UR];
            case ISO3166TwoEnum::_FIN:
                return [self::_FI, self::_SV];
            case ISO3166TwoEnum::_FRA:
                return [self::_FR];
            case ISO3166TwoEnum::_GUF:
                return [self::_FR];
            case ISO3166TwoEnum::_PYF:
                return [self::_FR, self::_TY];
            case ISO3166TwoEnum::_ATF:
                return [self::_FR];
            case ISO3166TwoEnum::_GAB:
                return [self::_FR];
            case ISO3166TwoEnum::_GMB:
                return [self::_EN];
            case ISO3166TwoEnum::_GEO:
                return [self::_KA];
            case ISO3166TwoEnum::_DEU:
                return [self::_DE];
            case ISO3166TwoEnum::_GHA:
                return [self::_EN];
            case ISO3166TwoEnum::_GIB:
                return [self::_EN];
            case ISO3166TwoEnum::_GRC:
                return [self::_EL];
            case ISO3166TwoEnum::_GRL:
                return [self::_KL];
            case ISO3166TwoEnum::_GRD:
                return [self::_EN];
            case ISO3166TwoEnum::_GLP:
                return [self::_FR];
            case ISO3166TwoEnum::_GUM:
                return [self::_EN, self::_CH, self::_ES];
            case ISO3166TwoEnum::_GTM:
                return [self::_ES];
            case ISO3166TwoEnum::_GGY:
                return [self::_EN, self::_FR];
            case ISO3166TwoEnum::_GIN:
                return [self::_FR];
            case ISO3166TwoEnum::_GNB:
                return [self::_PT];
            case ISO3166TwoEnum::_GUY:
                return [self::_EN];
            case ISO3166TwoEnum::_HTI:
                return [self::_FR, self::_HT];
            case ISO3166TwoEnum::_HMD:
                return [self::_EN];
            case ISO3166TwoEnum::_VAT:
                return [self::_IT, self::_LA];
            case ISO3166TwoEnum::_HND:
                return [self::_ES];
            case ISO3166TwoEnum::_HKG:
                return [self::_ZH, self::_EN];
            case ISO3166TwoEnum::_HUN:
                return [self::_HU];
            case ISO3166TwoEnum::_ISL:
                return [self::_IS];
            case ISO3166TwoEnum::_IND:
                return [self::_HI, self::_EN];
            case ISO3166TwoEnum::_IDN:
                return [self::_ID];
            case ISO3166TwoEnum::_IRN:
                return [self::_FA];
            case ISO3166TwoEnum::_IRQ:
                return [self::_AR, self::_KU];
            case ISO3166TwoEnum::_IRL:
                return [self::_GA, self::_EN];
            case ISO3166TwoEnum::_IMN:
                return [self::_EN, self::_GV];
            case ISO3166TwoEnum::_ISR:
                return [self::_HE, self::_AR];
            case ISO3166TwoEnum::_ITA:
                return [self::_IT];
            case ISO3166TwoEnum::_JAM:
                return [self::_EN];
            case ISO3166TwoEnum::_JPN:
                return [self::_JA];
            case ISO3166TwoEnum::_JEY:
                return [self::_EN, self::_FR];
            case ISO3166TwoEnum::_JOR:
                return [self::_AR];
            case ISO3166TwoEnum::_KAZ:
                return [self::_KK, self::_RU];
            case ISO3166TwoEnum::_KEN:
                return [self::_SW, self::_EN];
            case ISO3166TwoEnum::_KIR:
                return [self::_EN];
            case ISO3166TwoEnum::_PRK:
                return [self::_KO];
            case ISO3166TwoEnum::_KOR:
                return [self::_KO];
            case ISO3166TwoEnum::_KWT:
                return [self::_AR];
            case ISO3166TwoEnum::_KGZ:
                return [self::_KY, self::_RU];
            case ISO3166TwoEnum::_LAO:
                return [self::_LO];
            case ISO3166TwoEnum::_LVA:
                return [self::_LV];
            case ISO3166TwoEnum::_LBN:
                return [self::_AR, self::_FR];
            case ISO3166TwoEnum::_LSO:
                return [self::_EN, self::_ST];
            case ISO3166TwoEnum::_LBR:
                return [self::_EN];
            case ISO3166TwoEnum::_LBY:
                return [self::_AR];
            case ISO3166TwoEnum::_LIE:
                return [self::_DE];
            case ISO3166TwoEnum::_LTU:
                return [self::_LT];
            case ISO3166TwoEnum::_LUX:
                return [self::_LB, self::_FR, self::_DE];
            case ISO3166TwoEnum::_MAC:
                return [self::_ZH, self::_PT];
            case ISO3166TwoEnum::_MDG:
                return [self::_MG, self::_FR];
            case ISO3166TwoEnum::_MWI:
                return [self::_NY, self::_EN];
            case ISO3166TwoEnum::_MYS:
                return [self::_MS];
            case ISO3166TwoEnum::_MDV:
                return [self::_DV];
            case ISO3166TwoEnum::_MLI:
                return [self::_FR];
            case ISO3166TwoEnum::_MLT:
                return [self::_MT, self::_EN];
            case ISO3166TwoEnum::_MKD:
                return [self::_MK];
            case ISO3166TwoEnum::_MHL:
                return [self::_MH, self::_EN];
            case ISO3166TwoEnum::_MTQ:
                return [self::_FR];
            case ISO3166TwoEnum::_MRT:
                return [self::_AR, self::_FR];
            case ISO3166TwoEnum::_MUS:
                return [self::_EN, self::_FR];
            case ISO3166TwoEnum::_MYT:
                return [self::_FR];
            case ISO3166TwoEnum::_MEX:
                return [self::_ES];
            case ISO3166TwoEnum::_FSM:
                return [self::_EN];
            case ISO3166TwoEnum::_MDA:
                return [self::_RO];
            case ISO3166TwoEnum::_MCO:
                return [self::_FR];
            case ISO3166TwoEnum::_MNG:
                return [self::_MN];
            case ISO3166TwoEnum::_MNE:
                return [self::_SR, self::_BS, self::_SQ, self::_HR];
            case ISO3166TwoEnum::_MSR:
                return [self::_EN];
            case ISO3166TwoEnum::_MAR:
                return [self::_AR];
            case ISO3166TwoEnum::_MOZ:
                return [self::_PT];
            case ISO3166TwoEnum::_MMR:
                return [self::_MY];
            case ISO3166TwoEnum::_NAM:
                return [self::_EN, self::_AF];
            case ISO3166TwoEnum::_NRU:
                return [self::_NA, self::_EN];
            case ISO3166TwoEnum::_NPL:
                return [self::_NE];
            case ISO3166TwoEnum::_NLD:
                return [self::_NL];
            case ISO3166TwoEnum::_NCL:
                return [self::_FR];
            case ISO3166TwoEnum::_NZL:
                return [self::_EN, self::_MI];
            case ISO3166TwoEnum::_NIC:
                return [self::_ES];
            case ISO3166TwoEnum::_NER:
                return [self::_FR];
            case ISO3166TwoEnum::_NGA:
                return [self::_EN];
            case ISO3166TwoEnum::_NIU:
                return [self::_EN];
            case ISO3166TwoEnum::_NFK:
                return [self::_EN];
            case ISO3166TwoEnum::_MNP:
                return [self::_EN, self::_CH];
            case ISO3166TwoEnum::_NOR:
                return [self::_NO, self::_NB, self::_NN];
            case ISO3166TwoEnum::_OMN:
                return [self::_AR];
            case ISO3166TwoEnum::_PAK:
                return [self::_UR, self::_EN];
            case ISO3166TwoEnum::_PLW:
                return [self::_EN, self::_JA, self::_ZH];
            case ISO3166TwoEnum::_PSE:
                return [self::_AR];
            case ISO3166TwoEnum::_PAN:
                return [self::_ES];
            case ISO3166TwoEnum::_PNG:
                return [self::_EN, self::_HO];
            case ISO3166TwoEnum::_PRY:
                return [self::_ES, self::_GN];
            case ISO3166TwoEnum::_PER:
                return [self::_ES, self::_QU, self::_AY];
            case ISO3166TwoEnum::_PHL:
                return [self::_EN];
            case ISO3166TwoEnum::_PCN:
                return [self::_EN];
            case ISO3166TwoEnum::_POL:
                return [self::_PL];
            case ISO3166TwoEnum::_PRT:
                return [self::_PT];
            case ISO3166TwoEnum::_PRI:
                return [self::_ES, self::_EN];
            case ISO3166TwoEnum::_QAT:
                return [self::_AR];
            case ISO3166TwoEnum::_REU:
                return [self::_FR];
            case ISO3166TwoEnum::_ROU:
                return [self::_RO];
            case ISO3166TwoEnum::_RUS:
                return [self::_RU];
            case ISO3166TwoEnum::_RWA:
                return [self::_RW, self::_EN, self::_FR];
            case ISO3166TwoEnum::_BLM:
                return [self::_FR];
            case ISO3166TwoEnum::_SHN:
                return [self::_EN];
            case ISO3166TwoEnum::_KNA:
                return [self::_EN];
            case ISO3166TwoEnum::_LCA:
                return [self::_EN];
            case ISO3166TwoEnum::_MAF:
                return [self::_FR, self::_EN, self::_NL];
            case ISO3166TwoEnum::_SPM:
                return [self::_FR];
            case ISO3166TwoEnum::_VCT:
                return [self::_EN];
            case ISO3166TwoEnum::_WSM:
                return [self::_SM, self::_EN];
            case ISO3166TwoEnum::_SMR:
                return [self::_IT];
            case ISO3166TwoEnum::_STP:
                return [self::_PT];
            case ISO3166TwoEnum::_SAU:
                return [self::_AR];
            case ISO3166TwoEnum::_SEN:
                return [self::_FR, self::_WO];
            case ISO3166TwoEnum::_SRB:
                return [self::_SR];
            case ISO3166TwoEnum::_SYC:
                return [self::_FR, self::_EN];
            case ISO3166TwoEnum::_SLE:
                return [self::_EN];
            case ISO3166TwoEnum::_SGP:
                return [self::_EN, self::_MS, self::_TA, self::_ZH];
            case ISO3166TwoEnum::_SXM:
                return [self::_NL, self::_EN];
            case ISO3166TwoEnum::_SVK:
                return [self::_SK];
            case ISO3166TwoEnum::_SVN:
                return [self::_SL];
            case ISO3166TwoEnum::_SLB:
                return [self::_EN];
            case ISO3166TwoEnum::_SOM:
                return [self::_SO, self::_AR, self::_IT, self::_EN];
            case ISO3166TwoEnum::_ZAF:
                return [self::_ZU, self::_XH, self::_AF, self::_EN, self::_TN, self::_ST, self::_TS, self::_SS, self::_VE];
            case ISO3166TwoEnum::_SGS:
                return [self::_EN];
            case ISO3166TwoEnum::_KOR:
                return [self::_KO];
            case ISO3166TwoEnum::_SSD:
                return [self::_EN];
            case ISO3166TwoEnum::_ESP:
                return [self::_ES];
            case ISO3166TwoEnum::_LKA:
                return [self::_SI, self::_TA, self::_EN];
            case ISO3166TwoEnum::_SDN:
                return [self::_AR, self::_EN];
            case ISO3166TwoEnum::_SUR:
                return [self::_NL];
            case ISO3166TwoEnum::_SJM:
                return [self::_NO];
            case ISO3166TwoEnum::_SWZ:
                return [self::_EN, self::_SS];
            case ISO3166TwoEnum::_SWE:
                return [self::_SV];
            case ISO3166TwoEnum::_CHE:
                return [self::_DE, self::_FR, self::_IT];
            case ISO3166TwoEnum::_SYR:
                return [self::_AR];
            case ISO3166TwoEnum::_TWN:
                return [self::_ZH];
            case ISO3166TwoEnum::_TJK:
                return [self::_TG, self::_RU];
            case ISO3166TwoEnum::_TZA:
                return [self::_SW, self::_EN];
            case ISO3166TwoEnum::_THA:
                return [self::_TH];
            case ISO3166TwoEnum::_TLS:
                return [self::_PT];
            case ISO3166TwoEnum::_TGO:
                return [self::_FR];
            case ISO3166TwoEnum::_TKL:
                return [self::_EN];
            case ISO3166TwoEnum::_TON:
                return [self::_EN, self::_TO];
            case ISO3166TwoEnum::_TTO:
                return [self::_EN];
            case ISO3166TwoEnum::_TUN:
                return [self::_AR];
            case ISO3166TwoEnum::_TUR:
                return [self::_TR];
            case ISO3166TwoEnum::_TKM:
                return [self::_TK, self::_RU];
            case ISO3166TwoEnum::_TCA:
                return [self::_EN];
            case ISO3166TwoEnum::_TUV:
                return [self::_EN];
            case ISO3166TwoEnum::_UGA:
                return [self::_EN, self::_SW];
            case ISO3166TwoEnum::_UKR:
                return [self::_UK];
            case ISO3166TwoEnum::_ARE:
                return [self::_AR];
            case ISO3166TwoEnum::_GBR:
                return [self::_EN, self::_CY, self::_GD, self::_GA];
            case ISO3166TwoEnum::_USA:
                return [self::_EN, self::_ES];
            case ISO3166TwoEnum::_UMI:
                return [self::_EN];
            case ISO3166TwoEnum::_URY:
                return [self::_ES];
            case ISO3166TwoEnum::_UZB:
                return [self::_UZ, self::_RU];
            case ISO3166TwoEnum::_VUT:
                return [self::_BI, self::_EN, self::_FR];
            case ISO3166TwoEnum::_VEN:
                return [self::_ES];
            case ISO3166TwoEnum::_VNM:
                return [self::_VI];
            case ISO3166TwoEnum::_VGB:
                return [self::_EN];
            case ISO3166TwoEnum::_VIR:
                return [self::_EN];
            case ISO3166TwoEnum::_WLF:
                return [self::_FR];
            case ISO3166TwoEnum::_ESH:
                return [self::_AR, self::_ES];
            case ISO3166TwoEnum::_YEM:
                return [self::_AR];
            case ISO3166TwoEnum::_ZMB:
                return [self::_EN];
            case ISO3166TwoEnum::_ZWE:
                return [self::_EN, self::_SN, self::_ND];
            default:
                return [];
        }
    }
}
