<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Orange-Management
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

require_once __DIR__ . '/Preloader.php';

$preloader = new \phpOMS\Preloader();

$preloader->includePath(__DIR__ . '/Account')
    ->includePath(__DIR__ . '/Asset')
    ->includePath(__DIR__ . '/Auth')
    ->includePath(__DIR__ . '/Cache')
    ->includePath(__DIR__ . '/Config')
    ->includePath(__DIR__ . '/Contract')
    ->includePath(__DIR__ . '/DataStorage')
    ->includePath(__DIR__ . '/Dispatcher')
    ->includePath(__DIR__ . '/Event')
    ->includePath(__DIR__ . '/Localization')
    ->includePath(__DIR__ . '/Log')
    ->includePath(__DIR__ . '/Message')
    ->includePath(__DIR__ . '/Model')
    ->includePath(__DIR__ . '/Module')
    ->includePath(__DIR__ . '/Router')
    ->includePath(__DIR__ . '/Stdlib')
    ->includePath(__DIR__ . '/Uri')
    ->includePath(__DIR__ . '/Views')
    ->load();
