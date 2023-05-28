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

use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\RequestMethod;
use phpOMS\Message\Http\Rest;
use phpOMS\Uri\HttpUri;

/**
 * Creditsafe Api.
 *
 * @package phpOMS\Api\CreditRating
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CreditSafe implements CreditRatingInterface
{
    public const API_URL = 'https://connect.creditsafe.com/v1';
    //public const API_URL = 'https://connect.sandbox.creditsafe.com/v1';

    public function auth(string $username, string $password) : string
    {
        $url = '/authenticate';

        $request = new HttpRequest(new HttpUri(self::API_URL . $url));
        $request->setMethod(RequestMethod::POST);

        $response = Rest::request($request);

        return $response->header->status === 200
            ? ($response->get('token') ?? '')
            : '';
    }

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
        int $threashold = 0,
    ) : array
    {
        $url = '/companies';
        if ($threashold > 0) {
            $url .= '/matches';
        }

        $request = new HttpRequest(new HttpUri(self::API_URL . $url));
        $request->setMethod(RequestMethod::GET);

        $request->header->set('Authorization', $token);

        $request->setData('page', 1);
        $request->setData('pageSize', 100);
        $request->setData('language', 'en');

        if ($threashold > 0) {
            $request->setData('matchThreshold', $threashold);
            $request->setData('country', \implode(',', $countries));
        } else {
            $request->setData('countries', empty($countries) ? 'PLC' : \implode(',', $countries));
        }

        if ($localRegistrationNo !== '') {
            $request->setData('regNo', $localRegistrationNo);
        }

        if ($vatNo !== '') {
            $request->setData('vatNo', $vatNo);
        }

        if ($name !== '') {
            $request->setData('name', $name);
        }

        if ($address !== '') {
            $request->setData('address', $address);
        }

        if ($street !== '') {
            $request->setData('street', $street);
        }

        if ($province !== '') {
            $request->setData('province', $province);
        }

        if ($postal !== '') {
            $request->setData('postal', $postal);
        }

        if ($city !== '') {
            $request->setData('city', $city);
        }

        if ($houseNo !== '') {
            $request->setData('houseNo', $houseNo);
        }

        if ($phoneNo !== '') {
            $request->setData('phoneNo', $phoneNo);
        }

        $response = Rest::request($request);

        return $response->get('companies') ?? ($response->get('matchedCompanies') ?? []);
    }

    public function creditReport(string $token, string $id, string $template = 'full', string $language = 'en') : array
    {
        $url = '/companies/' . $id;

        $request = new HttpRequest(new HttpUri(self::API_URL . $url));
        $request->setMethod(RequestMethod::GET);

        $request->header->set('Authorization', $token);

        $request->setData('connectId', $id);
        $request->setData('language', $language);
        $request->setData('template', $template);

        $response = Rest::request($request);

        return $response->get('report') ?? [];
    }

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
    ) : string
    {
        $url = '/freshinvestigations';

        $request = new HttpRequest(new HttpUri(self::API_URL . $url));
        $request->setMethod(RequestMethod::POST);

        $request->header->set('Authorization', $token);

        $request->setData('contactInfo', [
            'name'    => $ownName,
            'company' => [
                'name'   => $ownCompanyName,
                'number' => $ownCompanyRegistrationNo,
            ],
            'emailAddress'   => $ownEmail,
            'searchCriteria' => [
                'name'    => $name,
                'address' => [
                    'simple'   => empty($address) ? null : $address,
                    'postcode' => empty($postal) ? null : $postal,
                    'city'     => empty($city) ? null : $city,
                ],
                'regNo'       => empty(${$localRegistrationNo}) ? null : ${$localRegistrationNo},
                'vatNo'       => empty($vatNo) ? null : $vatNo,
                'countryCode' => $country,
            ],
        ]);

        $response = Rest::request($request);

        return $response->get('orderID') ?? '';
    }

    public function showInvestigations(string $token, \DateTime $start) : array
    {
        $url = '/freshinvestigations';

        $request = new HttpRequest(new HttpUri(self::API_URL . $url));
        $request->setMethod(RequestMethod::GET);

        $request->header->set('Authorization', $token);

        $request->setData('page', 1);
        $request->setData('pageSize', 100);
        $request->setData('createdSince', $start->format('c'));

        $response = Rest::request($request);

        return $response->get('orders') ?? [];
    }

    public function getInvestigation(string $token, string $id) : array
    {
        $url = '/freshinvestigations/' . $id;

        $request = new HttpRequest(new HttpUri(self::API_URL . $url));
        $request->setMethod(RequestMethod::GET);

        $request->header->set('Authorization', $token);

        $request->setData('orderId', $id);

        $response = Rest::request($request);

        return $response->toArray();
    }
}
