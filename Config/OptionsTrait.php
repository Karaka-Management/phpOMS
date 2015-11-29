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
namespace phpOMS\Config;

/**
 * Options trait.
 *
 * @category   Framework
 * @package    phpOMS\Config
 * @since      1.0.0
 */
trait OptionsTrait
{

    /**
     * Options.
     *
     * @var array
     * @since 1.0.0
     */
    private $options = [];

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($key)
    {
        return $this->options[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($key, $value, \bool $overwrite = true) : \bool
    {
        if ($overwrite || !array_key_exists($key, $this->options)) {
            $this->options[$key] = [$value, $overwrite];

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $pair, \bool $overwrite = true) : \bool
    {
        if ($overwrite) {
            $this->options += $pair;

            return true;
        }

        return false;
    }

}
