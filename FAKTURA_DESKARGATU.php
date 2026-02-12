<?php
include_once 'INIT.php';

if (!isset($_SESSION['erabiltzailea'])) { 
    exit('Sarbidea ukatua'); 
}

$id_faktura = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_faktura <= 0) { 
    exit('ID baliogabea'); 
}

$stmt = $pdo->prepare("SELECT faktura_ruta FROM fakturak WHERE id = ?");
$stmt->execute([$id_faktura]);
$faktura = $stmt->fetch();

if (!$faktura || empty($faktura['faktura_ruta'])) {
    exit('Faktura hau jada deskargatu da edo ez da aurkitu.');
}

$ruta_archivo = $faktura['faktura_ruta']; 

if (file_exists($ruta_archivo)) {
    
    if (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($ruta_archivo) . '"');
    header('Content-Length: ' . filesize($ruta_archivo));
    header('Pragma: public');
    
    readfile($ruta_archivo);
    
    exit;
} else {
    echo "Errorea: Fitxategia ez dago zerbitzarian.";
}
?>