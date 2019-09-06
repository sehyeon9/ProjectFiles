<?php
    $user = 'root';
    $pass = 'root';
    $database = 'user';
    $host = 'localhost';
    $port = 8889;
    $database = new mysqli($host, $user, $pass, $database, $port);
    
    if ($database ->connection_error) {
        die("Connection failed" . $database ->connection_error);
    }
?>