<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <title>Formularioa</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
    </head>
    <body>
<?php 
include 'INIT.php';
include "header.php";

$mezua = '';
$user = isset($_SESSION['erabiltzailea']) ? $_SESSION['erabiltzailea'] : null;

// Auto-fill Logic
$mota = $user ? ($user['mota'] ?? 'bezeroa') : 'bezeroa'; // 'bezeroa' default if not logged in, but inputs are disabled?
$izena = '';
$abizena = '';
$email = '';
$telefonoa = '';
$harremanetarako = '';
$enpresa_izena = '';

if ($user) {
    if ($mota === 'bezeroa') {
        $izena = $user['izena'];
        $abizena = $user['abizena'];
        $email = $user['email'];
        // Phone might not be in session for bezeroa if we didn't store it, 
        // but let's assume valid session struct from login
    } else {
        // Hornitzailea
        $enpresa_izena = $user['izena']; // We stored company name in 'izena'
        $harremanetarako = $user['kontaktu_izena'];
        $email = $user['email'];
        $telefonoa = $user['telefonoa'];
    }
}

// FORM SUBMISSION
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aukeratutakoMota = $_POST['Aukera'] ?? '';
    
    // Get all fields
    $izenaPost = $_POST['izena'] ?? null;
    $abizenaPost = $_POST['abizena'] ?? null;
    $harremanetarakoPost = $_POST['harremanetarako_pertsona'] ?? null;
    $postaPost = $_POST['posta_elektronikoa'] ?? '';
    $telefonoaPost = $_POST['telefonoa'] ?? null;
    $enpresaIzenaPost = $_POST['enpresaren_izena'] ?? null;
    $produktuMota = $_POST['produktu_mota'] ?? '';
    $produktuMarka = $_POST['produktu_marka'] ?? null;
    $produktua = $_POST['produktua'] ?? '';
    $produktuKopurua = $_POST['produktu_kopurua'] ?? null;
    $deskribapena = $_POST['produktuaren_deskribapena'] ?? '';
    $oharrak = $_POST['oharrak'] ?? null;

    try {
        $sql = "INSERT INTO formularioa (
            Bezero_mota, Izena, Abizena, Harremanetako_pertsona, 
            Posta_elektronikoa, Telefonoa, Enpresaren_izena, 
            Produktu_mota, Produktu_marka, Produktua, 
            Produktu_kopurua, Produktuaren_deskribapena, Oharrak
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $aukeratutakoMota, $izenaPost, $abizenaPost, $harremanetarakoPost,
            $postaPost, $telefonoaPost, $enpresaIzenaPost,
            $produktuMota, $produktuMarka, $produktua,
            $produktuKopurua, $deskribapena, $oharrak
        ]);
        
        $mezua = "<div class='alert alert-success'>Formularioa ondo bidali da!</div>";
    } catch (PDOException $e) {
        $mezua = "<div class='alert alert-error'>Errorea formularioa bidaltzean: " . $e->getMessage() . "</div>";
    }
}
?>
    <main>
    <div class="formulario-container">
        <h2 style="text-align: center; color: #2a5c4a; margin-bottom: 30px;">Formularioa</h2>
        
        <?php echo $mezua; ?>

        <form action="#" method="post" class="formularioa-form">
            <div class="role-selection">
                <label class="radio-label">
                    <input type="radio" name="Aukera" value="bezeroa" class="mota" required 
                    <?php if ($mota === 'bezeroa') echo 'checked'; ?>> Bezeroa
                </label>
                <label class="radio-label">
                    <input type="radio" name="Aukera" value="hornitzailea" class="mota" 
                    <?php if ($mota === 'hornitzailea') echo 'checked'; ?>> Hornitzailea
                </label>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="izena">Izena</label>
                    <input type="text" id="izena" name="izena" value="<?php echo htmlspecialchars($izena); ?>" required disabled>
                </div>
                <div class="form-group">
                    <label for="abizena">Abizena</label>
                    <input type="text" id="abizena" name="abizena" value="<?php echo htmlspecialchars($abizena); ?>" required disabled>
                </div>
                <div class="form-group">
                    <label for="harremanetarako_pertsona">Harremanetarako pertsona</label>
                    <input type="text" id="harremanetarako_pertsona" name="harremanetarako_pertsona" value="<?php echo htmlspecialchars($harremanetarako); ?>" required disabled>
                </div>
                <div class="form-group"  >
                    <label for="posta_elektronikoa">Posta elektronikoa</label>
                    <input type="email" id="posta_elektronikoa" name="posta_elektronikoa" value="<?php echo htmlspecialchars($email); ?>" placeholder="example@gmail.com" required disabled>
                </div>
                <div class="form-group">
                    <label for="telefonoa">Telefonoa</label>
                    <input type="tel" id="telefonoa" name="telefonoa" value="<?php echo htmlspecialchars($telefonoa); ?>" placeholder="+34 688 452 317" disabled>
                </div>
                <div class="form-group">
                    <label for="enpresaren_izena">Enpresaren izena</label>
                    <input type="text" id="enpresaren_izena" name="enpresaren_izena" value="<?php echo htmlspecialchars($enpresa_izena); ?>" required disabled>
                </div>
                <div class="form-group">
                    <label for="produktu_mota">Produktu mota</label>
                    <input type="text" id="produktu_mota" name="produktu_mota" required disabled>
                </div>
                <div class="form-group">
                    <label for="produktu_marka">Produktu marka</label>
                    <input type="text" id="produktu_marka" name="produktu_marka" disabled>
                </div>

                <div class="form-group">
                    <label for="produktua">Produktua</label>
                    <input type="text" id="produktua" name="produktua" required disabled>
                </div>
                <div class="form-group">
                    <label for="produktu_kopurua">Produktu kopurua</label>
                    <input type="number" id="produktu_kopurua" name="produktu_kopurua" min="1" disabled>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="produktuaren_deskribapena">Produktuaren deskribapena</label>
                <input type="text" id="produktuaren_deskribapena" name="produktuaren_deskribapena" required disabled>
            </div>
            <div class="form-group full-width">
                <label for="oharrak">Oharrak</label>
                <input type="text" id="oharrak" name="oharrak" disabled>
            </div>
            <div class="form-buttons">
                <button type="reset">Ezabatu</button>
                <button type="submit">Bidali</button>
            </div>
        </form>
    </div>
    </main>
    <?php
    include "footer.php";
    ?>   
    </body>
    <script>
    // Include the same script logic but ensure it runs correctly with pre-filled values
    const rolAukerak = document.querySelectorAll('input[name="Aukera"]');
    const eremuGuztiak = document.querySelectorAll(
      'input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="reset"], input[type="submit"]'
    );

    function desgaituDenak() {
        // Only disable inputs that SHOULD be switchable. 
        // Caution: disabled inputs are NOT sent in POST. We need to enable them before submit or use <input type="hidden">
        // Simpler for this level: Enable them based on selection immediately.
        
        // Actually, logic below re-enables them.
        eremuGuztiak.forEach(e => {
            // Keep values if already there
             e.disabled = true;
        });
    }

    // Function to handle state based on current selection
    function eguneratuEgoera(mota) {
        desgaituDenak();
        
        if (mota === 'bezeroa') {
          document.getElementById('izena').disabled = false;
          document.getElementById('abizena').disabled = false;
          document.getElementById('posta_elektronikoa').disabled = false;
          document.getElementById('telefonoa').disabled = false;
          document.getElementById('produktu_mota').disabled = false;
          document.getElementById('produktu_marka').disabled = false;
          document.getElementById('produktua').disabled = false;
          document.getElementById('oharrak').disabled = false;
          document.querySelector('input[type="reset"]').disabled = false;
          document.querySelector('input[type="submit"]').disabled = false;
          
          document.getElementById('produktuaren_deskribapena').disabled = false; 

        } else if (mota === 'hornitzailea') {
          document.getElementById('harremanetarako_pertsona').disabled = false;
          document.getElementById('enpresaren_izena').disabled = false;
          document.getElementById('posta_elektronikoa').disabled = false;
          document.getElementById('telefonoa').disabled = false;
          document.getElementById('produktu_mota').disabled = false;
          document.getElementById('produktu_marka').disabled = false;
          document.getElementById('produktua').disabled = false;
          document.getElementById('produktu_kopurua').disabled = false;
          document.getElementById('produktuaren_deskribapena').disabled = false;
          document.getElementById('oharrak').disabled = false;
          document.querySelector('input[type="reset"]').disabled = false;
          document.querySelector('input[type="submit"]').disabled = false;
        }
    }

    rolAukerak.forEach(aukera => {
      aukera.addEventListener('change', () => {
        eguneratuEgoera(aukera.value);
      });
    });

    // Run on load to set initial state
    const hautatua = document.querySelector('input[name="Aukera"]:checked');
    if(hautatua) {
        eguneratuEgoera(hautatua.value);
    } else {
        desgaituDenak();
    }
  </script>
    
</html>