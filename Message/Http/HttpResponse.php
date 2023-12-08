<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Http
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Http;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Log\FileLogger;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Utils\StringUtils;
use phpOMS\Views\View;

/**
 * Response class.
 *
 * @property \phpOMS\Message\Http\HttpHeader $header Http header
 *
 * @package phpOMS\Message\Http
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class HttpResponse extends ResponseAbstract implements RenderableInterface
{
    /**
     * Constructor.
     *
     * @param Localization $l11n Localization
     *
     * @since 1.0.0
     */
    public function __construct(Localization $l11n = null)
    {
        $this->header       = new HttpHeader();
        $this->header->l11n = $l11n ?? new Localization();
    }

    /**
     * Set response.
     *
     * @param array $response Response to set
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setResponse(array $response) : void
    {
        $this->data = $response;
    }

    /**
     * Remove response by ID.
     *
     * @param string $id Response ID
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove(string $id) : bool
    {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(bool $optimize = false) : string
    {
        return $this->render($optimize);
    }

    /**
     * {@inheritdoc}
     */
    public function getJsonData() : array
    {
        $json = \json_decode($this->getRaw(), true);

        return \is_array($json) ? $json : [];
    }

    /**
     * Generate response based on header.
     *
     * @param mixed ...$data Data passt to render function. (0 => bool: $optimize)
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function render(mixed ...$data) : string
    {
        $types = $this->header->get('Content-Type');
        foreach ($types as $type) {
            if (\stripos($type, MimeType::M_JSON) !== false) {
                return (string) \json_encode($this->jsonSerialize());
            } elseif (\stripos($type, MimeType::M_CSV) !== false) {
                return ArrayUtils::arrayToCsv($this->toArray());
            } elseif (\stripos($type, MimeType::M_XML) !== false) {
                return ArrayUtils::arrayToXml($this->toArray());
            } elseif (\stripos($type, MimeType::M_HTML) !== false) {
                /** @var array{0:bool} $data */
                return $this->getRaw($data[0] ?? false);
            }
        }

        return $this->getRaw(false);
    }

    /**
     * Generate raw response.
     *
     * @param bool $optimize Optimize response / minify
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function getRaw(bool $optimize = false) : string
    {
        $render = '';
        foreach ($this->data as $response) {
            // @note Api functions return void -> null, this is where the null value is "ignored"/rendered as ''
            $render .= StringUtils::stringify($response);
        }

        if ($optimize) {
            return $this->removeWhitespaceAndLineBreak($render);
        }

        return $render;
    }

    /**
     * Remove whitespace and line break from render
     *
     * @param string $render Rendered string
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function removeWhitespaceAndLineBreak(string $render) : string
    {
        $types = $this->header->get('Content-Type');
        if (\stripos($types[0], MimeType::M_HTML) !== false) {
            $clean = \preg_replace('/(?s)<pre[^<]*>.*?<\/pre>(*SKIP)(*F)|(\s{2,}|\n|\t)/', ' ', $render);

            return \trim($clean ?? '');
        }

        return $render;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        $result = [];

        foreach ($this->data as $response) {
            if ($response instanceof View) {
                $result[] = $response->toArray();
            } elseif (\is_array($response) || \is_scalar($response)) {
                $result[] = $response;
            } elseif ($response instanceof \JsonSerializable) {
                $result[] = $response->jsonSerialize();
            } elseif ($response === null) {
                continue;
            } else {
                FileLogger::getInstance()
                    ->error(
                        FileLogger::MSG_FULL, [
                            'message' => 'Unknown type.',
                            'line'    => __LINE__,
                            'file'    => self::class,
                        ]
                    );
            }
        }

        return $result;
    }

    /**
     * Ends the output buffering
     *
     * This is helpful in case the output buffering should be stopped for streamed/chunked responses (e.g. large data)
     *
     * @param int $levels Levels to close
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function endAllOutputBuffering(int $levels = 0) : void
    {
        if (!$this->header->isLocked()) {
            $this->header->push(); // @codeCoverageIgnore
        }

        $levels = $levels === 0 ? \ob_get_level() : $levels;
        for ($i = 0; $i < $levels; ++$i) {
            \ob_end_clean();
        }
    }
}
