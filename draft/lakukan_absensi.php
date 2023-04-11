<?php
date_default_timezone_set('Asia/Jakarta');
// Memastikan bahwa data dikirimkan melalui metode POST dari form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header("Location: login.php");
}

// Mengambil data dari Ajax POST request
$userid = $_POST['userid'];
$tanggal_absen = date('Y-m-d');
$jam = date('H:i:s');
$status_id = $_POST['status_id'];
$keterangan = $_POST['keterangan'];

// Menghubungkan ke database menggunakan PDO
$host = 'localhost';
$dbname = 'presensi';
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

$query = "SELECT * FROM absen WHERE userid='$userid' AND tanggal_absen='$tanggal_absen'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
	// Jika data sudah ada, berikan pesan error dan hentikan proses
	echo "Anda sudah melakukan absensi pada hari ini!";
} else {
	// Jika data belum ada, lakukan proses insert
	$sql = "INSERT INTO absen (userid, tanggal_absen, jam_masuk, tgl_keluar, jam_keluar, status_id, keterangan) 
VALUES (:userid, :tanggal_absen, :jam_masuk, :tgl_keluar, :jam_keluar, :status_id, :keterangan)";
	$stmt = $pdo->prepare($sql);

	// Mengeksekusi statement SQL dengan memasukkan nilai parameter
	$stmt->execute([
		':userid' => $userid,
		':tanggal_absen' => $tanggal_absen,
		':jam_masuk' => $jam,
		':tgl_keluar' => $tanggal_absen,
		':jam_keluar' => $jam,
		':status_id' => $status_id,
		':keterangan' => $keterangan
	]);
}
?>