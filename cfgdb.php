<?php
$DB_HOST 	= 'localhost';
$DB_USER 	= 'root';
$DB_PASSWD  = '';
$DB_NAME 	= 'presensi';

// Koneksi Database
@define("DB_HOST", $DB_HOST);
@define("DB_USER", $DB_USER);
@define("DB_PASSWD" , $DB_PASSWD);
@define("DB_NAME", $DB_NAME);
$conn = NEW mysqli($DB_HOST, $DB_USER, $DB_PASSWD, $DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
	die("Koneksi gagal: " . $conn->connect_error);
}
?>