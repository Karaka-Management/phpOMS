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

namespace phpOMS\Message\Http;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;

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
    /**
     * Request ip
     *
     * @var string
     * @since 1.0.0
     */
    public string $address = '';

    /**
     * Request language
     *
     * @var string
     * @since 1.0.0
     */
    public string $language = ISO639x1Enum::_EN;

    /**
     * Request country
     *
     * @var string
     * @since 1.0.0
     */
    public string $country = ISO3166TwoEnum::_XXX;

    /**
     * Request time
     *
     * @var \DateTime
     * @since 1.0.0
     */
    public \DateTime $datetime;

    /**
     * Request host/domain
     *
     * @var string
     * @since 1.0.0
     */
    public string $host = '';

    /**
     * Request path
     *
     * @var string
     * @since 1.0.0
     */
    public string $path = '';

    /**
     * Request full uri
     *
     * @var string
     * @since 1.0.0
     */
    public string $uri = '';

    /**
     * Request referer link
     *
     * @var string
     * @since 1.0.0
     */
    public string $referer = '';

    /**
     * Request browser/agent
     *
     * @var string
     * @since 1.0.0
     */
    public string $userAgent = '';

    /**
     * Additional custom meta data to be stored
     *
     * @var string
     * @since 1.0.0
     */
    public array $meta = [];

    /**
     * Constructor.
     *
     * @param HttpRequest $request Http request object
     *
     * @since 1.0.0
     */
    public function __construct(HttpRequest $request)
    {
        $this->language  = $request->header->l11n->language;
        $this->country   = $request->header->l11n->country;
        $this->uri       = \substr($request->uri->uri, 0, 255);
        $this->host      = $request->uri->host;
        $this->path      = \substr($request->uri->path, 0, 255);
        $this->address   = $request->header->getRequestIp();
        $this->datetime  = new \DateTime('@' . $request->header->getRequestTime());
        $this->referer   = \substr($request->header->getReferer(), 0, 255);
        $this->userAgent = $request->header->getBrowserName();
    }

    /**
     * Turn object to array
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function toArray() : array
    {
        return [
            'address'  => $this->address,
            'date'     => $this->datetime->format('Y-m-d'),
            'hour'     => $this->datetime->format('H'),
            'host'     => $this->host,
            'path'     => $this->path,
            'uri'      => $this->uri,
            'agent'    => $this->userAgent,
            'language' => $this->language,
            'country'  => $this->country,
            'referer'  => $this->referer,
            'datetime' => $this->datetime->getTimestamp(),
            'meta'     => $this->meta,
        ];
    }
}
