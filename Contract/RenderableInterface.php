<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Contract
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Contract;

/**
 * Make a class renderable.
 *
 * This is primarily used for classes that provide formatted output or output,
 * that get rendered.
 *
 * @package phpOMS\Contract
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface RenderableInterface
{
    /**
     * Get the evaluated contents of the object.
     *
     * @param mixed ...$data Data to pass to renderer
     *
     * @return string Returns rendered output
     *
     * @since 1.0.0
     */
    public function render(...$data) : string;
}
