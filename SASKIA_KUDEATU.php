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
        
        // Check for Silent Mode (Iframe)
        if (isset($_GET['mode']) && $_GET['mode'] == 'silent') {
            exit(); // Stop execution, prevents redirect/page reload
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

    case 'checkout':
        $cart_items = $_SESSION['saskia'] ?? [];
        if (empty($cart_items)) {
            $redirect_url = 'KATALOGOA.php';
            break;
        }

        try {
            // 1. Validate Stock & Calculate Total
            $pdo->beginTransaction();
            
            $ids = array_map('intval', array_keys($cart_items));
            if (empty($ids)) throw new Exception("Saskia hutsik dago.");
            
            $in  = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $pdo->prepare("SELECT id, izena, prezioa, stock FROM produktuak WHERE id IN ($in)");
            $stmt->execute($ids);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
            
            $totala = 0;
            $productMap = [];

            foreach ($products as $p) {
                $productMap[$p['id']] = $p;
            }

            foreach ($cart_items as $pid => $qty) {
                if (!isset($productMap[$pid])) continue;
                
                $product = $productMap[$pid];
                
                // STOCK CHECK
                if ($product['stock'] < $qty) {
                    throw new Exception("Ez dago nahikoa stock produktu honetarako: " . $product['izena'] . " (Eskuragarri: " . $product['stock'] . ")");
                }
                
                $totala += $product['prezioa'] * $qty;
            }

            // 2. Create Invoice (Fakturak)
            $user = $_SESSION['erabiltzailea'];
            $id_bezeroa = ($user['mota'] === 'bezeroa') ? $user['id'] : null;
            $id_hornitzailea = ($user['mota'] === 'hornitzailea') ? $user['id'] : null;
            $data = date('Y-m-d');

            $stmtFaktura = $pdo->prepare("INSERT INTO fakturak (id_bezeroa, id_hornitzailea, data, totala) VALUES (?, ?, ?, ?)");
            $stmtFaktura->execute([$id_bezeroa, $id_hornitzailea, $data, $totala]);
            $fakturaId = $pdo->lastInsertId();

            // 3. Create Order Items (Erosketa) & Update Stock
            $stmtErosketa = $pdo->prepare("INSERT INTO erosketa (id_bezeroa, id_hornitzailea, id_produktua, id_faktura, totala, data, zenbatekoa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtStock = $pdo->prepare("UPDATE produktuak SET stock = stock - ? WHERE id = ?");

            foreach ($cart_items as $pid => $qty) {
                if (!isset($productMap[$pid])) continue;
                $product = $productMap[$pid];
                $line_total = $product['prezioa'] * $qty;

                // Insert Erosketa
                $stmtErosketa->execute([
                    $id_bezeroa, 
                    $id_hornitzailea, 
                    $pid, 
                    $fakturaId, 
                    $line_total, 
                    $data,
                    $qty
                ]);

                // Update Stock
                $stmtStock->execute([$qty, $pid]);
            }

            $pdo->commit();
            
            // Clear cart
            $_SESSION['saskia'] = [];
            
            // Redirect with success
            // We can't easily pass a complex message via URL param without encoding, 
            // but let's assume KATALOGOA handles a simple 'success' flag or we add a script.
            echo "<script>
                alert('Erosketa ondo burutu da! Faktura ID: $fakturaId');
                window.location.href = 'KATALOGOA.php';
            </script>";
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            $errorMsg = addslashes($e->getMessage());
            echo "<script>
                alert('Errorea erosketa prozesuan: $errorMsg');
                window.location.href = 'SASKIA.php';
            </script>";
            exit();
        }
        break;
    }
header("Location: " . $redirect_url);
exit();
?>
