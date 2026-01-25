<?php 
include 'INIT.php';

if (isset($_SESSION['erabiltzailea'])) {
    header('Location: SARRERA.php');
    exit();
}

$errorea = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['posta_elektronikoa'] ?? '';
    $pasahitza = $_POST['pasahitza'] ?? '';

    if (empty($email) || empty($pasahitza)) {
        $errorea = 'Mesedez, bete eremu guztiak.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM bezeroak WHERE email = ?");
            $stmt->execute([$email]);
            $bezeroa = $stmt->fetch();

            if ($bezeroa && $bezeroa['pasahitza'] === $pasahitza) {
                $_SESSION['erabiltzailea'] = [
                    'id' => $bezeroa['id'],
                    'NAN' => $bezeroa['NAN'],
                    'izena' => $bezeroa['izena'],
                    'abizena' => $bezeroa['abizena'],
                    'email' => $bezeroa['email']
                ];
                
                header('Location: SARRERA.php');
                exit();
            } else {
                $errorea = 'Email edo pasahitza okerra.';
            }
        } catch (PDOException $e) {
            $errorea = 'Datu-base errorea: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasi saioa</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
</head>
<body>
    <?php include 'HEADER.php'; ?>
    
    <main>
        <div class="login-container">
            <h2>Hasi saioa</h2>
            
            <?php if (!empty($errorea)): ?>
                <div class="alert alert-error">
                    <?php echo $errorea; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="">
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
                    <button type="submit">Sartu</button>
                </div>
            </form>
            <p class="login-link">
                <br>
                <b>Ez duzu konturik? <a href="IZENA EMAN.php">Erregistratu hemen</a></b>
            </p>
        </div>
    </main>
    
    <?php include 'FOOTER.php'; ?>
</body>
</html>