<?php
// IMPORTANTE: Ni un solo espacio o línea vacía antes de <?php
include_once 'INIT.php';

if (!isset($_SESSION['erabiltzailea'])) { 
    exit('Sarbidea ukatua'); 
}

$id_faktura = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_faktura <= 0) { 
    exit('ID baliogabea'); 
}

// 1. Obtener la ruta de la factura
$stmt = $pdo->prepare("SELECT faktura_ruta FROM fakturak WHERE id = ?");
$stmt->execute([$id_faktura]);
$faktura = $stmt->fetch();

if (!$faktura || empty($faktura['faktura_ruta'])) {
    exit('Faktura hau jada deskargatu da edo ez da aurkitu.');
}

$ruta_archivo = $faktura['faktura_ruta']; 

if (file_exists($ruta_archivo)) {
    
    // Limpiar cualquier buffer previo para evitar archivos corruptos
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Cabeceras de descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($ruta_archivo) . '"');
    header('Content-Length: ' . filesize($ruta_archivo));
    header('Pragma: public');
    
    // Enviar el archivo al navegador
    readfile($ruta_archivo);
    
    // --- AQUÍ LA MAGIA PARA QUE DESAPAREZCA ---
    // Opción A: Borrar el registro por completo
    $delete = $pdo->prepare("DELETE FROM fakturak WHERE id = ?");
    $delete->execute([$id_faktura]);
    
    /* Opción B: Si prefieres que el registro se quede pero no se pueda descargar:
    $update = $pdo->prepare("UPDATE fakturak SET faktura_ruta = NULL WHERE id = ?");
    $update->execute([$id_faktura]);
    */

    // Opcional: Borrar el archivo físico de la carpeta 'fakturak' para no acumular basura
    // unlink($ruta_archivo); 

    exit;
} else {
    echo "Errorea: Fitxategia ez dago zerbitzarian.";
}
?>