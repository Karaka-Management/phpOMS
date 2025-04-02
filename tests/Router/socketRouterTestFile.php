<?php
declare(strict_types=1);
return [
    '^.*backend_admin -settings=general( \-.*$|$)' => [
        0 => [
            'dest' => '\Modules\Admin\Controller:viewSettingsGeneral',
        ],
    ],
];
