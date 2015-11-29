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
namespace phpOMS\Localization;

use phpOMS\Datatypes\EnumArray;

/**
 * Currency codes ISO list.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ISO4217EnumArray extends EnumArray
{
    protected static $constants = [
        'ALL' => ['Albania, Leke', '4c, 65, 6b'],
        'AFN' => ['Afghanistan, Afghanis', '60b'],
        'ARS' => ['Argentina, Pesos', '24'],
        'AWG' => ['Aruba, Guilders', '192'],
        'AUD' => ['Australia, Dollars', '24'],
        'AZN' => ['Azerbaijan, New Manats', '43c, 430, 43d'],
        'BSD' => ['Bahamas, Dollars', '24'],
        'BBD' => ['Barbados, Dollars', '24'],
        'BYR' => ['Belarus, Rubles', '70, 2e'],
        'BZD' => ['Belize, Dollars', '42, 5a, 24'],
        'BMD' => ['Bermuda, Dollars', '24'],
        'BOB' => ['Bolivia, Bolivianos', '24, 62'],
        'BAM' => ['Bosnia and Herzegovina, Convertible Marka', '4b, 4d'],
        'BWP' => ['Botswana, Pulas', '50'],
        'BGN' => ['Bulgaria, Leva', '43b, 432'],
        'BRL' => ['Brazil, Reais', '52, 24'],
        'BND' => ['Brunei Darussalam, Dollars', '24'],
        'KHR' => ['Cambodia, Riels', '17db'],
        'CAD' => ['Canada, Dollars', '24'],
        'KYD' => ['Cayman Islands, Dollars', '24'],
        'CLP' => ['Chile, Pesos', '24'],
        'CNY' => ['China, Yuan Renminbi', 'a5'],
        'COP' => ['Colombia, Pesos', '24'],
        'CRC' => ['Costa Rica, Colón', '20a1'],
        'HRK' => ['Croatia, Kuna', '6b, 6e'],
        'CUP' => ['Cuba, Pesos', '20b1'],
        'CZK' => ['Czech Republic, Koruny', '4b, 10d'],
        'DKK' => ['Denmark, Kroner', '6b, 72'],
        'DOP' => ['Dominican Republic, Pesos', '52, 44, 24'],
        'XCD' => ['East Caribbean, Dollars', '24'],
        'EGP' => ['Egypt, Pounds', 'a3'],
        'SVC' => ['El Salvador, Colones', '24'],
        'EEK' => ['Estonia, Krooni', '6b, 72'],
        'EUR' => ['Euro', '20ac'],
        'FKP' => ['Falkland Islands, Pounds', 'a3'],
        'FJD' => ['Fiji, Dollars', '24'],
        'GHC' => ['Ghana, Cedis', 'a2'],
        'GIP' => ['Gibraltar, Pounds', 'a3'],
        'GTQ' => ['Guatemala, Quetzales', '51'],
        'GGP' => ['Guernsey, Pounds', 'a3'],
        'GYD' => ['Guyana, Dollars', '24'],
        'HNL' => ['Honduras, Lempiras', '4c'],
        'HKD' => ['Hong Kong, Dollars', '24'],
        'HUF' => ['Hungary, Forint', '46, 74'],
        'ISK' => ['Iceland, Kronur', '6b, 72'],
        'INR' => ['India, Rupees', '20a8'],
        'IDR' => ['Indonesia, Rupiahs', '52, 70'],
        'IRR' => ['Iran, Rials', 'fdfc'],
        'IMP' => ['Isle of Man, Pounds', 'a3'],
        'ILS' => ['Israel, New Shekels', '20aa'],
        'JMD' => ['Jamaica, Dollars', '4a, 24'],
        'JPY' => ['Japan, Yen', 'a5'],
        'JEP' => ['Jersey, Pounds', 'a3'],
        'KZT' => ['Kazakhstan, Tenge', '43b, 432'],
        'KES' => ['Kenyan Shilling', '4b, 73, 68, 73'],
        'KGS' => ['Kyrgyzstan, Soms', '43b, 432'],
        'LAK' => ['Laos, Kips', '20ad'],
        'LVL' => ['Latvia, Lati', '4c, 73'],
        'LBP' => ['Lebanon, Pounds', 'a3'],
        'LRD' => ['Liberia, Dollars', '24'],
        'LTL' => ['Lithuania, Litai', '4c, 74'],
        'MKD' => ['Macedonia, Denars', '434, 435, 43d'],
        'MYR' => ['Malaysia, Ringgits', '52, 4d'],
        'MUR' => ['Mauritius, Rupees', '20a8'],
        'MXN' => ['Mexico, Pesos', '24'],
        'MNT' => ['Mongolia, Tugriks', '20ae'],
        'MZN' => ['Mozambique, Meticais', '4d, 54'],
        'NAD' => ['Namibia, Dollars', '24'],
        'NPR' => ['Nepal, Rupees', '20a8'],
        'ANG' => ['Netherlands Antilles, Guilders', '192'],
        'NZD' => ['New Zealand, Dollars', '24'],
        'NIO' => ['Nicaragua, Cordobas', '43, 24'],
        'NGN' => ['Nigeria, Nairas', '20a6'],
        'KPW' => ['North Korea, Won', '20a9'],
        'NOK' => ['Norway, Krone', '6b, 72'],
        'OMR' => ['Oman, Rials', 'fdfc'],
        'PKR' => ['Pakistan, Rupees', '20a8'],
        'PAB' => ['Panama, Balboa', '42, 2f, 2e'],
        'PYG' => ['Paraguay, Guarani', '47, 73'],
        'PEN' => ['Peru, Nuevos Soles', '53, 2f, 2e'],
        'PHP' => ['Philippines, Pesos', '50, 68, 70'],
        'PLN' => ['Poland, Zlotych', '7a, 142'],
        'QAR' => ['Qatar, Rials', 'fdfc'],
        'RON' => ['Romania, New Lei', '6c, 65, 69'],
        'RUB' => ['Russia, Rubles', '440, 443, 431'],
        'SHP' => ['Saint Helena, Pounds', 'a3'],
        'SAR' => ['Saudi Arabia, Riyals', 'fdfc'],
        'RSD' => ['Serbia, Dinars', '414, 438, 43d, 2e'],
        'SCR' => ['Seychelles, Rupees', '20a8'],
        'SGD' => ['Singapore, Dollars', '24'],
        'SBD' => ['Solomon Islands, Dollars', '24'],
        'SOS' => ['Somalia, Shillings', '53'],
        'ZAR' => ['South Africa, Rand', '52'],
        'KRW' => ['South Korea, Won', '20a9'],
        'LKR' => ['Sri Lanka, Rupees', '20a8'],
        'SEK' => ['Sweden, Kronor', '6b, 72'],
        'CHF' => ['Switzerland, Francs', '43, 48, 46'],
        'SRD' => ['Suriname, Dollars', '24'],
        'SYP' => ['Syria, Pounds', 'a3'],
        'TWD' => ['Taiwan, New Dollars', '4e, 54, 24'],
        'THB' => ['Thailand, Baht', 'e3f'],
        'TTD' => ['Trinidad and Tobago, Dollars', '54, 54, 24'],
        'TRY' => ['Turkey, Lira', '54, 4c'],
        'TRL' => ['Turkey, Liras', '20a4'],
        'TVD' => ['Tuvalu, Dollars', '24'],
        'UAH' => ['Ukraine, Hryvnia', '20b4'],
        'GBP' => ['United Kingdom, Pounds', 'a3'],
        'USD' => ['United States of America, Dollars', '24'],
        'UYU' => ['Uruguay, Pesos', '24, 55'],
        'UZS' => ['Uzbekistan, Sums', '43b, 432'],
        'VEF' => ['Venezuela, Bolivares Fuertes', '42, 73'],
        'VND' => ['Vietnam, Dong', '20ab'],
        'YER' => ['Yemen, Rials', 'fdfc'],
        'ZWD' => ['Zimbabwe, Zimbabwe Dollars', '5a, 24'],
    ];
}
