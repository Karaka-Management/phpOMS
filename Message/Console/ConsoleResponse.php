<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Console
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Console;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Log\FileLogger;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;
use phpOMS\Utils\StringUtils;
use phpOMS\Views\View;

/**
 * Response class.
 *
 * @package phpOMS\Message\Console
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ConsoleResponse extends ResponseAbstract implements RenderableInterface
{
    /**
     * Response status.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $status = RequestStatusCode::R_200;

    /**
     * Constructor.
     *
     * @param Localization $l11n Localization
     *
     * @since 1.0.0
     */
    public function __construct(Localization $l11n = null)
    {
        $this->header       = new ConsoleHeader();
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
        $this->response = $response;
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
        if (isset($this->response[$id])) {
            unset($this->response[$id]);

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
     * Generate response based on header.
     *
     * @param mixed ...$data Data passt to render function. (0 => bool: $optimize)
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function render(...$data) : string
    {
        $types = $this->header->get('Content-Type');

        foreach ($types as $type) {
            if (\stripos($type, MimeType::M_JSON) !== false) {
                return (string) \json_encode($this->jsonSerialize());
            }
        }

        return $this->getRaw($data[0] ?? false);
    }

    /**
     * Generate raw response.
     *
     * @param bool $optimize Optimize response / minify
     *
     * @return string
     *
     * @throws \Exception this exception is thrown if the response cannot be rendered
     *
     * @since 1.0.0
     */
    private function getRaw(bool $optimize = false) : string
    {
        $render = '';

        foreach ($this->response as $key => $response) {
            $render .= StringUtils::stringify($response);
        }

        return $render;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        $result = [];

        foreach ($this->response as $key => $response) {
            if ($response instanceof View) {
                $result[] = $response->toArray();
            } elseif (\is_array($response) || \is_scalar($response)) {
                $result[] = $response;
            } elseif ($response instanceof \JsonSerializable) {
                $result[] = $response->jsonSerialize();
            } elseif ($response === null) {
                continue;
            } else {
                FileLogger::getInstance('', false)
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
}
