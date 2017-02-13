<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);
namespace phpOMS\Utils\Crawler;
/**
 * Array utils.
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class WebParser
{
    private $uri = '';
    private $doc = null;
    private $finder = null;
    
    public function __construct(string $uri) 
    {
        $this->uri = $uri;
    }
    
    private function download($uri)
    {
        $handle = curl_init($uri);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        
        $this->doc = new \DOMDocument();
        $this->doc->loadHTML($this->content);
        $this->finder = new \DomXPath($this->doc);
    }
    
    public function get(string $xpath)
    {
        $nodes = $finder->query($xpath);
    }
    
    private function parseTable($node)
    {
    }
    
    private function parseList($node)
    {
    }
}
