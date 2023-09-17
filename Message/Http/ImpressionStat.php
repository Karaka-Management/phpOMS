<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Statistic
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Statistic;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Message\Http\HttpRequest;

/**
 * Request statistic class.
 *
 * @package phpOMS\Message\Statistic
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ImpressionStat
{
    public string $address = '';

    public string $language = ISO639x1Enum::_EN;

    public string $country = ISO3166TwoEnum::_XXX;

    public int $datetime = 0;

    public string $browser = '';

    public string $host = '';

    public string $path = '';

    public string $uri = '';

    public string $referer = '';

    public string $userAgent = '';

    public function __construct(HttpRequest $request)
    {
        $this->language  = $request->header->l11n->language;
        $this->country   = $request->header->l11n->country;
        $this->uri       = \substr($request->uri->uri, 0, 255);
        $this->host      = $request->uri->host;
        $this->path      = \substr($request->uri->path, 0, 255);
        $this->address   = $request->header->getRequestIp();
        $this->datetime  = $request->header->getRequestTime();
        $this->referer   = \substr($request->header->getReferer(), 0, 255);
        $this->userAgent = $request->header->getBrowserName();
    }

    public function toArray() : array
    {
        return [
            'address'  => $this->address,
            'date'     => \date('d-m-y', $this->datetime),
            'hour'     => \date('H', $this->datetime),
            'host'     => $this->host,
            'path'     => $this->path,
            'uri'      => $this->uri,
            'agent'    => $this->userAgent,
            'language' => $this->language,
            'country'  => $this->country,
            'referer'  => $this->referer,
            'datetime' => $this->datetime,
        ];
    }
}
