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
        
        <?php
        $bilaketa = isset($_GET["bilaketa"]) ? $_GET["bilaketa"] : "";
        $ordena = isset($_GET["ordena"]) ? $_GET["ordena"] : "auto";
        ?>

        <div class="filtro-container">
            <form action="AZKEN BERRIAK.php" method="GET" class="katalogo">
                <div class="bilaketa form-group" style="margin-bottom:0">
                    <label for="bilaketa">Bilatu izenburua</label>
                    <input type="text" id="bilaketa" name="bilaketa" value="<?= htmlspecialchars($bilaketa) ?>" placeholder="Bilatu berriak..." class="filter-input">
                </div>

                <div class="aukera form-group" style="margin-bottom:0">
                    <label for="ordena">Ordenatu</label>
                    <select id="ordena" name="ordena" class="filter-select">
                        <option value="auto" <?= $ordena == "auto" ? "selected" : "" ?>>Automatikoa</option>
                        <option value="data_desc" <?= $ordena == "data_desc" ? "selected" : "" ?>>Berrienak lehenengo</option>
                        <option value="data_asc" <?= $ordena == "data_asc" ? "selected" : "" ?>>Zaharrenak lehenengo</option>
                        <option value="garrantzia" <?= $ordena == "garrantzia" ? "selected" : "" ?>>Garantzia (Altua -> Baxua)</option>
                    </select>
                </div>

                <button type="submit">Bidali</button>
            </form>
        </div>

        <div class="news-container">
            <?php
            try {
                $sql = "SELECT * FROM berriak WHERE 1=1";
                $params = [];

                if (!empty($bilaketa)) {
                    $sql .= " AND berria_izena LIKE ?";
                    $params[] = "%$bilaketa%";
                }

                switch ($ordena) {
                    case 'data_desc':
                        $sql .= " ORDER BY berria_data DESC, id DESC";
                        break;
                    case 'data_asc':
                        $sql .= " ORDER BY berria_data ASC, id ASC";
                        break;
                    case 'garrantzia':
                        $sql .= " ORDER BY FIELD(garrantzi_maila, 'ALTUA', 'ERTAINA', 'BAXUA'), id DESC";
                        break;
                    default:
                        $sql .= " ORDER BY id DESC";
                        break;
                }
                
                $stmt = $pdo->prepare($sql); 
                $stmt->execute($params);
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
                            <?php if (!empty($news['irudia'])): ?>
                                <div class="news-card-image-container">
                                    <img src="ARGAZKIAK/NOTICIAS/<?php echo htmlspecialchars($news['irudia']); ?>" 
                                         alt="<?php echo htmlspecialchars($news['berria_izena']); ?>" 
                                         class="news-card-image">
                                </div>
                            <?php endif; ?>
                            <h3 class="news-title clickable-title" 
                                onclick="openNewsModal(this)"
                                data-title="<?php echo htmlspecialchars($news['berria_izena']); ?>"
                                data-date="<?php echo htmlspecialchars($news['berria_data']); ?>"
                                data-priority="<?php echo htmlspecialchars($news['garrantzi_maila']); ?>"
                                data-priority-class="<?php echo $priorityClass; ?>"
                                data-content="<?php echo htmlspecialchars($news['berria']); ?>"
                                data-image="<?php echo htmlspecialchars($news['irudia']); ?>">
                                <?php echo htmlspecialchars($news['berria_izena']); ?>
                            </h3>
                            <div class="news-content">
                                <p><?php echo nl2br(htmlspecialchars(substr($news['berria'], 0, 150))) . (strlen($news['berria']) > 150 ? '...' : ''); ?></p>
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

    <!-- News Modal -->
    <div id="newsModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeNewsModal()">&times;</span>
            <div class="modal-header">
                <span id="modalDate" class="news-date"></span>
                <span id="modalPriority" class="priority-badge"></span>
            </div>
            <h2 id="modalTitle"></h2>
            <div class="modal-body">
                <div id="modalImageContainer" class="modal-image-container">
                    <!-- Image will be inserted here if exists -->
                </div>
                <div id="modalFullContent" class="modal-full-content"></div>
            </div>
        </div>
    </div>

    <script>
    function openNewsModal(element) {
        const title = element.getAttribute('data-title');
        const date = element.getAttribute('data-date');
        const priority = element.getAttribute('data-priority');
        const priorityClass = element.getAttribute('data-priority-class');
        const content = element.getAttribute('data-content');
        const image = element.getAttribute('data-image');

        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalDate').innerText = date;
        const priorityBadge = document.getElementById('modalPriority');
        priorityBadge.innerText = priority;
        priorityBadge.className = 'priority-badge ' + priorityClass;
        document.getElementById('modalFullContent').innerHTML = content.replace(/\n/g, '<br>');

        const imageContainer = document.getElementById('modalImageContainer');
        imageContainer.innerHTML = '';
        if (image && image.trim() !== '') {
            const img = document.createElement('img');
            img.src = 'ARGAZKIAK/NOTICIAS/' + image;
            img.alt = title;
            img.className = 'modal-news-image';
            imageContainer.appendChild(img);
        } else {
            // Optional: placeholder or just leave empty
            imageContainer.innerHTML = '<div class="image-placeholder">No image available</div>';
        }

        document.getElementById('newsModal').style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function closeNewsModal() {
        document.getElementById('newsModal').style.display = 'none';
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }

    // Close when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('newsModal');
        if (event.target == modal) {
            closeNewsModal();
        }
    }
    </script>

    <?php include 'FOOTER.php'; ?>
</body>
</html>
