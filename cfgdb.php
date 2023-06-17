<?php
require_once 'cfgcom.php';

// Koneksi Database
$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$db = $config['db'];

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Jakarta');
?>