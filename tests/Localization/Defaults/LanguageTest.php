<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Language;

class LanguageTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaults()
    {
        $obj = new Language();
        self::assertEquals('', $obj->getName());
        self::assertEquals('', $obj->getNative());
        self::assertEquals('', $obj->getCode2());
        self::assertEquals('', $obj->getCode3());
        self::assertEquals('', $obj->getCode3Native());
    }
}
