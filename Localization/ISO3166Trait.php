<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
trait ISO3166Trait
{
    /**
     * Get value by 2 code
     *
     * @param string $code 2-code
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getBy2Code(string $code) : mixed
    {
        /** @var string $code3 */
        $code3 = ISO3166TwoEnum::getName($code);
        if ($code3 === false) {
            $code3 = '';
        }

        return self::getByName($code3);
    }

    /**
     * Get country from language.
     *
     * @param string $language Language 2 code
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function countryFromLanguage(string $language) : array
    {
        switch (\strtolower($language)) {
            case ISO639x1Enum::_PS:
                return [
                    self::_AFG,
                ];
            case ISO639x1Enum::_UZ:
                return [
                    self::_AFG, self::_UZB,
                ];
            case ISO639x1Enum::_TK:
                return [
                    self::_AFG, self::_TKM,
                ];
            case ISO639x1Enum::_SV:
                return [
                    self::_ALA, self::_FIN, self::_SWE,
                ];
            case ISO639x1Enum::_SQ:
                return [
                    self::_ALB, self::_MNE,
                ];
            case ISO639x1Enum::_AR:
                return [
                    self::_DZA, self::_BHR, self::_TCD, self::_COM, self::_DJI, self::_EGY, self::_ERI, self::_ETH, self::_IRQ, self::_ISR, self::_JOR, self::_KWT, self::_LBN, self::_LBY, self::_MRT, self::_MAR, self::_OMN, self::_PSE, self::_QAT, self::_SAU, self::_SOM, self::_SDN, self::_SYR, self::_TUN, self::_ARE, self::_ESH, self::_YEM,
                ];
            case ISO639x1Enum::_EN:
                return [
                    self::_USA, self::_ASM, self::_AIA, self::_ATA, self::_ATG, self::_AUS, self::_BHS, self::_BRB, self::_BLZ, self::_BMU, self::_BES, self::_BWA, self::_IOT, self::_CMR, self::_CAN, self::_CYM, self::_CXR, self::_CCK, self::_COK, self::_CUW, self::_DMA, self::_ERI, self::_FLK, self::_FJI, self::_GMB, self::_GHA, self::_GIB, self::_GRD, self::_GUM, self::_GGY, self::_GUY, self::_HMD, self::_HKG, self::_IND, self::_IRL, self::_IMN, self::_JAM, self::_JEY, self::_KEN, self::_KIR, self::_LSO, self::_LBR, self::_MWI, self::_MLT, self::_MHL, self::_MUS, self::_FSM, self::_MSR, self::_NAM, self::_NRU, self::_NZL, self::_NGA, self::_NIU, self::_NFK, self::_MNP, self::_PAK, self::_PLW, self::_PNG, self::_PHL, self::_PCN, self::_PRI, self::_RWA, self::_SHN, self::_KNA, self::_LCA, self::_MAF, self::_VCT, self::_WSM, self::_SYC, self::_SLE, self::_SGP, self::_SXM, self::_SLB, self::_SOM, self::_ZAF, self::_SGS, self::_SSD, self::_LKA, self::_SDN, self::_SWZ, self::_TZA, self::_TKL, self::_TON, self::_TTO, self::_TCA, self::_TUV, self::_UGA, self::_GBR, self::_UMI, self::_VUT, self::_VGB, self::_VIR, self::_ZMB, self::_ZWE,
                ];
            case ISO639x1Enum::_SM:
                return [
                    self::_ASM, self::_WSM,
                ];
            case ISO639x1Enum::_CA:
                return [
                    self::_AND,
                ];
            case ISO639x1Enum::_PT:
                return [
                    self::_AGO, self::_BRA, self::_CPV, self::_GNQ, self::_GNB, self::_MAC, self::_MOZ, self::_PRT, self::_STP, self::_TLS,
                ];
            case ISO639x1Enum::_RU:
                return [
                    self::_ATA, self::_ARM, self::_AZE, self::_BLR, self::_KAZ, self::_KGZ, self::_RUS, self::_TJK, self::_TKM, self::_UZB,
                ];
            case ISO639x1Enum::_ES:
                return [
                    self::_ARG, self::_BOL, self::_CHL, self::_COL, self::_CRI, self::_CUB, self::_DOM, self::_ECU, self::_SLV, self::_GNQ, self::_GUM, self::_GTM, self::_HND, self::_MEX, self::_NIC, self::_PAN, self::_PRY, self::_PER, self::_PRI, self::_ESP, self::_USA, self::_URY, self::_VEN, self::_ESH,
                ];
            case ISO639x1Enum::_GN:
                return [
                    self::_ARG, self::_PRY,
                ];
            case ISO639x1Enum::_HY:
                return [
                    self::_ARM, self::_AZE,
                ];
            case ISO639x1Enum::_NL:
                return [
                    self::_ABW, self::_BEL, self::_BES, self::_CUW, self::_NLD, self::_MAF, self::_SXM, self::_SUR,
                ];
            case ISO639x1Enum::_DE:
                return [
                    self::_DEU, self::_AUT, self::_BEL, self::_LIE, self::_LUX, self::_CHE,
                ];
            case ISO639x1Enum::_AZ:
                return [
                    self::_AZE,
                ];
            case ISO639x1Enum::_BN:
                return [
                    self::_BGD,
                ];
            case ISO639x1Enum::_BE:
                return [
                    self::_BLR,
                ];
            case ISO639x1Enum::_FR:
                return [
                    self::_BEL, self::_BEN, self::_BFA, self::_BDI, self::_CMR, self::_CAN, self::_CAF, self::_TCD, self::_COM, self::_COG, self::_COD, self::_CIV, self::_DJI, self::_GNQ, self::_FRA, self::_GUF, self::_PYF, self::_ATF, self::_GAB, self::_GLP, self::_GGY, self::_GIN, self::_HTI, self::_JEY, self::_LBN, self::_LUX, self::_MDG, self::_MLI, self::_MTQ, self::_MRT, self::_MUS, self::_MYT, self::_MCO, self::_NCL, self::_NER, self::_REU, self::_RWA, self::_BLM, self::_MAF, self::_SPM, self::_SEN, self::_SYC, self::_CHE, self::_TGO, self::_VUT, self::_WLF,
                ];
            case ISO639x1Enum::_DZ:
                return [
                    self::_BTN,
                ];
            case ISO639x1Enum::_QU:
                return [
                    self::_BOL, self::_PER,
                ];
            case ISO639x1Enum::_AY:
                return [
                    self::_BOL, self::_PER,
                ];
            case ISO639x1Enum::_BS:
                return [
                    self::_BIH, self::_MNE, self::_XXK,
                ];
            case ISO639x1Enum::_HR:
                return [
                    self::_BIH, self::_HRV, self::_MNE,
                ];
            case ISO639x1Enum::_SR:
                return [
                    self::_BIH, self::_MNE, self::_SRB,
                ];
            case ISO639x1Enum::_TN:
                return [
                    self::_BWA, self::_ZAF,
                ];
            case ISO639x1Enum::_NO:
                return [
                    self::_BVT, self::_NOR, self::_SJM,
                ];
            case ISO639x1Enum::_MS:
                return [
                    self::_BRN, self::_MYS, self::_SGP,
                ];
            case ISO639x1Enum::_BG:
                return [
                    self::_BGR,
                ];
            case ISO639x1Enum::_RN:
                return [
                    self::_BDI,
                ];
            case ISO639x1Enum::_KM:
                return [
                    self::_KHM,
                ];
            case ISO639x1Enum::_SG:
                return [
                    self::_CAF,
                ];
            case ISO639x1Enum::_ZH:
                return [
                    self::_CHN, self::_HKG, self::_MAC, self::_PLW, self::_SGP, self::_TWN,
                ];
            case ISO639x1Enum::_LN:
                return [
                    self::_COG, self::_COD,
                ];
            case ISO639x1Enum::_KG:
                return [
                    self::_COG, self::_COD,
                ];
            case ISO639x1Enum::_SW:
                return [
                    self::_COG, self::_COD, self::_KEN, self::_TZA, self::_UGA,
                ];
            case ISO639x1Enum::_PA:
                return [
                    self::_CUW,
                ];
            case ISO639x1Enum::_EL:
                return [
                    self::_CYP, self::_GRC,
                ];
            case ISO639x1Enum::_TR:
                return [
                    self::_CYP, self::_TUR, self::_XXK,
                ];
            case ISO639x1Enum::_CS:
                return [
                    self::_CZE,
                ];
            case ISO639x1Enum::_SK:
                return [
                    self::_CZE, self::_SVK,
                ];
            case ISO639x1Enum::_DA:
                return [
                    self::_DNK,
                ];
            case ISO639x1Enum::_SO:
                return [
                    self::_DJI, self::_ETH, self::_SOM,
                ];
            case ISO639x1Enum::_TI:
                return [
                    self::_ERI, self::_ETH,
                ];
            case ISO639x1Enum::_ET:
                return [
                    self::_EST,
                ];
            case ISO639x1Enum::_AM:
                return [
                    self::_ETH,
                ];
            case ISO639x1Enum::_OM:
                return [
                    self::_ETH,
                ];
            case ISO639x1Enum::_FO:
                return [
                    self::_FRO,
                ];
            case ISO639x1Enum::_FJ:
                return [
                    self::_FJI,
                ];
            case ISO639x1Enum::_HI:
                return [
                    self::_FJI, self::_IND,
                ];
            case ISO639x1Enum::_UR:
                return [
                    self::_FJI, self::_PAK,
                ];
            case ISO639x1Enum::_FI:
                return [
                    self::_FIN,
                ];
            case ISO639x1Enum::_TY:
                return [
                    self::_PYF,
                ];
            case ISO639x1Enum::_KA:
                return [
                    self::_GEO,
                ];
            case ISO639x1Enum::_KL:
                return [
                    self::_GRL,
                ];
            case ISO639x1Enum::_CH:
                return [
                    self::_GUM, self::_MNP,
                ];
            case ISO639x1Enum::_HT:
                return [
                    self::_HTI,
                ];
            case ISO639x1Enum::_IT:
                return [
                    self::_VAT, self::_ITA, self::_SMR, self::_SOM, self::_CHE,
                ];
            case ISO639x1Enum::_LA:
                return [
                    self::_VAT,
                ];
            case ISO639x1Enum::_HU:
                return [
                    self::_HUN,
                ];
            case ISO639x1Enum::_IS:
                return [
                    self::_ISL,
                ];
            case ISO639x1Enum::_ID:
                return [
                    self::_IDN,
                ];
            case ISO639x1Enum::_FA:
                return [
                    self::_IRN,
                ];
            case ISO639x1Enum::_KU:
                return [
                    self::_IRQ,
                ];
            case ISO639x1Enum::_GA:
                return [
                    self::_IRL, self::_GBR,
                ];
            case ISO639x1Enum::_GV:
                return [
                    self::_IMN,
                ];
            case ISO639x1Enum::_HE:
                return [
                    self::_ISR,
                ];
            case ISO639x1Enum::_JA:
                return [
                    self::_JPN, self::_PLW,
                ];
            case ISO639x1Enum::_KK:
                return [
                    self::_KAZ,
                ];
            case ISO639x1Enum::_KO:
                return [
                    self::_PRK, self::_KOR,
                ];
            case ISO639x1Enum::_KY:
                return [
                    self::_KGZ,
                ];
            case ISO639x1Enum::_LO:
                return [
                    self::_LAO,
                ];
            case ISO639x1Enum::_LV:
                return [
                    self::_LVA,
                ];
            case ISO639x1Enum::_ST:
                return [
                    self::_LSO, self::_ZAF,
                ];
            case ISO639x1Enum::_LT:
                return [
                    self::_LTU,
                ];
            case ISO639x1Enum::_LB:
                return [
                    self::_LUX,
                ];
            case ISO639x1Enum::_MK:
                return [
                    self::_MKD,
                ];
            case ISO639x1Enum::_MG:
                return [
                    self::_MDG,
                ];
            case ISO639x1Enum::_NY:
                return [
                    self::_MWI,
                ];
            case ISO639x1Enum::_DV:
                return [
                    self::_MDV,
                ];
            case ISO639x1Enum::_MT:
                return [
                    self::_MLT,
                ];
            case ISO639x1Enum::_MH:
                return [
                    self::_MHL,
                ];
            case ISO639x1Enum::_RO:
                return [
                    self::_MDA, self::_ROU,
                ];
            case ISO639x1Enum::_MN:
                return [
                    self::_MNG,
                ];
            case ISO639x1Enum::_MY:
                return [
                    self::_MMR,
                ];
            case ISO639x1Enum::_AF:
                return [
                    self::_NAM, self::_ZAF,
                ];
            case ISO639x1Enum::_NA:
                return [
                    self::_NRU,
                ];
            case ISO639x1Enum::_NE:
                return [
                    self::_NPL,
                ];
            case ISO639x1Enum::_MI:
                return [
                    self::_NZL,
                ];
            case ISO639x1Enum::_NB:
                return [
                    self::_NOR,
                ];
            case ISO639x1Enum::_NN:
                return [
                    self::_NOR,
                ];
            case ISO639x1Enum::_HO:
                return [
                    self::_PNG,
                ];
            case ISO639x1Enum::_PL:
                return [
                    self::_POL,
                ];
            case ISO639x1Enum::_RW:
                return [
                    self::_RWA,
                ];
            case ISO639x1Enum::_WO:
                return [
                    self::_SEN,
                ];
            case ISO639x1Enum::_TA:
                return [
                    self::_SGP, self::_LKA,
                ];
            case ISO639x1Enum::_SL:
                return [
                    self::_SVN,
                ];
            case ISO639x1Enum::_ZU:
                return [
                    self::_ZAF,
                ];
            case ISO639x1Enum::_XH:
                return [
                    self::_ZAF,
                ];
            case ISO639x1Enum::_TS:
                return [
                    self::_ZAF,
                ];
            case ISO639x1Enum::_SS:
                return [
                    self::_ZAF, self::_SWZ,
                ];
            case ISO639x1Enum::_VE:
                return [
                    self::_ZAF,
                ];
            case ISO639x1Enum::_SI:
                return [
                    self::_LKA,
                ];
            case ISO639x1Enum::_TG:
                return [
                    self::_TJK,
                ];
            case ISO639x1Enum::_TH:
                return [
                    self::_THA,
                ];
            case ISO639x1Enum::_TO:
                return [
                    self::_TON,
                ];
            case ISO639x1Enum::_UK:
                return [
                    self::_UKR,
                ];
            case ISO639x1Enum::_CY:
                return [
                    self::_GBR,
                ];
            case ISO639x1Enum::_GD:
                return [
                    self::_GBR,
                ];
            case ISO639x1Enum::_BI:
                return [
                    self::_VUT,
                ];
            case ISO639x1Enum::_VI:
                return [
                    self::_VNM,
                ];
            case ISO639x1Enum::_SN:
                return [
                    self::_ZWE,
                ];
            case ISO639x1Enum::_ND:
                return [
                    self::_ZWE,
                ];
            default:
                return [];
        }
    }

    /**
     * Get countries in a region
     *
     * @param string $region Region name
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getSubregions(string $region) : array
    {
        $region = \strtolower($region);

        switch ($region) {
            case 'continents':
                return ['Europe', 'Asia', 'America', 'Oceania', 'Africa'];
            case 'europe':
                return ['North-Europe', 'South-Europe', 'East-Europe', 'West-Europe'];
            case 'asia':
                return ['Central-Asia', 'South-Asia', 'Southeast-Asia', 'East-Asia', 'West-Asia'];
            case 'america':
                return ['North-america', 'South-america', 'Central-america', 'Caribbean'];
            case 'oceania':
                return ['Australia', 'Polynesia', 'Melanesia', 'Micronesia', 'Antarctica'];
            case 'africa':
                return ['North-Africa', 'South-Africa', 'East-Africa', 'West-Africa', 'Central-Africa'];
            default:
                return [$region];
        }
    }

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
            case 'continents':
                return \array_merge(
                    self::getRegion('europe'),
                    self::getRegion('asia'),
                    self::getRegion('america'),
                    self::getRegion('oceania'),
                    self::getRegion('africa')
                );
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
                    self::getRegion('antarctica')
                );
            case 'africa':
                return \array_merge(
                    self::getRegion('north-africa'),
                    self::getRegion('south-africa'),
                    self::getRegion('east-africa'),
                    self::getRegion('west-africa'),
                    self::getRegion('central-africa')
                );
            case 'nato':
                return [
                    self::_ALB, self::_BEL, self::_BGR, self::_CAN, self::_HRV,
                    self::_CZE, self::_DNK, self::_EST, self::_FRA, self::_DEU,
                    self::_GRC, self::_HUN, self::_ISL, self::_ITA, self::_LVA,
                    self::_LTU, self::_LUX, self::_MNE, self::_NLD, self::_MKD,
                    self::_NOR, self::_POL, self::_PRT, self::_ROU, self::_SVK,
                    self::_SVN, self::_ESP, self::_TUR, self::_GBR, self::_USA,
                    self::_SWE,
                ];
            case 'oecd':
                return [
                    self::_AUS, self::_NZL, self::_AUT, self::_NOR, self::_BEL,
                    self::_POL, self::_CAN, self::_PRT, self::_CHL, self::_SVK,
                    self::_DNK, self::_ESP, self::_EST, self::_SWE, self::_FIN,
                    self::_CHE, self::_FRA, self::_TUR, self::_DEU, self::_GBR,
                    self::_GRC, self::_USA, self::_HUN, self::_ISL, self::_IRL,
                    self::_ISR, self::_ITA, self::_JPN, self::_KOR, self::_LUX,
                    self::_MEX, self::_NLD, self::_CZE, self::_SVN,
                ];
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
            case 'schengen':
                return [
                    self::_AUT, self::_BEL, self::_HRV,
                    self::_CZE, self::_DNK, self::_EST, self::_FIN, self::_FRA,
                    self::_DEU, self::_GRC, self::_HUN, self::_ITA,
                    self::_LVA, self::_LTU, self::_LUX, self::_MLT, self::_NLD,
                    self::_POL, self::_PRT, self::_SVK, self::_SVN,
                    self::_ESP, self::_SWE,
                    self::_ISL, self::_NOR, self::_CHE, self::_LIE,
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
            case 'dach':
                return [
                    self::_DEU, self::_AUT, self::_CHE,
                ];
            case 'g8':
                return [
                    self::_USA, self::_GBR, self::_FRA, self::_DEU, self::_ITA,
                    self::_CAN, self::_RUS, self::_JPN,
                ];
            case 'p5':
                return [
                    self::_USA, self::_GBR, self::_FRA, self::_RUS, self::_CHN,
                ];
            default:
                return [];
        }
    }
}
