<?php
include 'INIT.php';

try {
    // Check if column exists
    $stmt = $pdo->prepare("SHOW COLUMNS FROM hornitzaileak LIKE 'pasahitza'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE hornitzaileak ADD COLUMN pasahitza VARCHAR(255) NOT NULL AFTER email");
        echo "Column 'pasahitza' added successfully.";
    } else {
        echo "Column 'pasahitza' already exists.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
