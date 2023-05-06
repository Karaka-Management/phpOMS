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
    public function auth(string $username, string $password) : string;

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
        int $threashold = 900,
    ) : array;

    public function creditReport(string $token, string $id, string $template = 'full', string $language = 'en') : array;

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

    public function showInvestigations(string $token, \DateTime $start) : array;

    public function getInvestigation(string $token, string $id) : array;
}
