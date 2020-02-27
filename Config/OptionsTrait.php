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
     * Is this key set.
     *
     * @param mixed $key Key to check for existence
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function exists($key) : bool
    {
        return isset($this->options[$key]);
    }

    /**
     * Get option by key.
     *
     * @param mixed $key Unique option key
     *
     * @return mixed Option value
     *
     * @since 1.0.0
     */
    public function getOption($key)
    {
        return $this->options[$key] ?? null;
    }

    /**
     * Get options by keys.
     *
     * @param mixed $key Unique option key
     *
     * @return array Option values
     *
     * @since 1.0.0
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
     * Updating or adding settings.
     *
     * @param mixed $key       Unique option key
     * @param mixed $value     Option value
     * @param bool  $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since 1.0.0
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
     * Updating or adding settings.
     *
     * @param array $pair      Key value pair
     * @param bool  $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since 1.0.0
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
