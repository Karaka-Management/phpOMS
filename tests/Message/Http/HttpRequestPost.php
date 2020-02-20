<?php

require_once __DIR__ . '/../../../Autoloader.php';

use phpOMS\Message\Http\HttpRequest;

$request = HttpRequest::createFromSuperglobals();

echo \json_encode($request->getData());