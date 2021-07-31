<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Http
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Console;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Log\FileLogger;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;
use phpOMS\Views\View;

/**
 * Response class.
 *
 * @package phpOMS\Message\Http
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
            if ($response instanceof \Serializable) {
                $render .= $response->serialize();
            } elseif (\is_string($response) || \is_numeric($response)) {
                $render .= $response;
            } else {
                throw new \Exception('Wrong response type');
            }
        }

        return $render;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        $result = [];

        try {
            foreach ($this->response as $key => $response) {
                if ($response instanceof View) {
                    $result += $response->toArray();
                } elseif (\is_array($response)) {
                    $result += $response;
                } elseif (\is_scalar($response)) {
                    $result[] = $response;
                } elseif ($response instanceof \JsonSerializable) {
                    $result[] = $response->jsonSerialize();
                } elseif ($response === null) {
                    continue;
                } else {
                    throw new \Exception('Wrong response type');
                }
            }
        } catch (\Exception $e) {
            FileLogger::getInstance('', false)
                ->error(
                    FileLogger::MSG_FULL, [
                        'message' => $e->getMessage(),
                        'line'    => __LINE__,
                        'file'    => self::class,
                    ]
                );

            $result = [];
        } finally {
            return $result;
        }
    }
}
