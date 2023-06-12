<?php
session_start(); // Mulai session
require_once('cfgall.php');

$result = mysqli_query($conn, "SELECT * FROM jadwal WHERE nama_hari = '$hari_ini'");
$row = mysqli_fetch_array($result);
$waktu_pulang = $row['waktu_pulang'];
$waktu_pulang = date('H:i', strtotime($waktu_pulang)); // mengubah format waktu
$waktu_pulang = $waktu_pulang . " WIB"; // menambahkan "WIB" pada akhir string
$waktu_sekarang = date('H:i:s');
$waktu_sekarang = date('H:i', strtotime($waktu_sekarang)); // mengubah format waktu
$waktu_sekarang = $waktu_sekarang . " WIB"; // menambahkan "WIB" pada akhir string

if ($waktu_sekarang > $waktu_pulang) {

	if (isset($_POST['jarak'])) {
		$jarak = $_POST['jarak'];

		//jika variabel bernilai kosong maka kita kembalikan
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
				//tapi jika belum, kita lakukan query ke id user untuk mendapatkan id absen berdasarkan tgl masuk
				//jika dia belum melakukan absen masuk maka dia akan dikembalikan ke halaman utama
				if ($jarak <= 99) {
					//format tanggal akan dibuat seperti format di mysql
					$tgl_keluar = date('Y-m-d');
					$jam_keluar = date('H:i:s');

					if ($obj->update_Absenkeluar($tgl_keluar, $jam_keluar, $obj->id_absen)) {
						?>
						<script>
							swal.fire({
								title: "Berhasil!",
								text: "Anda berhasil absen keluar hari ini pada pukul <?php echo $waktu_sekarang ?>!",
								icon: "success",
							}).then((result) => {
								setTimeout(function () {
									window.location.href = "login";
								}, 300);
							})
						</script>
						<?php

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
} else {
	?>
	<script>
		swal.fire({
			title: "Gagal!",
			text: "Hanya diperbolehkan keluar pada pukul <?php echo $waktu_pulang ?> atau lebih!",
			icon: "error",
		}).then((result) => {
			setTimeout(function () {
				window.location.href = "login";
			}, 300);
		})
	</script>
	<?php
}
?>