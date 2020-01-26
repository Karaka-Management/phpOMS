<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\IO
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO;

use phpOMS\Utils\IO\Csv\CsvInterface;
use phpOMS\Utils\IO\Json\JsonInterface;
use phpOMS\Utils\IO\Pdf\PdfInterface;
use phpOMS\Utils\IO\Spreadsheet\SpreadsheetInterface;

/**
 * Exchange interface.
 *
 * @package phpOMS\Utils\IO
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface ExchangeInterface extends CsvInterface, JsonInterface, SpreadsheetInterface, PdfInterface
{
}
