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
namespace phpOMS\Message\Http;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\MimeType;
use phpOMS\Views\View;

/**
 * Response class.
 *
 * @category   Framework
 * @package    phpOMS\Response
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Response extends ResponseAbstract implements RenderableInterface
{
    /**
     * Constructor.
     *
     * @param Localization $l11n Localization
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(Localization $l11n)
    {
        $this->header = new Header();
        $this->l11n   = $l11n;
    }

    /**
     * Set response.
     *
     * @param array $response Response to set
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setResponse(array $response) /* : void */
    {
        $this->response = $response;
    }

    /**
     * Remove response by ID.
     *
     * @param int $id Response ID
     *
     * @return bool
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove(int $id) : bool
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
    public function getProtocolVersion() : string
    {
        return '1.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        return $this->render();
    }

    /**
     * Generate response based on header.
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function render() : string
    {
        switch ($this->header->get('Content-Type')) {
            case MimeType::M_JSON:
                return $this->jsonSerialize();
            default:
                return $this->getRaw();
        }
    }

    /**
     * Generate raw response.
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getRaw() : string
    {
        $render = '';

        foreach ($this->response as $key => $response) {
            if ($response instanceOf \Serializable) {
                $render .= $response->serialize();
            } elseif (is_string($response) || is_numeric($response)) {
                $render .= $response;
            } elseif (is_array($response)) {
                $render .= json_encode($response);
                // TODO: remove this. This should never happen since then someone forgot to set the correct header. it should be json header!
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
                } elseif (is_array($response)) {
                    $result += $response;
                } elseif (is_scalar($response)) {
                    $result[] = $response;
                } elseif ($response instanceof \Serializable) {
                    $result[] = $response->serialize();
                } else {
                    throw new \Exception('Wrong response type');
                }
            }
        } catch (\Exception $e) {
            // todo: handle exception
            // need to to try catch for logging. otherwise the json_encode in the logger will have a problem with this
            $result = [];
        } finally {
            return $result;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase() : string
    {
        return $this->header->getHeader('Status');
    }
}
