<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Views
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Views;

/**
 * Basic table view which can be used as basis for specific implementations.
 *
 * @package    phpOMS\Views
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class TableView extends View
{
    public function renderHeaderColumn(string $inner, bool $sortable = true, bool $filterable = true)
    {

    }
}
