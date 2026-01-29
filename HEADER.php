<header>
    <nav>
        <div class="nav">
            <img src="ARGAZKIAK/EJBE-CleanLogo-BG.png" width="40px" height="20px">
            <a href="SARRERA.php">HASIERA</a>
            <a href="AZKEN BERRIAK.php">BERRIAK</a>
            <a href="ENPRESAREN HISTORIA.php">HISTORIOA</a>
            <a href="KATALOGOA.php">KATALOGOA</a>
            
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
                    <!-- Simple SVG Cart Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge"><?php echo $cart_count; ?></span>
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