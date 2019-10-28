<?php declare(strict_types=1);
return [
    '^.*/backend/admin/settings/general.*$' => [
        0 => [
            'dest' => '\Modules\Admin\Controller:viewSettingsGeneral',
            'verb' => 1,
        ],
    ],
];
