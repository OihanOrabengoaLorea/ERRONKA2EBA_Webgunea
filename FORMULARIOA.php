<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <title>EJBE - Formularioa</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
    </head>
    <body>
<?php 
include 'INIT.php';
include "header.php";

$mezua = '';
$user = isset($_SESSION['erabiltzailea']) ? $_SESSION['erabiltzailea'] : null;


$mota = $user ? ($user['mota'] ?? 'bezeroa') : 'bezeroa'; 
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
        
    } else {

        $enpresa_izena = $user['izena']; // We stored company name in 'izena'
        $harremanetarako = $user['kontaktu_izena'];
        $email = $user['email'];
        $telefonoa = $user['telefonoa'];
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aukeratutakoMota = $_POST['Aukera'] ?? '';
    

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
            <div class="role-selection" <?php if ($user) echo 'style="display:none;"'; ?>>
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
                    <input type="text" id="harremanetarako_pertsona" name="harremanetarako_pertsona" value="<?php echo htmlspecialchars($harremanetarako); ?>" placeholder="Izen eta abizena" required disabled>
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

                <div class="form-group full-width">
                    <label for="produktua">Produktua</label>
                    <input type="text" id="produktua" name="produktua" placeholder="Produktu bat ipini" required disabled>
                </div>
                <div class="form-group full-width">
                    <label for="produktu_kopurua">Produktu kopurua</label>
                    <input type="number" id="produktu_kopurua" name="produktu_kopurua" min="1" disabled>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="produktuaren_deskribapena">Produktuaren deskribapena</label>
                <input type="text" id="produktuaren_deskribapena" name="produktuaren_deskribapena" placeholder="Produktuaren egitura eta berezitasunak aipatu" required disabled>
            </div>
            <div class="form-group full-width">
                <label for="oharrak">Oharrak</label>
                <input type="text" id="oharrak" name="oharrak" placeholder="Gure teknikarientzarako ohar batzuk" required disabled>
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
    const rolAukerak = document.querySelectorAll('input[name="Aukera"]');
    

    function toggleField(id, show) {
        const input = document.getElementById(id);
        if (!input) return;
        

        input.disabled = !show;
        

        const group = input.closest('.form-group');
        if (group) {
            group.style.display = show ? 'block' : 'none';
        }
    }


    function eguneratuEgoera(mota) {

        const commonFields = [
            'posta_elektronikoa', 'telefonoa', 
            'produktu_mota', 'produktu_marka', 'produktua', 
            'produktuaren_deskribapena', 'oharrak'
        ];
        
        commonFields.forEach(id => toggleField(id, true));


        const bezeroaFields = ['izena', 'abizena'];

        const hornitzaileaFields = ['harremanetarako_pertsona', 'enpresaren_izena', 'produktu_kopurua'];

        if (mota === 'bezeroa') {
            bezeroaFields.forEach(id => toggleField(id, true));
            hornitzaileaFields.forEach(id => toggleField(id, false));
        } else if (mota === 'hornitzailea') {
            bezeroaFields.forEach(id => toggleField(id, false));
            hornitzaileaFields.forEach(id => toggleField(id, true));
        }
        

        document.querySelector('input[type="reset"]').disabled = false;
        document.querySelector('input[type="submit"]').disabled = false;
    }

    rolAukerak.forEach(aukera => {
      aukera.addEventListener('change', () => {
        eguneratuEgoera(aukera.value);
      });
    });


    const hautatua = document.querySelector('input[name="Aukera"]:checked');
    if(hautatua) {
        eguneratuEgoera(hautatua.value);
    } else {

        ['izena', 'abizena', 'harremanetarako_pertsona', 'enpresaren_izena', 'produktu_kopurua'].forEach(id => toggleField(id, false));
    }
    </script>
    
</html>