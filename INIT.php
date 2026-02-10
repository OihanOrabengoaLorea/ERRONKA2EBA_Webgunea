<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

$host = "192.168.115.171";
$user = "erabiltzaile";
$pass = "2TALDEA";
$dbname = "erronka_2taldea";

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die("DB konexio errorea: " . $e->getMessage());
}
?>