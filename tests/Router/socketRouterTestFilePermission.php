<?php declare(strict_types=1);

use Modules\Admin\Controller\BackendController;
use Modules\Admin\Models\PermissionState;
use phpOMS\Account\PermissionType;

return [
    '^.*backend_admin -settings=general.*$' => [
        0 => [
            'dest'       => '\Modules\Admin\Controller:viewSettingsGeneral',
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::SETTINGS,
            ],
        ],
    ],
];
