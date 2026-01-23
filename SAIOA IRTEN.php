<?php
session_start();
session_destroy();
header('Location: SARRERA.php');
exit();
?>