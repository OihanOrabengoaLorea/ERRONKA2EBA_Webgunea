<?php
include_once "INIT.php";

$aukera = isset($_GET["aukera"]) ? $_GET["aukera"] : "guztiak";
$bilaketa = isset($_GET["bilaketa"]) ? $_GET["bilaketa"] : "";
$ordena = isset($_GET["ordena"]) ? $_GET["ordena"] : "asc";
$sql = "SELECT * FROM produktuak WHERE 1=1";
$params = [];

if ($aukera != "guztiak") {
  $sql .= " AND mota = ?";
  $params[] = $aukera;
}

if (!empty($bilaketa)) {
  $sql .= " AND izena LIKE ?";
  $params[] = "%$bilaketa%";
}

$sql .= " ORDER BY izena " . ($ordena == "desc" ? "DESC" : "ASC");

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>EJBE - Katalogoa</title>
  <link rel="stylesheet" href="CSS_Erronka.css" />
  <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png" />
</head>

<body>
  <?php include_once "HEADER.php"; ?>
  <br>
  <div class="filtro-container">
    <form class="katalogo" action="KATALOGOA.php" method="GET">
      <div class="aukera form-group" style="margin-bottom:0">
        <label for="aukera">Mota</label>
        <select id="aukera" name="aukera" class="filter-select">
          <option value="guztiak" <?= $aukera == "guztiak" ? "selected" : "" ?>>Guztiak</option>
          <option value="mugikorra" <?= $aukera == "mugikorra" ? "selected" : "" ?>>Mugikorra</option>
          <option value="ordenagailu eramangarria" <?= $aukera == "ordenagailu eramangarria" ? "selected" : "" ?>>
            Ordenagailu eramangarria</option>
          <option value="tableta" <?= $aukera == "tableta" ? "selected" : "" ?>>Tableta</option>
          <option value="sagua" <?= $aukera == "sagua" ? "selected" : "" ?>>Sagua</option>
          <option value="teklatua" <?= $aukera == "teklatua" ? "selected" : "" ?>>Teklatua</option>
          <option value="monitorea" <?= $aukera == "monitorea" ? "selected" : "" ?>>Monitorea</option>
          <option value="inprimagailua" <?= $aukera == "inprimagailua" ? "selected" : "" ?>>Inprimagailua</option>
          <option value="biltegiratzea" <?= $aukera == "biltegiratzea" ? "selected" : "" ?>>Biltegiratzea</option>
          <option value="sarea" <?= $aukera == "sarea" ? "selected" : "" ?>>Sarea</option>
          <option value="erloju adimenduna" <?= $aukera == "erloju adimenduna" ? "selected" : "" ?>>Erloju adimenduna
          </option>
          <option value="aurikularrak" <?= $aukera == "aurikularrak" ? "selected" : "" ?>>Aurikularrak</option>
          <option value="kamera" <?= $aukera == "kamera" ? "selected" : "" ?>>Kamera</option>
        </select>
      </div>

      <div class="bilaketa form-group" style="margin-bottom:0">
        <label for="bilaketa">Bilaketa</label>
        <input type="text" id="bilaketa" name="bilaketa" value="<?= htmlspecialchars($bilaketa) ?>"
          placeholder="Bilatu produktuak..." class="filter-input">
      </div>

      <div class="ordena">
        <label>Ordena</label>
        <div style="display:flex; gap:15px; margin-top:5px;">
          <label for="asc"
            style="font-weight:400; font-size:14px; display:flex; align-items:center; gap:5px; margin:0;">
            <input type="radio" id="asc" name="ordena" value="asc" <?= $ordena == "asc" ? "checked" : "" ?>> Behetik-gora
          </label>
          <label for="desc"
            style="font-weight:400; font-size:14px; display:flex; align-items:center; gap:5px; margin:0;">
            <input type="radio" id="desc" name="ordena" value="desc" <?= $ordena == "desc" ? "checked" : "" ?>>
            Goitik-behera
          </label>
        </div>
      </div>

      <button>Bidali</button>
    </form>
  </div>

  <?php if (isset($_SESSION['erabiltzailea'])): 
    $user = $_SESSION['erabiltzailea'];
    $sql_faktura = "SELECT * FROM fakturak WHERE (id_bezeroa = ? OR id_hornitzailea = ?) ORDER BY data DESC";
    $stmt_faktura = $pdo->prepare($sql_faktura);
    $stmt_faktura->execute([$user['id'], $user['id']]);
    $fakturak = $stmt_faktura->fetchAll();
    
    if (count($fakturak) > 0):
  ?>
  <div class="faktura-container" style="max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="color: #2a5c4a; border-bottom: 2px solid #2a5c4a; padding-bottom: 10px; margin-bottom: 20px;">Nire Fakturak</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
      <?php foreach ($fakturak as $f): ?>
      <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9; display: flex; justify-content: space-between; align-items: center;">
        <div>
          <strong style="display: block; font-size: 1.1em;">Faktura #<?= $f['id'] ?></strong>
          <span style="color: #666; font-size: 0.9em;"><?= $f['data'] ?></span>
          <span style="display: block; font-weight: bold; color: #2a5c4a; margin-top: 5px;"><?= $f['totala'] ?>€</span>
        </div>
        <a href="FAKTURA_DESKARGATU.php?id=<?= $f['id'] ?>" class="erosi-btn" style="text-decoration: none; padding: 8px 15px; font-size: 0.9em;">Deskargatu</a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; endif; ?>
  
  <div class="container">
    <?php
    if ($stmt->rowCount() > 0) {
      foreach ($stmt as $row) {
        echo "<div>";
        $irudia = !empty($row["irudia"]) ? $row["irudia"] : "default.jpg";
        echo "<img src='./ARGAZKIAK/" . $irudia . "'/>";
        echo "<h3>" . $row["izena"] . "</h3>";
        echo "<p>" . $row["prezioa"] . "€</p>";

        if (isset($_SESSION['erabiltzailea'])) {
          echo "<div class='saskia-btn-group'>";
          echo "<a href='SASKIA_KUDEATU.php?action=add&id=" . $row['id'] . "&mode=silent' target='cart_iframe' class='saskia-btn'>Sartu saskira</a>";
          echo "<a href='SASKIA_KUDEATU.php?action=buy_now&id=" . $row['id'] . "' class='erosi-btn'>Erosi orain</a>";
          echo "</div>";
        } else {
          echo "<div class='saskia-btn-group'>";
          echo "<button class='saskia-btn' onclick=\"alert('Mesedez, saioa hasi produktua erosteko')\">Sartu saskira</button>";
          echo "<button class='erosi-btn' onclick=\"alert('Mesedez, saioa hasi produktua erosteko')\">Erosi orain</button>";
          echo "</div>";
        }

        echo "</div>";
      }
    } else {
      echo "<p style='text-align:center; width:100%; color: #2a5c4a; font-size: 16px; padding: 20px;'>Ez da produkturik aurkitu zure bilaketarekin.</p>";
    }
    ?>
  </div>

  <?php include_once 'FOOTER.php'; ?>

  <iframe name="cart_iframe" style="display:none;"></iframe>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const productos = document.querySelectorAll('.container > div');
      if (productos.length === 0) {
        console.log('Produktuak ez dira aurkitu.');
      } else {
        console.log('Produktuak aurkitutak: ' + productos.length);
      }

      window.updateCartBadge = function () {
        let badge = document.getElementById('cart-count');
        if (!badge) {
          const cartLink = document.querySelector('.cart-link');
          if (cartLink) {
            badge = document.createElement('span');
            badge.id = 'cart-count';
            badge.className = 'cart-badge';
            badge.textContent = '0';
            cartLink.appendChild(badge);
          }
        }
        if (badge) {
          let count = parseInt(badge.textContent);
          if (isNaN(count)) count = 0;
          badge.textContent = count + 1;

          badge.style.transform = 'scale(1.2)';
          setTimeout(() => badge.style.transform = 'scale(1)', 200);
        }
      };
    });
  </script>
</body>

</html>