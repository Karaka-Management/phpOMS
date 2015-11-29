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
namespace phpOMS\DataStorage\Database\Schema;

interface GrammarInterface
{

    public function typeTinyInt($places);

    public function typeSmallInt($places);

    public function typeMediumInt($places);

    public function typeInt($places);

    public function typeBigInt($places);

    public function typeFloat($m, $e, $b = 10);

    public function typeDouble($m, $e, $b = 10);

    public function typeDecimal($a, $b);

    public function typeBoolean();

    public function typeJson();

    public function typeDate();

    public function typeTime();

    public function typeDateTime();

    public function typeTimestamp();

    public function typeBinary();

    public function typeChar();

    public function typeString();

    public function typeMediumText();

    public function typeText();

    public function typeLongText();

}
