<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\tests\Application\Apps\{APPNAME}\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Application\Apps\Testapp\Admin;

use phpOMS\Application\UninstallerAbstract;

/**
 * Uninstaller class.
 *
 * @package phpOMS\tests\Application\Apps\{APPNAME}\Admin
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Uninstaller extends UninstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;
}
