<?php 
include 'INIT.php';

if (isset($_SESSION['erabiltzailea'])) {
    header('Location: SARRERA.php');
    exit();
}

$errorea = '';
$arrakasta = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mota = $_POST['mota'] ?? 'bezeroa'; // 'bezeroa' edo 'hornitzailea'
    $pasahitza = $_POST['pasahitza'] ?? '';

    // Eremu komunak eta espezifikoak
    $email = $_POST['posta_elektronikoa'] ?? '';
    $telefonoa = $_POST['telefonoa'] ?? '';

    if (empty($email) || empty($pasahitza) || empty($mota)) {
        $errorea = 'Mesedez, bete derrigorrezko eremu guztiak.';
    } elseif (strlen($pasahitza) < 6) {
        $errorea = 'Pasahitzak gutxienez 6 karaktere izan behar ditu.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorea = 'Posta elektronikoaren formatua ez da zuzena.';
    } else {
        try {
            // Hash the password securely
            $pasahitza_hash = password_hash($pasahitza, PASSWORD_DEFAULT);

            // BEZEROA
            if ($mota === 'bezeroa') {
                $nan = $_POST['nan'] ?? '';
                $izena = $_POST['izena'] ?? '';
                $abizena = $_POST['abizena'] ?? '';

                if (empty($nan) || empty($izena) || empty($abizena)) {
                     $errorea = 'Mesedez, bete Bezeroaren datu guztiak (NAN, Izena, Abizena).';
                } elseif (!preg_match('/^[0-9]{8}[A-Z]$/', $nan)) {
                    $errorea = 'NANaren formatua ez da zuzena (8 zenbaki + letra maiuskula).';
                } else {
                    // Check duplicate
                    $stmt = $pdo->prepare("SELECT * FROM bezeroak WHERE NAN = ? OR email = ?");
                    $stmt->execute([$nan, $email]);
                    if ($stmt->rowCount() > 0) {
                        $errorea = 'NAN edo email hori dagoeneko erregistratuta dago bezero moduan.';
                    } else {
                        // Insert Hashed Password
                        $insert = $pdo->prepare("INSERT INTO bezeroak (NAN, izena, abizena, email, pasahitza) VALUES (?, ?, ?, ?, ?)");
                        if ($insert->execute([$nan, $izena, $abizena, $email, $pasahitza_hash])) {
                            $arrakasta = 'Bezeroa ondo erregistratu da!';
                            // Auto login
                            $bezeroId = $pdo->lastInsertId();
                            $_SESSION['erabiltzailea'] = [
                                'id' => $bezeroId,
                                'NAN' => $nan,
                                'izena' => $izena,
                                'abizena' => $abizena,
                                'email' => $email,
                                'mota' => 'bezeroa'
                            ];
                            echo '<script>setTimeout(function(){ window.location.href = "SARRERA.php"; }, 2000);</script>';
                        }
                    } 
                }
            } 
            // HORNITZAILEA
            else {
                $enpresaIzena = $_POST['izena_enpresa'] ?? ''; // Match HTML ID/Name
                $kontaktuIzena = $_POST['kontaktu_izena'] ?? '';
                $helbidea = $_POST['helbidea'] ?? '';

                if (empty($enpresaIzena) || empty($kontaktuIzena) || empty($helbidea) || empty($telefonoa)) {
                    $errorea = 'Mesedez, bete Hornitzailearen datu guztiak.';
                } else {
                    // Check duplicate
                    $stmt = $pdo->prepare("SELECT * FROM hornitzaileak WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->rowCount() > 0) {
                        $errorea = 'Email hori dagoeneko erregistratuta dago hornitzaile moduan.';
                    } else {
                        // Insert Hashed Password
                        $insert = $pdo->prepare("INSERT INTO hornitzaileak (izena, kontaktu_izena, email, helbidea, telefonoa, pasahitza) VALUES (?, ?, ?, ?, ?, ?)");
                        if ($insert->execute([$enpresaIzena, $kontaktuIzena, $email, $helbidea, $telefonoa, $pasahitza_hash])) {
                            $arrakasta = 'Hornitzailea ondo erregistratu da!';
                             // Auto login
                            $hornId = $pdo->lastInsertId();
                            $_SESSION['erabiltzailea'] = [
                                'id' => $hornId,
                                'izena' => $enpresaIzena, 
                                'kontaktu_izena' => $kontaktuIzena,
                                'email' => $email,
                                'telefonoa' => $telefonoa,
                                'mota' => 'hornitzailea'
                            ];
                            echo '<script>setTimeout(function(){ window.location.href = "SARRERA.php"; }, 2000);</script>';
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            $errorea = 'Datu-base errorea: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="utf-8">
    <title>Erregistratu</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
    <style>
        .hidden { display: none; }
    </style>
</head>
<body>
    <?php include 'HEADER.php'; ?>
    
    <main>
        <div class="login-container" style="max-width: 600px;">
            <h2>Erregistratu</h2>
            
            <?php if (!empty($errorea)): ?>
                <div class="alert alert-error">
                    <?php echo $errorea; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($arrakasta)): ?>
                <div class="alert alert-success">
                    <?php echo $arrakasta; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="">
                
                <div class="form-group role-selection" style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                    <label style="margin-right: 20px;"><input type="radio" name="mota" value="bezeroa" checked onchange="toggleForm()"> Bezeroa</label>
                    <label><input type="radio" name="mota" value="hornitzailea" onchange="toggleForm()"> Hornitzailea</label>
                </div>

                <!-- BEZEROA FIELDS -->
                <div id="bezeroa-fields">
                    <div class="form-group">
                        <label for="nan">NANa (DNI)</label>
                        <input type="text" id="nan" name="nan" placeholder="01234567A">
                    </div>
                    
                    <div class="form-group">
                        <label for="izena">Izena</label>
                        <input type="text" id="izena" name="izena">
                    </div>
                    
                    <div class="form-group">
                        <label for="abizena">Abizena</label>
                        <input type="text" id="abizena" name="abizena">
                    </div>
                </div>

                <!-- HORNITZAILEA FIELDS -->
                <div id="hornitzailea-fields" class="hidden">
                    <div class="form-group">
                        <label for="izena_enpresa">Enpresaren Izena</label>
                        <input type="text" id="izena_enpresa" name="izena_enpresa">
                    </div>
                    <div class="form-group">
                        <label for="kontaktu_izena">Kontaktuaren Izena</label>
                        <input type="text" id="kontaktu_izena" name="kontaktu_izena">
                    </div>
                    <div class="form-group">
                        <label for="helbidea">Helbidea</label>
                        <input type="text" id="helbidea" name="helbidea">
                    </div>
                </div>

                <!-- COMMON FIELDS -->
                <div class="form-group">
                    <label for="telefonoa">Telefonoa</label>
                    <input type="tel" id="telefonoa" name="telefonoa" placeholder="+34 688 452 317" required>
                </div>
                
                <div class="form-group">
                    <label for="posta_elektronikoa">Posta elektronikoa</label>
                    <input type="email" id="posta_elektronikoa" name="posta_elektronikoa" 
                           placeholder="example@gmail.com" required>
                </div>
                
                <div class="form-group">
                    <label for="pasahitza">Pasahitza</label>
                    <input type="password" name="pasahitza" id="pasahitza" required>
                </div>
                
                <div class="form-buttons">
                    <button type="reset">Ezabatu</button>
                    <button type="submit">Sortu</button>
                </div>
            </form>
            <br>
            <p class="login-link">
                <b>Dagoeneko kontua duzu? <a href="HASI SAIOA.php">Hasi saioa hemen</a></b>
            </p>
        </div>
    </main>
    
    <?php include 'FOOTER.php'; ?>
    <script>
        function toggleForm() {
            const mota = document.querySelector('input[name="mota"]:checked').value;
            const bezeroaFields = document.getElementById('bezeroa-fields');
            const hornitzaileaFields = document.getElementById('hornitzailea-fields');

            if (mota === 'bezeroa') {
                bezeroaFields.classList.remove('hidden');
                hornitzaileaFields.classList.add('hidden');
                // Set required
                document.getElementById('nan').required = true;
                document.getElementById('izena').required = true;
                document.getElementById('abizena').required = true;
                
                document.getElementById('izena_enpresa').required = false;
                document.getElementById('kontaktu_izena').required = false;
                document.getElementById('helbidea').required = false;

            } else {
                bezeroaFields.classList.add('hidden');
                hornitzaileaFields.classList.remove('hidden');
                // Set required
                document.getElementById('nan').required = false;
                document.getElementById('izena').required = false;
                document.getElementById('abizena').required = false;

                document.getElementById('izena_enpresa').required = true;
                document.getElementById('kontaktu_izena').required = true;
                document.getElementById('helbidea').required = true;
            }
        }
        // Initialize
        toggleForm();
    </script>
</body>
</html>