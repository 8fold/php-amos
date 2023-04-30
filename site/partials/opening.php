<?php
require_once __DIR__ . '/../environment.php';

require_once __DIR__ . '/../functions.php';

$title = page_title($contentPublicRoot, $_SERVER['REQUEST_URI']);
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php print $title; ?></title>
    </head>
    <body>
