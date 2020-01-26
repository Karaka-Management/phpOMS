<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Config
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Config;

/**
 * Options trait.
 *
 * This trait basically implements the OptionsInterface
 *
 * @package phpOMS\Config
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
trait OptionsTrait
{

    /**
     * Options.
     *
     * @var array
     * @since 1.0.0
     */
    private array $options = [];

    /**
     * {@inheritdoc}
     */
    public function exists($key) : bool
    {
        return isset($this->options[$key]);
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
    public function getOptions(array $key)
    {
        $options = [];

        foreach ($key as $value) {
            if (isset($this->options[$value])) {
                $options[$value] = $this->options[$value];
            }
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($key, $value, bool $overwrite = true) : bool
    {
        if ($overwrite || !isset($this->options[$key])) {
            $this->options[$key] = $value;

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $pair, bool $overwrite = true) : bool
    {
        if ($overwrite) {
            $this->options = $pair + $this->options;
        } else {
            $this->options += $pair;
        }

        return true;
    }
}
