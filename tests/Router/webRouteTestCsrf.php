<?php
declare(strict_types=1);
return [
    '^.*/backend/admin/settings/csrf.*$' => [
        0 => [
            'dest' => '\Modules\Admin\Controller:viewCsrf',
            'verb' => 1,
            'csrf' => true,
        ],
    ],
];
