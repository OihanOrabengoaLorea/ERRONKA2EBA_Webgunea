<?php
include_once 'INIT.php';

if (!isset($_SESSION['erabiltzailea'])) {
    exit('Sarbidea ukatua');
}

$id_faktura = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_faktura <= 0) {
    exit('Faktura ID baliogabea');
}

$ftp_server = "192.168.115.171";
$ftp_user = "user1";
$ftp_pass = "123";
$ftp_port = 21;

$archivo_remoto = "faktura_" . $id_faktura . ".pdf";
$directorio_local = "FAKTURAK/";
$archivo_local = $directorio_local . $archivo_remoto;

if (!is_dir($directorio_local)) {
    mkdir($directorio_local, 0777, true);
}

$conn_id = ftp_connect($ftp_server, $ftp_port);
$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);

if (!$conn_id || !$login_result) {
    exit('FTP konexio errorea');
}

ftp_pasv($conn_id, true);

if (ftp_get($conn_id, $archivo_local, $archivo_remoto, FTP_BINARY)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($archivo_local) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($archivo_local));
    readfile($archivo_local);
} else {
    echo "Errorea faktura deskargatzean";
}

ftp_close($conn_id);
?>
