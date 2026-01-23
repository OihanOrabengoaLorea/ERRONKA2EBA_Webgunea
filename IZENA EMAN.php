<?php
session_start();
include 'INIT.php';

$errorea = '';
$arrakasta = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nan = $_POST['nan'] ?? '';
    $izena = $_POST['izena'] ?? '';
    $abizena = $_POST['abizena'] ?? '';
    $email = $_POST['posta_elektronikoa'] ?? '';
    $pasahitza = $_POST['pasahitza'] ?? '';
    $telefonoa = $_POST['telefonoa'] ?? '';

    // Validar campos obligatorios
    if (empty($nan) || empty($izena) || empty($abizena) || empty($email) || empty($pasahitza)) {
        $errorea = 'Mesedez, bete eremu guztiak.';
    } elseif (strlen($pasahitza) < 6) {
        $errorea = 'Pasahitzak gutxienez 6 karaktere izan behar ditu.';
    } else {
        try {
            // Comprobar si el usuario ya existe
            $stmt = $pdo->prepare("SELECT * FROM erabiltzaileak WHERE NANa = ? OR email = ?");
            $stmt->execute([$nan, $email]);
            
            if ($stmt->rowCount() > 0) {
                $errorea = 'NAN edo email hori dagoeneko erregistratuta dago.';
            } else {
                // Insertar nuevo usuario
                $pasahitza_hash = password_hash($pasahitza, PASSWORD_DEFAULT);
                
                $insert = $pdo->prepare("INSERT INTO erabiltzaileak (NANa, izena, abizena, email, pasahitza, rola) VALUES (?, ?, ?, ?, ?, 'erabiltzailea')");
                
                if ($insert->execute([$nan, $izena, $abizena, $email, $pasahitza_hash])) {
                    $arrakasta = 'Erregistroa arrakastatsua! Orain saioa hasi dezakezu.';
                    
                    // Iniciar sesión automáticamente
                    $stmt = $pdo->prepare("SELECT * FROM erabiltzaileak WHERE email = ?");
                    $stmt->execute([$email]);
                    $erabiltzailea = $stmt->fetch();
                    
                    if ($erabiltzailea) {
                        $_SESSION['erabiltzailea'] = [
                            'id' => $erabiltzailea['id'],
                            'izena' => $erabiltzailea['izena'],
                            'abizena' => $erabiltzailea['abizena'],
                            'email' => $erabiltzailea['email'],
                            'rola' => $erabiltzailea['rola']
                        ];
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
        <h2>Erregistratu</h2>
        
        <?php if (!empty($errorea)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #f5c6cb;">
                <?php echo $errorea; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($arrakasta)): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #c3e6cb;">
                <?php echo $arrakasta; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div>
                <label for="nan">NANa (DNI)</label>
                <input type="text" id="nan" name="nan" placeholder="01234567A" required>
            </div>
            
            <div>
                <label for="izena">Izena</label>
                <input type="text" id="izena" name="izena" required>
            </div>
            
            <div>
                <label for="abizena">Abizena</label>
                <input type="text" id="abizena" name="abizena" required>
            </div>
            
            <div>
                <label for="telefonoa">Telefonoa</label>
                <input type="tel" id="telefonoa" name="telefonoa" placeholder="+34 688 452 317">
            </div>
            
            <div>
                <label for="posta_elektronikoa">Posta elektronikoa</label>
                <input type="email" id="posta_elektronikoa" name="posta_elektronikoa" placeholder="example@gmail.com" required>
            </div>
            
            <div>
                <label for="pasahitza">Pasahitza</label>
                <input type="password" name="pasahitza" id="pasahitza" required>
            </div>
            
            <div>
                <button type="reset">Ezabatu</button>
                <button type="submit">Sortu</button>
            </div>
        </form>
        
        <p style="margin-top: 20px;">
            <b> Dagoeneko kontua duzu? <a href="HASI SAIOA.php" style="color: #4a9b7c;">Hasi saioa hemen</a></b>
        </p>
    </main>
    
    <?php include 'FOOTER.php'; ?>
</body>
</html>