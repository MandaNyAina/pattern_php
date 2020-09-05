<?php
    $config = parse_ini_file('config.ini');
    $host = $config['db_host'];
    if (strtolower($host) == "localhost") {
        $host = "127.0.0.1";
    }
    $base = new Database($host,$config['db_name'],$config['db_user'],$config['db_password']);
?>