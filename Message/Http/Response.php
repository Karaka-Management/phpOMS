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

use phpOMS\Contract\ArrayableInterface;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Html\Head;
use phpOMS\Utils\ArrayUtils;

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
     * Header.
     *
     * @var string[][]
     * @since 1.0.0
     */
    private $header = [];

    /**
     * html head.
     *
     * @var Head
     * @since 1.0.0
     */
    private $head = null;

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');
        $this->head = new Head();
    }

    /**
     * Push header by ID.
     *
     * @param mixed $name Header ID
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function pushHeaderId($name)
    {
        foreach ($this->header[$name] as $key => $value) {
            header($name, $value);
        }
    }

    /**
     * Remove header by ID.
     *
     * @param int $key Header key
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeHeader(int $key) : bool
    {
        if (isset($this->header[$key])) {
            unset($this->header[$key]);

            return true;
        }

        return false;
    }

    /**
     * Generate header automatically based on code.
     *
     * @param int $code HTTP status code
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function generateHeader(int $code)
    {
        if ($code === 403) {
            $this->setHeader('HTTP', 'HTTP/1.0 403 Forbidden');
            $this->setHeader('Status', 'Status: HTTP/1.0 403 Forbidden');
        } elseif ($code === 406) {
            $this->setHeader('HTTP', 'HTTP/1.0 406 Not acceptable');
            $this->setHeader('Status', 'Status:406 Not acceptable');
        } elseif ($code === 503) {
            $this->setHeader('HTTP', 'HTTP/1.0 503 Service Temporarily Unavailable');
            $this->setHeader('Status', 'Status: 503 Service Temporarily Unavailable');
            $this->setHeader('Retry-After', 'Retry-After: 300');
        }
    }

    /**
     * Set response.
     *
     * @param string $response Response to set
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * Push a specific response ID.
     *
     * @param int $id Response ID
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function pushResponseId(int $id)
    {
        ob_start();
        echo $this->response[$id];
        ob_end_flush();
    }

    /**
     * Generate response.
     *
     * @return \Iterator
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getYield() : \Iterator
    {
        yield $this->head->render();

        foreach ($this->response as $key => $response) {
            yield $response;
        }
    }

    /**
     * Push all headers.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function pushHeader()
    {
        foreach ($this->header as $name => $arr) {
            foreach ($arr as $ele => $value) {
                header($name . ': ' . $value);
            }
        }
    }

    /**
     * Remove response by ID.
     *
     * @param int $id Response ID
     *
     * @return bool
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
    public function getHead()
    {
        return $this->head;
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
    public function getHeaders() : array
    {
        return $this->header;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(string $name) : bool
    {
        return array_key_exists($name, $this->header);
    }

    /**
     * {@inheritdoc}
     */
    public function getBody() : string
    {
        return $this->render();
    }

    /**
     * Generate response.
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
        $render = $this->head->render();

        foreach ($this->response as $key => $response) {
            if (is_object($response)) {
                $render .= $response->render();
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
    public function toCsv() : string
    {
        return ArrayUtils::arrayToCSV($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        $arr = [];

        foreach ($this->response as $key => $response) {
            if ($response instanceof ArrayableInterface) {
                $arr = ArrayUtils::setArray($key, $arr, $response->toArray(), ':');
            } else {
                $arr = ArrayUtils::setArray($key, $arr, $response, ':');
            }
        }

        return $arr;
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase() : string
    {
        return $this->getHeader('Status');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name)
    {
        if (isset($this->header[$name])) {
            return $this->header[$name];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader($key, string $header, bool $overwrite = false) : bool
    {
        if (!$overwrite && isset($this->header[$key])) {
            return false;
        } elseif ($overwrite) {
            unset($this->header[$key]);
        }

        if (!isset($this->header[$key])) {
            $this->header[$key] = [];
        }

        $this->header[$key][] = $header;

        return true;
    }
}
