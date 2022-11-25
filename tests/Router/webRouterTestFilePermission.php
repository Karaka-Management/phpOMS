<?php
declare(strict_types=1);

use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/backend/admin/settings/general.*$' => [
        0 => [
            'dest'       => '\Modules\Admin\Controller:viewSettingsGeneral',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => 'TEST',
                'type'   => PermissionType::READ,
                'category'  => 1,
            ],
        ],
    ],
];
