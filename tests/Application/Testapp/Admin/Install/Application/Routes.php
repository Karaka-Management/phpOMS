<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use phpOMS\Router\RouteVerb;

return [
    '^.*/testapp.*$' => [
        [
            'dest'       => '\phpOMS\tess\Application\Apps\Testapp\Controller\Controller:testEndpoint',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'type'   => 1,
                'state'  => 2,
            ],
        ],
    ],
];
