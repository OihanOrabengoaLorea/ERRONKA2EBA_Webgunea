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
            $stmt = $pdo->prepare("SELECT stock, izena FROM produktuak WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            $current_qty = isset($_SESSION['saskia'][$id]) ? $_SESSION['saskia'][$id] : 0;

            if ($product && $product['stock'] > $current_qty) {
                $_SESSION['saskia'][$id] = $current_qty + 1;
            } else {
                $product_name = $product ? $product['izena'] : 'produktu hau';
                $msg = "Ezin da gehiago gehitu. Ez dago nahikoa stock $product_name-(e)rako.";
                echo "<script>
                    alert('$msg');
                    " . (isset($_GET['mode']) && $_GET['mode'] == 'silent' ? "" : "window.location.href = '$redirect_url';") . "
                </script>";
                exit();
            }
        }

        if (isset($_GET['mode']) && $_GET['mode'] == 'silent') {
            echo "<script>
                if (window.parent && window.parent.updateCartBadge) {
                    window.parent.updateCartBadge();
                }
            </script>";
            exit();
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
            // HOBEKUNTZA: Produktua jada saskian badago, ez gehitu unitate gehiago
            if (!isset($_SESSION['saskia'][$id])) {
                $stmt = $pdo->prepare("SELECT stock, izena FROM produktuak WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();

                if ($product && $product['stock'] > 0) {
                    $_SESSION['saskia'][$id] = 1;
                } else {
                    echo "<script>alert('Ez dago stock-ik!'); window.location.href='KATALOGOA.php';</script>";
                    exit();
                }
            }
            // Zuzenean saskiara bidali
            header("Location: SASKIA.php");
            exit();
        }
        break;

    case 'checkout':
        $cart_items = $_SESSION['saskia'] ?? [];
        if (empty($cart_items)) {
            header("Location: KATALOGOA.php");
            exit();
        }

        try {
            $pdo->beginTransaction();

            $ids = array_map('intval', array_keys($cart_items));
            $in = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $pdo->prepare("SELECT id, izena, prezioa, stock FROM produktuak WHERE id IN ($in)");
            $stmt->execute($ids);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $totala = 0;
            $productMap = [];
            foreach ($products as $p) { $productMap[$p['id']] = $p; }

            foreach ($cart_items as $pid => $qty) {
                if (isset($productMap[$pid])) {
                    if ($productMap[$pid]['stock'] < $qty) {
                        throw new Exception("Stock nahikorik ez: " . $productMap[$pid]['izena']);
                    }
                    $totala += $productMap[$pid]['prezioa'] * $qty;
                }
            }

            $user = $_SESSION['erabiltzailea'];
            $id_bezeroa = ($user['mota'] === 'bezeroa') ? $user['id'] : null;
            $id_hornitzailea = ($user['mota'] === 'hornitzailea') ? $user['id'] : null;
            $data = date('Y-m-d');

            $stmtErosketa = $pdo->prepare("INSERT INTO erosketa (id_bezeroa, id_hornitzailea, id_produktua, totala, data, zenbatekoa) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtFaktura = $pdo->prepare("INSERT INTO fakturak (id_bezeroa, id_hornitzailea, id_produktua, id_saskia, data, totala, zenbatekoa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtStock = $pdo->prepare("UPDATE produktuak SET stock = stock - ? WHERE id = ?");

            $lastFakturaId = 0;

            foreach ($cart_items as $pid => $qty) {
                if (!isset($productMap[$pid])) continue;
                $line_total = $productMap[$pid]['prezioa'] * $qty;

                // 1. Erosketa txertatu
                $stmtErosketa->execute([$id_bezeroa, $id_hornitzailea, $pid, $line_total, $data, $qty]);
                $erosketaId = $pdo->lastInsertId();

                // 2. Faktura txertatu
                $stmtFaktura->execute([$id_bezeroa, $id_hornitzailea, $pid, $erosketaId, $data, $totala, $qty]);
                $lastFakturaId = $pdo->lastInsertId(); // Gordetako IDa alert-erako

                // 3. Stock eguneratu
                $stmtStock->execute([$qty, $pid]);
            }

            $pdo->commit();
            $_SESSION['saskia'] = [];

            echo "<script>
                alert('Erosketa ondo burutu da! Faktura ID: $lastFakturaId');
                window.location.href = 'KATALOGOA.php';
            </script>";
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $msg = addslashes($e->getMessage());
            echo "<script>alert('Errorea: $msg'); window.location.href = 'SASKIA.php';</script>";
            exit();
        }
        break;
}
header("Location: " . $redirect_url);
exit();
?>