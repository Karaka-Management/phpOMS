<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message\Socket
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Socket;

use phpOMS\Message\ResponseAbstract;
use phpOMS\Contract\RenderableInterface;

final class Response extends ResponseAbstract implements RenderableInterface
{
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
    * @param mixed $id Response ID
    *
    * @return bool
    *
    * @since 1.0.0
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
   public function getJsonData() : array
   {
       return \json_decode($this->getRaw(), true);
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
     * @param bool $optimize Optimize response / minify
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function render(bool $optimize = false) : string
    {
        $types = $this->header->get('Content-Type');

        foreach ($types as $type) {
            if (\stripos($type, MimeType::M_JSON) !== false) {
                return (string) \json_encode($this->jsonSerialize());
            }
        }

        return $this->getRaw($optimize);
    }

    /**
     * Generate raw response.
     *
     * @param bool $optimize Optimize response / minify
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    private function getRaw(bool $optimize = false) : string
    {
        $render = '';

        foreach ($this->response as $key => $response) {
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
