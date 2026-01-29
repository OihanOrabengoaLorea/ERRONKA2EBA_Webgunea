<?php
include_once 'INIT.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['erabiltzailea'])) {
    header("Location: HASI SAIOA.php");
    exit();
}

if (!isset($_SESSION['saskia'])) {
    $_SESSION['saskia'] = [];
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'KATALOGOA.php';

switch ($action) {
    case 'add':
        if ($id > 0) {
            if (isset($_SESSION['saskia'][$id])) {
                $_SESSION['saskia'][$id]++;
            } else {
                $_SESSION['saskia'][$id] = 1;
            }
        }
        break;

    case 'remove':
        if ($id > 0 && isset($_SESSION['saskia'][$id])) {
            unset($_SESSION['saskia'][$id]);
        }
        break;
        
    case 'update':
         $qty = isset($_GET['qty']) ? intval($_GET['qty']) : 1;
         if ($id > 0) {
             if ($qty <= 0) {
                 unset($_SESSION['saskia'][$id]);
             } else {
                 $_SESSION['saskia'][$id] = $qty;
             }
         }
         break;

    case 'empty':
        $_SESSION['saskia'] = [];
        break;
        
    case 'buy_now':
        if ($id > 0) {
             if (isset($_SESSION['saskia'][$id])) {
                $_SESSION['saskia'][$id]++;
            } else {
                $_SESSION['saskia'][$id] = 1;
            }
        }
        $redirect_url = 'SASKIA.php'; 
        break;
}

header("Location: " . $redirect_url);
exit();
?>
