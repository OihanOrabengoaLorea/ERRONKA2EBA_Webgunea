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

// FTP server might map directly to the folder, so we check if we need to strip 'fakturak/' prefix
$remote_path = $ruta_archivo;
if (strpos(strtolower($remote_path), 'fakturak/') === 0) {
    $remote_path = substr($remote_path, 9);
}

$ftp_server = "192.168.115.171";
$ftp_user = "user1";
$ftp_pass = "123";

$conn_id = ftp_connect($ftp_server);
if (!$conn_id) {
    exit("Errorea: Ezin izan da FTP zerbitzariarekin konektatu.");
}

$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
if (!$login_result) {
    ftp_close($conn_id);
    exit("Errorea: FTP autentikazioak huts egin du.");
}

ftp_pasv($conn_id, true);

$temp_handle = fopen('php://temp', 'r+');

if (ftp_fget($conn_id, $temp_handle, $remote_path, FTP_BINARY)) {
    rewind($temp_handle);
    $fstat = fstat($temp_handle);
    $size = $fstat['size'];

    if (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($ruta_archivo) . '"');
    header('Content-Length: ' . $size);
    header('Pragma: public');
    
    fpassthru($temp_handle);
    fclose($temp_handle);
    ftp_close($conn_id);
    exit;
} else {
    fclose($temp_handle);
    ftp_close($conn_id);
    echo "Errorea: Fitxategia ez da aurkitu zerbitzarian ($remote_path).";
}
?>