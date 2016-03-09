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
namespace phpOMS\System;

final class OperatingSystem {
	public static function getSystem() : int 
	{
		switch(PHP_OS) {
			case stristr(PHP_OS, 'DAR'):
				return SystemType::OSX;
			case stristr(PHP_OS, 'WIN'):
				return SystemType::WIN;
			case stristr(PHP_OS, 'LINIX'):
				return SystemType::LINUX;
			default:
				return SystemType::UNKNOWN;
		}
	}
}