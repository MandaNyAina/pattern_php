<?php
    $config = parse_ini_file('config.ini');
    require 'constante.php';
    require 'directory.php';
    $dir = new FileDir();
    require 'routes.php';
    require 'database.php';
    require 'connector.php';
    require 'views.php';
    require 'core.php';
    require 'message.php';
    $message = new Message();
    require 'forms.php';
    $database = $base;
?>