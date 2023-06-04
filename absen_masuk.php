<?php
session_start(); // Mulai session
require_once('cfgall.php');
$id_jadwal = mysqli_fetch_array(mysqli_query($conn, "SELECT id_jadwal FROM jadwal WHERE nama_hari = '$hari_ini'"))['id_jadwal'];

if (isset($_POST['photo'], $_POST['jarak'], $_POST['latlong'])) {
	$jarak = $_POST['jarak'];
	$compressedPhoto = $_POST['photo'];
	$latlong = $_POST['latlong'];

	# kita cek dulu apakah dia sudah absen sebelumnya
	if ($obj->cek_Absenmasuk($userid)) {
		//jika sudah absen sebelumnya arahkan ke login.php
		echo
			'
				<script> 
					swal.fire({
						title: "Gagal!",
						text: "Anda sudah absen hari ini",
						icon: "error",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>
				';
	} else {
		if ($jarak <= 99) { // jarak maksimal (km) agar bisa masuk
			$id_status = 1; // 1 yaitu masuk
			$tanggal_absen = date('Y-m-d');
			$jam_masuk = date('H:i:s');

			// Mendekode data gambar yang dikirim sebagai base64
			$decodedPhoto = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $compressedPhoto));

			// Menentukan direktori penyimpanan gambar
			$targetDirectory = "hasil_absen/";
			$file_foto = $userid . "_" . date('Y-m-d') . ".png";
			$targetPath = $targetDirectory . $file_foto;
			file_put_contents($targetPath, $decodedPhoto);

			// Eksekusi query dan mengambil isi jadwal masuk
			$sqlW = "SELECT waktu_masuk FROM jadwal WHERE id_jadwal = $id_jadwal";
			$hasilW = mysqli_query($conn, $sqlW);

			// Cek apakah query berhasil dijalankan
			if (mysqli_num_rows($hasilW) > 0) {
				// Looping untuk membaca nilai waktu_masuk dari setiap baris data
				while ($row = mysqli_fetch_assoc($hasilW)) {
					$waktu_masuk = $row["waktu_masuk"];
				}
			} else {
				$waktu_masuk = date('H:i:s');
			}

			// menghitung selisih waktu dengan waktu yang ditentukan (07:15:00)
			$tanggal_waktu_target = date('Y-m-d') . $waktu_masuk;
			$selisih_waktu = strtotime($jam_masuk) - strtotime($tanggal_waktu_target);

			// jika selisih waktu lebih dari 0 (artinya terlambat)
			if ($selisih_waktu > 0) {
				if ($selisih_waktu < 3600) {
					$menit_terlambat = ceil($selisih_waktu / 60); // menghitung selisih waktu dalam menit
					if ($menit_terlambat == 60) {
						$keterangan = $jarak . ' kilometer' . ", TERLAMBAT 1 jam";
					} else {
						$keterangan = $jarak . ' kilometer' . ", TERLAMBAT $menit_terlambat menit";
					}
				} else {
					$jam_terlambat = floor($selisih_waktu / 3600); // menghitung selisih waktu dalam jam
					$menit_terlambat = ceil(($selisih_waktu % 3600) / 60); // menghitung selisih waktu dalam menit
					if ($menit_terlambat == 60) {
						$jam_terlambat++; // jika terdapat 60 menit, tambahkan 1 jam ke jumlah jam terlambat
						$menit_terlambat = 0; // reset jumlah menit terlambat menjadi 0
					}
					if ($jam_terlambat == 1) {
						$keterangan = $jarak . ' kilometer' . ", TERLAMBAT 1 jam $menit_terlambat menit";
					} else {
						$keterangan = $jarak . ' kilometer' . ", TERLAMBAT $jam_terlambat jam $menit_terlambat menit";
					}
				}
			} else {
				$keterangan = $jarak . ' kilometer';
			}
			// eksekusi
			if ($obj->insert_Absenmasuk($userid, $id_status, $id_jadwal, $tanggal_absen, $jam_masuk, $keterangan, $file_foto, $latlong)) {
				echo
					'
				<script> 
					swal.fire({
						title: "Berhasil Masuk!",
						html: "JARAK ' . $keterangan . '",
						icon: "success",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>
				';

			} else {
				echo
					'
				<script> 
					swal.fire({
						title: "Gagal!",
						text: "Anda gagal absen hari ini!",
						icon: "error",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>
				';

			}
		} else {
			echo '
			<script>
				swal.fire({
					title: "Gagal!",
					text: "Anda tidak berada pada lokasi SMP SMA MKGR Kertasemaya!",
					icon: "error",
				}).then((result) => {
					setTimeout(function () {
						window.location.href = "login";
					 }, 300);
				})
			</script>';
		}
	}
} else {
	echo '
		<script>
			swal.fire({
				title: "Gagal!",
				text: "Jarak tidak terdeteksi!",
				icon: "error",
			}).then((result) => {
				setTimeout(function () {
					window.location.href = "login";
				 }, 300);
			})
		</script>
	';
}
?>