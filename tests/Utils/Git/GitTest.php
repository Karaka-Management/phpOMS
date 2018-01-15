<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Git;

class GitTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        self::assertEquals('/usr/bin/git', Git::getBin());
    }
}
