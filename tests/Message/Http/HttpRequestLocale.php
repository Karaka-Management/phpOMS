<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../Autoloader.php';

use phpOMS\Message\Http\HttpRequest;

$request = HttpRequest::createFromSuperglobals();

echo $request->getLocale();
