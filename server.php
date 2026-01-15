<?php

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$pathPrefix = trim((string) getenv('APP_PATH_PREFIX'), '/');
$uriForFile = $uri;

if ($pathPrefix !== '') {
    $prefixWithSlash = '/' . $pathPrefix;

    if ($uriForFile === $prefixWithSlash) {
        $uriForFile = '/';
    } elseif (str_starts_with($uriForFile, $prefixWithSlash . '/')) {
        $uriForFile = substr($uriForFile, strlen($prefixWithSlash));
    }
}

if ($uriForFile !== '/' && file_exists($publicPath . $uriForFile)) {
    return false;
}

$formattedDateTime = date('D M j H:i:s Y');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$remoteAddress = $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'];

file_put_contents('php://stdout', "[{$formattedDateTime}] {$remoteAddress} [{$requestMethod}] URI: {$uri}\n");

require_once $publicPath . '/index.php';
