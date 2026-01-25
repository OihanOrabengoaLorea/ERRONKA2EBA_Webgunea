<header>
    <nav>
        <div class="nav">
            <img src="ARGAZKIAK/EJBE-CleanLogo-BG.png" width="40px" height="20px">
            <a href="SARRERA.php">HASIERA</a>
            <a href="AZKEN BERRIAK.php">BERRIAK</a>
            <a href="ENPRESAREN HISTORIA.php">HISTORIOA</a>
            <a href="KATALOGOA.php">KATALOGOA</a>
            
            <?php 
            if (isset($_GET['action']) && $_GET['action'] == 'irtxi') {
                session_destroy();
                header('Location: SARRERA.php');
                exit();
            }
            
            if (isset($_SESSION['erabiltzailea'])): ?>
                <span style="color: #2a5c4a; font-weight: 600;">
                    Kaixo, <?php echo htmlspecialchars($_SESSION['erabiltzailea']['izena']); ?>!
                </span>
                <a href="?action=irtxi">IRTEN</a>
            <?php else: ?>
                <a href="HASI SAIOA.php">SARTU</a>
                <a href="IZENA EMAN.php">ERREGISTRATU</a>
            <?php endif; ?>
        </div>
    </nav>
</header>