<?php 
include 'INIT.php';

if (isset($_SESSION['erabiltzailea'])) {
    header('Location: SARRERA.php');
    exit();
}

$errorea = '';
$arrakasta = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nan = $_POST['nan'] ?? '';
    $izena = $_POST['izena'] ?? '';
    $abizena = $_POST['abizena'] ?? '';
    $email = $_POST['posta_elektronikoa'] ?? '';
    $pasahitza = $_POST['pasahitza'] ?? '';
    $telefonoa = $_POST['telefonoa'] ?? '';

    if (empty($nan) || empty($izena) || empty($abizena) || empty($email) || empty($pasahitza)) {
        $errorea = 'Mesedez, bete eremu guztiak.';
    } elseif (strlen($pasahitza) < 6) {
        $errorea = 'Pasahitzak gutxienez 6 karaktere izan behar ditu.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorea = 'Posta elektronikoaren formatua ez da zuzena.';
    } elseif (!preg_match('/^[0-9]{8}[A-Z]$/', $nan)) {
        $errorea = 'NANaren formatua ez da zuzena (8 zenbaki + letra maiuskula).';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM bezeroak WHERE NAN = ? OR email = ?");
            $stmt->execute([$nan, $email]);
            
            if ($stmt->rowCount() > 0) {
                $errorea = 'NAN edo email hori dagoeneko erregistratuta dago.';
            } else {
                $pasahitza_hash = password_hash($pasahitza, PASSWORD_DEFAULT);
                $insert = $pdo->prepare("INSERT INTO bezeroak (NAN, izena, abizena, email, pasahitza) VALUES (?, ?, ?, ?, ?)");

                if ($insert->execute([$nan, $izena, $abizena, $email, $pasahitza_hash])) {
                    
                    $arrakasta = 'Erregistroa arrakastatsua! Orain saioa hasi dezakezu.';
                    
                    $stmt = $pdo->prepare("SELECT * FROM bezeroak WHERE email = ?");
                    $stmt->execute([$email]);
                    $bezeroa = $stmt->fetch();
                    
                    if ($bezeroa) {
                        $_SESSION['erabiltzailea'] = [
                            'id' => $bezeroa['id'],
                            'NAN' => $bezeroa['NAN'],
                            'izena' => $bezeroa['izena'],
                            'abizena' => $bezeroa['abizena'],
                            'email' => $bezeroa['email']
                        ];
                        
                        echo '<script>
                            setTimeout(function() {
                                window.location.href = "SARRERA.php";
                            }, 2000);
                        </script>';
                    }
                } else {
                    $errorea = 'Errorea gertatu da erregistroan.';
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
</head>
<body>
    <?php include 'HEADER.php'; ?>
    
    <main>
        <div class="login-container">
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
                <div class="form-group">
                    <label for="nan">NANa (DNI)</label>
                    <input type="text" id="nan" name="nan" placeholder="01234567A" required>
                </div>
                
                <div class="form-group">
                    <label for="izena">Izena</label>
                    <input type="text" id="izena" name="izena" required>
                </div>
                
                <div class="form-group">
                    <label for="abizena">Abizena</label>
                    <input type="text" id="abizena" name="abizena" required>
                </div>
                
                <div class="form-group">
                    <label for="telefonoa">Telefonoa</label>
                    <input type="tel" id="telefonoa" name="telefonoa" placeholder="+34 688 452 317">
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
</body>
</html>