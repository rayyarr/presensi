<?php
session_start(); // Mulai session
require_once('sw-header.php');
require_once('database.php');
require_once('Absenclass.php');
$obj = new Absensiswa;
$userid = $_SESSION['nip'];

if (isset($_POST['jarak'])) {
	$jarak = $_POST['jarak'];
	//echo "Jarak: " . $jarak . " kilometer";

	//pertama kita coba dapatkan dulu id absen berdasarkan data useridnya untuk proses update
	//jika variabel bernilai kosong maka kita kembalikan ke index
	if (empty($obj->get_idabsen($userid))) {
		echo
			'
					<script>
						swal.fire({
							title: "Gagal!",
							text: "Anda harus melakukan absen masuk terlebih dahulu",
							icon: "error",
						}).then((result) => {
							setTimeout(function () {
								window.location.href = "login";
							 }, 300);
						})
					</script>
					';
	} else {
		//Selanjutnya kita cek dulu apakah dia sudah melakukan absen keluar sebelumnya
		if ($obj->cek_Absenkeluar($userid)) {
			//jika sudah absen sebelumnya arahkan ke index.php
			echo
				'
				<script> 
					swal.fire({
						title: "Gagal!",
						text: "Anda sudah absen keluar hari ini",
						icon: "error",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>
				';
		} else {
			//tapi jika belum, kita lakukan query ke id user untuk mendapatkan id absen berdasarkan tgl masuk
			//jika dia belum melakukan absen masuk maka dia akan dikembalikan ke halaman utama
			if ($jarak <= 99) {
				//format tanggal akan dibuat seperti format di mysql
				$tgl_keluar = date('Y-m-d');
				$jam_keluar = date('H:i:s');

				if ($obj->update_Absenkeluar($tgl_keluar, $jam_keluar, $obj->id_absen)) {
					echo
						'
					<script>
						swal.fire({
							title: "Berhasil!",
							text: "Anda berhasil absen keluar hari ini!",
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
						text: "Anda gagal absen keluar hari ini!",
						icon: "error",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>
				';

				}
				//
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