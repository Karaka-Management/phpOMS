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
namespace phpOMS\Algorithm\Knappsack;

/**
 * Knappsack algorithm implementations
 *
 * @category   Framework
 * @package    phpOMS\Auth
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
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

    public function setPopulationItem(ItemInterface $item) 
    {
        $this->population[$item->getId()] = $item;
    }

    public function setCostCalculation(\Closure $callback)
    {

    }

    public function setValueCalculation(\Closure $callback)
    {

    }

    public function setTestPopulationBuilder(\Closure $callback)
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