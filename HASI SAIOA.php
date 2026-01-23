<?php include 'INIT.php'; ?>

<?php
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
            $erabiltzailea = $stmt->fetch();

            if ($erabiltzailea && password_verify($pasahitza, $erabiltzailea['pasahitza'])) {
                $_SESSION['erabiltzailea'] = [
                    'id' => $erabiltzailea['id'],
                    'NAN' => $erabiltzailea['NAN'],
                    'izena' => $erabiltzailea['izena'],
                    'abizena' => $erabiltzailea['abizena'],
                    'email' => $erabiltzailea['email'],
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
        <h2>Hasi saioa</h2>
        
        <?php if (!empty($errorea)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #f5c6cb;">
                <?php echo $errorea; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="">
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
                <button type="submit">Sartu</button>
             </div>
        </form>
        
        <p style="margin-top: 20px;">
            <b>Ez duzu konturik? <a href="IZENA EMAN.php" style="color: #4a9b7c;">Erregistratu hemen</a></b>
        </p>
    </main>
    
    <?php include 'FOOTER.php'; ?>
</body>
</html>