<?php

$host = "127.0.0.1";
$dbname = "erronka_2taldea";
$user = "root";
$pass = "1MG32025";

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try{
    
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
    ]);
}catch(PDOException $e){
    die("DB konexio errorea: " . $e->getMessage());
}
?>