<?php

define("DSN", "mysql:host=db;dbname=myapp;charset=utf8mb4");
define("DB_USER", "myappuser");
define("DB_PASS", "myapppass");
try{
    $pdo = new PDO(
        DSN,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}catch(PDOException $e){
    echo $e->getMessage();
    exit;
}

?>
