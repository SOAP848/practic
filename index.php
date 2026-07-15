<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Router;
use App\Core\Database;
$config = require_once __DIR__ . '/config/database.php';


Database::init($config);


$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = dirname($_SERVER['SCRIPT_NAME']);
if ($basePath !== '/' && $basePath !== '.') {
    $basePath = rtrim($basePath, '/');
    if (strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
}
$isApiRequest = (strpos($requestUri, '/api/') !== false);

$router = new Router();
$router->dispatch();


if (!$isApiRequest) {
    require_once __DIR__ . '/index.html';
}