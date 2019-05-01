<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Message\Http
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Message\Http;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Log\FileLogger;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;
use phpOMS\Utils\StringUtils;
use phpOMS\Views\View;

/**
 * Response class.
 *
 * @package    phpOMS\Message\Http
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Response extends ResponseAbstract implements RenderableInterface
{
    /**
     * Response status.
     *
     * @var int
     * @since 1.0.0
     */
    protected $status = RequestStatusCode::R_200;

    /**
     * Constructor.
     *
     * @param Localization $l11n Localization
     *
     * @since  1.0.0
     */
    public function __construct(Localization $l11n = null)
    {
        $this->header = new Header();
        $this->header->setL11n($l11n ?? new Localization());
    }

    /**
     * Set response.
     *
     * @param array $response Response to set
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setResponse(array $response) : void
    {
        $this->response = $response;
    }

    /**
     * Remove response by ID.
     *
     * @param mixed $id Response ID
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function remove($id) : bool
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
    public function getBody() : string
    {
        return $this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function getJson() : array
    {
        return \json_decode($this->render(), true);
    }

    /**
     * Generate response based on header.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function render() : string
    {
        $types = $this->header->get('Content-Type');

        foreach ($types as $type) {
            if (\stripos($type, MimeType::M_JSON) !== false) {
                return (string) \json_encode($this->jsonSerialize());
            }
        }

        return $this->getRaw();
    }

    /**
     * Generate raw response.
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    private function getRaw() : string
    {
        $render = '';

        foreach ($this->response as $key => $response) {
            $render .= StringUtils::stringify($response);
        }

        return $this->removeWhitespaceAndLineBreak($render);
    }

    /**
     * Remove whitespace and line break from render
     *
     * @param string $render Rendered string
     *
     * @return string
     *
     * @since  1.0.0
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
     * @todo: this whole workflow with json got improved a little bit but this part looks bad. do i really need so much code or could i simplify it
     */
    public function toArray() : array
    {
        $result = [];

        try {
            foreach ($this->response as $key => $response) {
                if ($response instanceof View) {
                    $result[] = $response->toArray();
                } elseif (\is_array($response)) {
                    $result[] = $response;
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
