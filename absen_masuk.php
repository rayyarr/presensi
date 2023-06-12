<?php
session_start(); // Mulai session
require_once('cfgall.php');

$jarak_ideal = mysqli_fetch_array(mysqli_query($conn, "SELECT jarak FROM pengaturan WHERE id_pengaturan = 1"))['jarak'];
$batas_telat = mysqli_fetch_array(mysqli_query($conn, "SELECT batas_telat FROM pengaturan WHERE id_pengaturan = 1"))['batas_telat'];

$tanggal_absen = date('Y-m-d');
$jam_masuk = date('H:i:s');

$result = mysqli_query($conn, "SELECT id_jadwal, status, waktu_masuk FROM jadwal WHERE nama_hari = '$hari_ini'");
$row = mysqli_fetch_array($result);
$id_jadwal = $row['id_jadwal'];
$status = $row['status'];
$waktu_masuk = $row['waktu_masuk'];

$waktu_masuk = strtotime($waktu_masuk);
$jam_masuk = strtotime($jam_masuk);
// Menghitung selisih waktu masuk dan waktu pengguna dalam menit
$selisih_menit = round(($jam_masuk - $waktu_masuk) / 60);

if ($status == 'Aktif') {
	if (isset($_POST['photo'], $_POST['jarak'], $_POST['latlong'])) {
		if ($selisih_menit <= $batas_telat) {
			$jarak = $_POST['jarak'];
			$compressedPhoto = $_POST['photo'];
			$latlong = $_POST['latlong'];

			# Cek apakah dia sudah absen sebelumnya
			if ($obj->cek_Absenmasuk($userid)) {
				echo
					'
					<script> 
						swal.fire({
							title: "Gagal!",
							text: "Anda sudah absen hari ini",
							icon: "error",
						}).then((result) => {
							setTimeout(function () {
								window.location.href = "beranda";
							 }, 300);
						})
					</script>
					';
			} else {
				$jarak = floatval($jarak);
				$jarak_konv = floor($jarak);
				if ($jarak_konv < $jarak_ideal) { // jarak maksimal (km) agar bisa masuk
					$id_status = 1; // 1 yaitu masuk

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

					$jam_masuk = date('H:i:s');

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
			?>
			<script>
				swal.fire({
					title: "Gagal!",
					text: "Anda sudah melebihi batas terlambat (<?php echo $batas_telat ?> menit)!",
					icon: "error",
				}).then((result) => {
					setTimeout(function () {
						window.location.href = "login";
					}, 300);
				})
			</script>
			<?php
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
	echo '
		<script>
			swal.fire({
				title: "Gagal!",
				text: "Hari Ini Hari Libur!",
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