<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\CreditRating
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\CreditRating;

/**
 * EU VAT validation interface
 *
 * @package phpOMS\Api\CreditRating
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface CreditRatingInterface
{
    /**
     * Authenticate with the API
     *
     * @param string $username Username
     * @param string $password Password
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function auth(string $username, string $password) : string;

    /**
     * Find companies matching search parameters
     *
     * @param string $token               API token
     * @param string $name                Company name
     * @param string $address             Company address
     * @param string $street              Company street
     * @param string $city                Company city
     * @param string $postal              Company postal
     * @param string $province            Company province
     * @param string $phoneNo             Company phone number
     * @param string $houseNo             Company house number
     * @param string $vatNo               Company VAT number
     * @param string $localRegistrationNo Company registration number
     * @param array  $countries           countries to search in
     * @param int    $threshold           Match threshold
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function findCompanies(
        string $token,
        string $name = '',
        string $address = '',
        string $street = '',
        string $city = '',
        string $postal = '',
        string $province = '',
        string $phoneNo = '',
        string $houseNo = '',
        string $vatNo = '',
        string $localRegistrationNo = '',
        array $countries = [],
        int $threshold = 900,
    ) : array;

    /**
     * Get credit report of company
     *
     * @param string $token    API token
     * @param string $id       Company id
     * @param string $template Report type
     * @param string $language Report language
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function creditReport(string $token, string $id, string $template = 'full', string $language = 'en') : array;

    /**
     * Find companies matching search parameters if they couldn't be found in the database
     *
     * @param string $token                    API token
     * @param string $ownName                  Name of the person requesting the company
     * @param string $ownCompanyName           Own company name
     * @param string $ownCompanyRegistrationNo Owm company registration number
     * @param string $ownEmail                 Email of the person requestion the company
     * @param string $name                     Company name
     * @param string $address                  Company address
     * @param string $street                   Company street
     * @param string $city                     Company city
     * @param string $postal                   Company postal
     * @param string $province                 Company province
     * @param string $phoneNo                  Company phone number
     * @param string $houseNo                  Company house number
     * @param string $vatNo                    Company VAT number
     * @param string $localRegistrationNo      Company registration number
     * @param string $country                  Company country
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function investigate(
        string $token,
        string $ownName = '',
        string $ownCompanyName = '',
        string $ownCompanyRegistrationNo = '',
        string $ownEmail = '',
        string $name = '',
        string $address = '',
        string $street = '',
        string $city = '',
        string $postal = '',
        string $province = '',
        string $phoneNo = '',
        string $houseNo = '',
        string $vatNo = '',
        string $localRegistrationNo = '',
        string $country = ''
    ) : string;

    /**
     * Get investigations
     *
     * @param string    $token API token
     * @param \DateTime $start Investitions requested from this starting date
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function showInvestigations(string $token, \DateTime $start) : array;

    /**
     * Get the status/result of a investigation
     *
     * @param string $token API token
     * @param string $id    Investigation ID
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getInvestigation(string $token, string $id) : array;
}
