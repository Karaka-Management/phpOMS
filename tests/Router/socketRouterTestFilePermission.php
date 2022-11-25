<?php
declare(strict_types=1);

use phpOMS\Account\PermissionType;

return [
    '^.*backend_admin -settings=general.*$' => [
        0 => [
            'dest'       => '\Modules\Admin\Controller:viewSettingsGeneral',
            'permission' => [
                'module' => 'TEST',
                'type'   => PermissionType::READ,
                'category'  => 1,
            ],
        ],
    ],
];
