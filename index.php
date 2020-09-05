<?php
require './config/modules.php';
$uri = explode("/", $_SERVER['REQUEST_URI']);
$uri_valid = str_contains("php", $uri[count($uri) - 1]) || str_contains("html", $uri[count($uri) - 1]);
if (!$uri_valid) routes("/" . $uri[count($uri) - 1]);
?>