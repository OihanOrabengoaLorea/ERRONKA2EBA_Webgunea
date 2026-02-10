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
        $in = str_repeat('?,', count($ids) - 1) . '?';
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
    <title>EJBE - Zure Saskia</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png" />
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
                                    <?php if ($product['irudia']): ?>
                                        <img src="ARGAZKIAK/<?= htmlspecialchars($product['irudia']) ?>"
                                            style="width: 50px; height: 50px; object-fit: cover; margin:0; border: none;" alt="">
                                    <?php endif; ?>
                                    <?= htmlspecialchars($product['izena']) ?>
                                </div>
                            </td>
                            <td><?= number_format($product['prezioa'], 2) ?>€</td>
                            <td><?= $qty ?></td>
                            <td><?= number_format($line_total, 2) ?>€</td>
                            <td>
                                <a href="SASKIA_KUDEATU.php?action=remove&id=<?= $product['id'] ?>"
                                    class="btn-remove">Ezabatu</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (isset($_SESSION['erabiltzailea'])):
                $user = $_SESSION['erabiltzailea'];
                $sql_faktura = "SELECT * FROM fakturak WHERE (id_bezeroa = ? OR id_hornitzailea = ?) ORDER BY data DESC";
                $stmt_faktura = $pdo->prepare($sql_faktura);
                $stmt_faktura->execute([$user['id'], $user['id']]);
                $fakturak = $stmt_faktura->fetchAll();

                if (count($fakturak) > 0):
                    ?>
                    <div class="faktura-container"
                        style="max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h2 style="color: #2a5c4a; border-bottom: 2px solid #2a5c4a; padding-bottom: 10px; margin-bottom: 20px;">
                            Nire Fakturak</h2>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                            <?php foreach ($fakturak as $f): ?>
                                <div
                                    style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong style="display: block; font-size: 1.1em;">Faktura #<?= $f['id'] ?></strong>
                                        <span style="color: #666; font-size: 0.9em;"><?= $f['data'] ?></span>
                                        <span
                                            style="display: block; font-weight: bold; color: #2a5c4a; margin-top: 5px;"><?= $f['totala'] ?>€</span>
                                    </div>
                                    <a href="FAKTURA_DESKARGATU.php?id=<?= $f['id'] ?>" class="erosi-btn"
                                        style="text-decoration: none; padding: 8px 15px; font-size: 0.9em;">Deskargatu</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; endif; ?>

            <div class="cart-total">
                <p>Guztira: <?= number_format($total, 2) ?>€</p>
                <a href="KATALOGOA.php" class="saskia-btn" style="float: left;">Jarraitu erosten</a>
                <a href="SASKIA_KUDEATU.php?action=checkout" class="checkout-btn"
                    onclick="return confirm('Ziur zaude erosketa burutu nahi duzula? (Totala: <?= number_format($total, 2) ?>€)');">Eskatu
                    Orain</a>
            </div>
            <div style="clear: both;"></div>
        <?php endif; ?>
    </div>

    <?php include_once 'FOOTER.php'; ?>
</body>

</html>