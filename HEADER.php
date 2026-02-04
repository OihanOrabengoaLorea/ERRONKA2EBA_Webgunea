<header>
    <nav>
        <div class="nav">
            <img src="ARGAZKIAK/EJBE-CleanLogo-BG.png" width="40px" height="20px">
            <a href="SARRERA.php">HASIERA</a>
            <a href="KATALOGOA.php">KATALOGOA</a>
            <a href="AZKEN BERRIAK.php">BERRIAK</a>
            <a href="ENPRESAREN HISTORIA.php">HISTORIOA</a>
            <a href="KATALOGOA.php">KATALOGOA</a>
            <a href="FORMULARIOA.php">FORMULARIOA</a>
            
            <?php 
            if (isset($_GET['action']) && $_GET['action'] == 'itxi') {
                session_destroy();
                header('Location: SARRERA.php');
                exit();
            }
            
            if (isset($_SESSION['erabiltzailea'])): 
                $cart_count = 0;
                if (isset($_SESSION['saskia'])) {
                    $cart_count = array_sum($_SESSION['saskia']);
                }
            ?>
                <a href="SASKIA.php" class="cart-link" style="text-decoration: none; color: black;">
                   <img src="ARGAZKIAK/Cesta.png" width="24px" height="24px" alt="Saskia"/>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge" id="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="?action=itxi">IRTEN</a>
                <span style="color: #2a5c4a; font-weight: 600;">
                    Kaixo, <?php echo htmlspecialchars($_SESSION['erabiltzailea']['izena']); ?>!
                </span>
            <?php else: ?>
                <a href="HASI SAIOA.php">SARTU</a>
                <a href="IZENA EMAN.php">ERREGISTRATU</a>
            <?php endif; ?>
        </div>
    </nav>
</header>