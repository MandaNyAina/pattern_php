<?php
    $host = $config['db_host'];
    if (strtolower($host) == "localhost") {
        $host = "127.0.0.1";
    }
    $base = $config['active_db'] ? new Database($config['db_driver'],$host,$config['db_name'],$config['db_port'],$config['db_user'],$config['db_password']) : null;
?>