<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Contract
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Contract;

/**
 * Make a class renderable.
 *
 * This is primarily used for classes that provide formatted output or output,
 * that get rendered.
 *
 * @package    phpOMS\Contract
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface RenderableInterface
{

    /**
     * Get the evaluated contents of the object.
     *
     * @return string Returns rendered output
     *
     * @since  1.0.0
     */
    public function render() : string;
}
