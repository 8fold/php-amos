<?php
$config = __DIR__ . '/../../config.php';

if (file_exists($config)) {
    require_once $config;
}

if (defined('AMOS_SITE_ENV') and AMOS_SITE_ENV === 'local') {
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);
    error_reporting(E_ALL);

} else {
    ini_set('display_errors', false);
    ini_set('display_startup_errors', false);

}

$contentPublicRoot = __DIR__ . '/../docs/public';
if (is_dir($contentPublicRoot) === false) {
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    $message  = '500 Internal Server Error';
    header($protocol . ' ' . $message, true, 500);
    print file_get_contents(__DIR__ . '/public/error-500.html');
    exit();
}
?>
