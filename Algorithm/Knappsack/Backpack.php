<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types = 1);

namespace phpOMS\Algorithm\Knappsack;
use phpOMS\Algorithm\AlgorithmType;

/**
 * Knappsack algorithm implementations
 *
 * @category   Framework
 * @package    phpOMS\Auth
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Backpack
{
    private $costMaximum = 0;

    private $value = 0;

    private $cost = 0;

    private $items = [];

    private $population = [];

    public function __construct(float $costMaximum) 
    {
        $this->costMaximum = $costMaximum;
    }

    public function addPopulationItem(ItemInterface $item) : bool
    {
        if(isset($this->population[$item->getId()])) {
            return false;
        }

        $this->population[$item->getId()] = $item;

        return true;
    }

    public function setPopulationItem(ItemInterface $item)  /* : void */
    {
        $this->population[$item->getId()] = $item;
    }

    public function setCostCalculation(\Closure $callback) /* : void */
    {

    }

    public function setValueCalculation(\Closure $callback) /* : void */
    {

    }

    public function setTestPopulationBuilder(\Closure $callback) /* : void */
    {

    }

    public function pack(int $type) 
    {
        switch($type) {
            case AlgorithmType::BRUTEFORCE:
                return $this->bruteforce();
            default:
                throw new \Exception('Invalid algorithm type');
        }
    }

    public function bruteforce()
    {
    }
}