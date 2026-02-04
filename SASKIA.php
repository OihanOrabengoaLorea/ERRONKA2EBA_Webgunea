<?php
include_once 'INIT.php';
include_once 'HEADER.php';

if (!isset($_SESSION['erabiltzailea'])) {
    echo "<script>window.location.href='HASI SAIOA.php';</script>";
    exit();
}

$cart_items = isset($_SESSION['saskia']) ? $_SESSION['saskia'] : [];
$products = [];
$total = 0;

if (!empty($cart_items)) {
    $ids = array_map('intval', array_keys($cart_items));
    if (!empty($ids)) {
        $in  = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "SELECT * FROM produktuak WHERE id IN ($in)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        $products = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Zure Saskia</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
</head>
<body>
    <div class="main" style="max-width: 900px; margin: 98px auto;">
        <h1>Zure Saskia</h1>
        
        <?php if (empty($products)): ?>
            <div class="empty-cart">
                <h3>Zure saskia hutsik dago.</h3>
                <a href="KATALOGOA.php" class="saskia-btn">Ikusi Katalogoa</a>
            </div>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produktua</th>
                        <th>Prezioa</th>
                        <th>Kopurua</th>
                        <th>Guztira</th>
                        <th>Ekintzak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): 
                        $qty = $cart_items[$product['id']];
                        $line_total = $product['prezioa'] * $qty;
                        $total += $line_total;
                    ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <?php if($product['irudia']): ?>
                                    <img src="ARGAZKIAK/<?= htmlspecialchars($product['irudia']) ?>" style="width: 50px; height: 50px; object-fit: cover; margin:0; border: none;" alt="">
                                <?php endif; ?>
                                <?= htmlspecialchars($product['izena']) ?>
                            </div>
                        </td>
                        <td><?= number_format($product['prezioa'], 2) ?>€</td>
                        <td><?= $qty ?></td>
                        <td><?= number_format($line_total, 2) ?>€</td>
                        <td>
                            <a href="SASKIA_KUDEATU.php?action=remove&id=<?= $product['id'] ?>" class="btn-remove">Ezabatu</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="cart-total">
                <p>Guztira: <?= number_format($total, 2) ?>€</p>
                <a href="KATALOGOA.php" class="saskia-btn" style="float: left;">Jarraitu erosten</a>
                <a href="SASKIA_KUDEATU.php?action=checkout" class="checkout-btn" onclick="return confirm('Ziur zaude erosketa burutu nahi duzula? (Totala: <?= number_format($total, 2) ?>€)');">Eskatu Orain</a>
            </div>
            <div style="clear: both;"></div>
        <?php endif; ?>
    </div>

    <?php include_once 'FOOTER.php'; ?>
</body>
</html>
