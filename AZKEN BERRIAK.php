<?php
include 'INIT.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Azken berriak</title>
    <link rel="stylesheet" href="CSS_Erronka.css" />
    <link rel="icon" type="image/png" href="ARGAZKIAK/EJBE-BG.png"/>
</head>
<body>
    <?php include 'HEADER.php'; ?>
    <div class="main">
        <h1>AZKEN BERRIAK</h1>
        
        <div class="news-container">
            <?php
            try {
                $stmt = $pdo->prepare("SELECT * FROM berriak order by id desc"); 
                
                $stmt->execute();
                $news_items = $stmt->fetchAll();

                if (count($news_items) > 0) {
                    foreach ($news_items as $news) {
                        $priorityClass = 'priority-' . $news['garrantzi_maila'];
                        ?>
                        <div class="news-card">
                            <div class="news-header">
                                <span class="news-date"><?php echo htmlspecialchars($news['berria_data']); ?></span>
                                <span class="priority-badge <?php echo $priorityClass; ?>"><?php echo htmlspecialchars($news['garrantzi_maila']); ?></span>
                            </div>
                            <h3 class="news-title"><?php echo htmlspecialchars($news['berria_izena']); ?></h3>
                            <div class="news-content">
                                <p><?php echo nl2br(htmlspecialchars($news['berria'])); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>Ez dago berririk momentu honetan.</p>';
                }

            } catch (PDOException $e) {
                echo '<div class="alert alert-error">Errorea berriak kargatzean: ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
    </div>
    <?php include 'FOOTER.php'; ?>
</body>
</html>
