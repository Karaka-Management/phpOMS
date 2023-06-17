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
 * ISO 3166 regional grouping
 *
 * @package phpOMS\Localization
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
trait ISO3166RegionTrait
{
    /**
     * Get countries in a region
     *
     * @param string $region Region name
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getRegion(string $region) : array
    {
        $region = \strtolower($region);

        switch ($region) {
            case 'europe':
                return \array_merge(
                    self::getRegion('north-europe'),
                    self::getRegion('south-europe'),
                    self::getRegion('east-europe'),
                    self::getRegion('west-europe')
                );
            case 'asia':
                return \array_merge(
                    self::getRegion('central-asia'),
                    self::getRegion('south-asia'),
                    self::getRegion('southeast-asia'),
                    self::getRegion('east-asia'),
                    self::getRegion('west-asia')
                );
            case 'america':
                return \array_merge(
                    self::getRegion('north-america'),
                    self::getRegion('south-america'),
                    self::getRegion('central-america'),
                    self::getRegion('caribbean')
                );
            case 'oceania':
                return \array_merge(
                    self::getRegion('australia'),
                    self::getRegion('polynesia'),
                    self::getRegion('melanesia'),
                    self::getRegion('micronesia'),
                    self::getRegion('antartica')
                );
            case 'africa':
                return \array_merge(
                    self::getRegion('north-africa'),
                    self::getRegion('south-africa'),
                    self::getRegion('east-africa'),
                    self::getRegion('west-africa'),
                    self::getRegion('central-africa')
                );
            case 'eu':
                return [
                    self::_AUT, self::_BEL, self::_BGR, self::_HRV, self::_CYP,
                    self::_CZE, self::_DNK, self::_EST, self::_FIN, self::_FRA,
                    self::_DEU, self::_GRC, self::_HUN, self::_IRL, self::_ITA,
                    self::_LVA, self::_LTU, self::_LUX, self::_MLT, self::_NLD,
                    self::_POL, self::_PRT, self::_ROU, self::_SVK, self::_SVN,
                    self::_ESP, self::_SWE,
                ];
            case 'euro':
                return [
                    self::_AUT, self::_BEL, self::_HRV, self::_CYP, self::_EST,
                    self::_FIN, self::_FRA, self::_DEU, self::_GRC, self::_IRL,
                    self::_ITA, self::_LVA, self::_LTU, self::_LUX, self::_MLT,
                    self::_NLD, self::_PRT, self::_SVK, self::_SVN, self::_ESP,
                ];
            case 'north-europe':
                return [
                    self::_ALA, self::_DNK, self::_EST, self::_FRO, self::_FIN,
                    self::_GGY, self::_ISL, self::_IRL, self::_IMN, self::_JEY,
                    self::_LVA, self::_LTU, self::_NOR, self::_SJM, self::_SWE,
                    self::_GBR,
                ];
            case 'south-europe':
                return [
                    self::_ALB, self::_AND, self::_BIH, self::_HRV, self::_GIB,
                    self::_GRC, self::_ITA, self::_XXK, self::_MLT, self::_MNE,
                    self::_MKD, self::_PRT, self::_SMR, self::_SRB, self::_SVN,
                    self::_ESP, self::_VAT,
                ];
            case 'east-europe':
                return [
                    self::_BLR, self::_BGR, self::_CZE, self::_HUN, self::_MDA,
                    self::_POL, self::_ROU, self::_RUS, self::_SVK, self::_UKR,
                ];
            case 'west-europe':
                return [
                    self::_AUT, self::_BEL, self::_FRA, self::_DEU, self::_LIE,
                    self::_LUX, self::_NLD, self::_MCO, self::_CHE,
                ];
            case 'middle-east':
                return [
                    self::_BHR, self::_CYP, self::_EGY, self::_IRN, self::_IRQ,
                    self::_ISR, self::_JOR, self::_KWT, self::_LBN, self::_OMN,
                    self::_PSE, self::_QAT, self::_SAU, self::_SYR, self::_TUR,
                    self::_ARE, self::_YEM,
                ];
            case 'south-america':
                return [
                    self::_ARG, self::_BOL, self::_BVT, self::_BRA, self::_CHL,
                    self::_COL, self::_ECU, self::_FLK, self::_GUF, self::_GUY,
                    self::_PRY, self::_PER, self::_SGS, self::_SUR, self::_URY,
                    self::_VEN,
                ];
            case 'north-america':
                return [
                    self::_BMU, self::_CAN, self::_GRL, self::_SPM, self::_USA,
                ];
            case 'central-america':
                return [
                    self::_BLZ, self::_CRI, self::_SLV, self::_HND, self::_MEX,
                    self::_NIC, self::_PAN, self::_GTM,
                ];
            case 'caribbean':
                return [
                    self::_ATG, self::_ABW, self::_BHS, self::_BRB, self::_VGB,
                    self::_CYM, self::_CUB, self::_CUW, self::_DMA, self::_VIR,
                    self::_DOM, self::_GRD, self::_GLP, self::_HTI, self::_JAM,
                    self::_MTQ, self::_MSR, self::_PRI, self::_BLM, self::_KNA,
                    self::_LCA, self::_MAF, self::_SXM, self::_VCT, self::_TTO,
                    self::_TCA, self::_AIA, self::_BES,
                ];
            case 'central-asia':
                return [
                    self::_KAZ, self::_KGZ, self::_TJK, self::_TKM, self::_UZB,
                ];
            case 'south-asia':
                return [
                    self::_AFG, self::_BGD, self::_BTN, self::_IND, self::_IRN,
                    self::_MDV, self::_NPL, self::_PAK, self::_LKA,
                ];
            case 'southeast-asia':
                return [
                    self::_BRN, self::_KHM, self::_TLS, self::_IDN, self::_LAO,
                    self::_MYS, self::_MMR, self::_PHL, self::_SGP, self::_THA,
                    self::_VNM,
                ];
            case 'east-asia':
                return [
                    self::_CHN, self::_HKG, self::_JPN, self::_MAC, self::_MNG,
                    self::_KOR, self::_PRK, self::_TWN,
                ];
            case 'west-asia':
                return [
                    self::_ARM, self::_AZE, self::_BHR, self::_CYP, self::_GEO,
                    self::_IRQ, self::_ISR, self::_JOR, self::_KWT, self::_LBN,
                    self::_OMN, self::_PSE, self::_QAT, self::_SAU, self::_SYR,
                    self::_TUR, self::_ARE, self::_YEM,
                ];
            case 'central-africa':
                return [
                    self::_AGO, self::_CMR, self::_CAF, self::_TCD, self::_COD,
                    self::_GNQ, self::_GAB, self::_COG, self::_STP,
                ];
            case 'south-africa':
                return [
                    self::_BWA, self::_SWZ, self::_LSO, self::_NAM, self::_ZAF,
                ];
            case 'north-africa':
                return [
                    self::_DZA, self::_EGY, self::_LBY, self::_MAR, self::_SSD,
                    self::_SDN, self::_TUN, self::_ESH,
                ];
            case 'east-africa':
                return [
                    self::_IOT, self::_BDI, self::_COM, self::_DJI, self::_ERI,
                    self::_ETH, self::_KEN, self::_MDG, self::_MWI, self::_MUS,
                    self::_MYT, self::_MOZ, self::_RWA, self::_SYC, self::_SOM,
                    self::_TZA, self::_UGA, self::_ZMB, self::_ZWE, self::_REU,
                ];
            case 'west-africa':
                return [
                    self::_BEN, self::_BFA, self::_CPV, self::_GMB, self::_GHA,
                    self::_GIN, self::_GNB, self::_CIV, self::_LBR, self::_MLI,
                    self::_MRT, self::_NER, self::_NGA, self::_SHN, self::_SEN,
                    self::_SLE, self::_TGO,
                ];
            case 'australia':
                return [
                    self::_AUS, self::_CXR, self::_CCK, self::_HMD, self::_NZL,
                    self::_NFK,
                ];
            case 'polynesia':
                return [
                    self::_WSM, self::_COK, self::_PYF, self::_NIU, self::_PCN,
                    self::_WSM, self::_TKL, self::_TON, self::_TUV, self::_WLF,
                ];
            case 'melanesia':
                return [
                    self::_FJI, self::_NCL, self::_PNG, self::_SLB, self::_VUT,
                ];
            case 'micronesia':
                return [
                    self::_FSM, self::_GUM, self::_KIR, self::_MHL, self::_NRU,
                    self::_MNP, self::_PLW, self::_UMI,
                ];
            case 'antarctica':
                return [
                    self::_ATA, self::_ATF,
                ];
            default:
                return [];
        }
    }
}
