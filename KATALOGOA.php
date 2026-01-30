<?php
include_once "INIT.php";
include_once "HEADER.php";

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
  <title>Katalogoa</title>
  <link rel="stylesheet" href="CSS_Erronka.css" />
  <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
</head>
<body>
<br>
  <div class="filtro-container">
    <form class="katalogo" action="KATALOGOA.php" method="GET">
      <div class="aukera">
        <label for="aukera">Mota:</label>
        <select id="aukera" name="aukera">
          <option value="guztiak" <?= $aukera == "guztiak" ? "selected" : "" ?>>Guztiak</option>
          <option value="mugikorra" <?= $aukera == "mugikorra" ? "selected" : "" ?>>Mugikorra</option>
          <option value="ordenagailu eramangarria" <?= $aukera == "ordenagailu eramangarria" ? "selected" : "" ?>>Ordenagailu eramangarria</option>
          <option value="tableta" <?= $aukera == "tableta" ? "selected" : "" ?>>Tableta</option>
          <option value="sagua" <?= $aukera == "sagua" ? "selected" : "" ?>>Sagua</option>
          <option value="teklatua" <?= $aukera == "teklatua" ? "selected" : "" ?>>Teklatua</option>
          <option value="monitorea" <?= $aukera == "monitorea" ? "selected" : "" ?>>Monitorea</option>
          <option value="inprimagailua" <?= $aukera == "inprimagailua" ? "selected" : "" ?>>Inprimagailua</option>
          <option value="biltegiratzea" <?= $aukera == "biltegiratzea" ? "selected" : "" ?>>Biltegiratzea</option>
          <option value="sarea" <?= $aukera == "sarea" ? "selected" : "" ?>>Sarea</option>
          <option value="erloju adimenduna" <?= $aukera == "erloju adimenduna" ? "selected" : "" ?>>Erloju adimenduna</option>
          <option value="aurikularrak" <?= $aukera == "aurikularrak" ? "selected" : "" ?>>Aurikularrak</option>
          <option value="kamera" <?= $aukera == "kamera" ? "selected" : "" ?>>Kamera</option>
        </select>
      </div> 
      <br>
      <div class="ordena">
        <label>Ordena:</label>
        <br>
        <label for="asc">Behetik-gora</label>
        <input type="radio" id="asc" name="ordena" value="asc" <?= $ordena == "asc" ? "checked" : "" ?>>
        <br>
        <label for="desc">Goitik-behera</label>
        <input type="radio" id="desc" name="ordena" value="desc" <?= $ordena == "desc" ? "checked" : "" ?>>
      </div>
      <br>
      <div class="bilaketa">
        <label for="bilaketa">Bilaketa:</label>
        <input type="text" id="bilaketa" name="bilaketa" value="<?= htmlspecialchars($bilaketa) ?>" placeholder="Bilatu produktuak...">
      </div>
      <br>
      <button>Bidali</button>
    </form>
  </div>
  
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
  document.addEventListener('DOMContentLoaded', function() {
    // Simple Console Log
    const productos = document.querySelectorAll('.container > div');
    if (productos.length === 0) {
      console.log('Produktuak ez dira aurkitu.');
    } else {
      console.log('Produktuak aurkitutak: ' + productos.length);
    }
    
    // JS for Cart Badge Counter (No AJAX)
    const addLinks = document.querySelectorAll('a.saskia-btn');
    addLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Update badge immediately
            let badge = document.getElementById('cart-count');
            if (!badge) {
                // If badge doesn't exist yet, create it
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
                
                 // Visual feedback
                badge.style.transform = 'scale(1.2)';
                setTimeout(() => badge.style.transform = 'scale(1)', 200);
            }
        });
    });
  });
  </script>
</body>
</html>