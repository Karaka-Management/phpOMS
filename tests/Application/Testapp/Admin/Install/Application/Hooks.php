<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   \phpOMS\tess\Application\Apps\Testapp
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

return [
    '/POST:App:Testapp.*?\-create/' => [
        'callback' => ['\phpOMS\tess\Application\Apps\Testapp\Controller\Controller:testHook'],
    ],
];
