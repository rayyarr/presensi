<?php
session_start(); // Mulai session

// Jika user belum login, alihkan ke halaman login
if (!isset($_SESSION['nip'])) {
	header("Location: login.php");
	exit();
}

// Jika tombol logout ditekan
if (isset($_POST['logout'])) {
	session_destroy(); // Hapus session
	header("Location: index.php"); // Alihkan ke halaman login setelah logout berhasil
	exit();
}

include_once 'sw-header.php';

/* Tampilkan tabel pengguna
if ($result !== false && $result->num_rows > 0) {
echo "<table>
<tr>
<th>ID</th>
<th>NIP</th>
<th>Nama</th>
<th>Jabatan ID</th>
<th>Guru</th>
</tr>";
while ($row = $result->fetch_assoc()) {
echo "<tr>
<td>" . $row["id"] . "</td>
<td>" . $row["nip"] . "</td>
<td>" . $row["nama"] . "</td>
<td>" . $row["jabatan_id"] . "</td>
<td>" . $row["guru"] . "</td>
</tr>";
}
echo "</table><br>";
} else {
echo "Tidak ada data pengguna.";
} */
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard | SMP SMA MKGR Kertasemaya</title>
<style>
	.mx-auto {
		max-width: 800px !important
	}

	.card {
		margin-top: 10px;
	}
</style>

<body>
	<div class="kolomkanan">
		<div class="mx-auto">

			<?php

			?>
			<!-- menampilkan gambar 
		<img width="100" height="100" src="foto_profil/<?php echo $nama_file; ?>" alt="Gambar Pengguna">
		-->
			<div class="card mb-3 p-3">
				<div class="leftP">
					<div>
						<div class="card-body" style="padding-top:0">
							<h5 class="card-title">Selamat datang,</h5>
						</div>
					</div>
					<div class="profileIcon leftC flex solo" style="padding-bottom:20px">
						<label class="a flexIns fc" for="forProfile">
							<span class="avatar flex center">
								<img class="iniprofil" src="foto_profil/<?php echo $nama_file; ?>"
									alt="<?php echo $nama_file; ?>">
							</span>
							<span class="n flex column">
								<span class="fontS">
									<?php
									if (mysqli_num_rows($result) > 0) {
										// tampilkan nama
										while ($row = mysqli_fetch_assoc($result)) {
											?>
											<h4>
												<?= $row['nama']; ?>
											</h4>
										</span>
										<p class="opacity" style="margin-bottom:0">
											NIP
											<?= $row['nip']; ?> -
											<?= $hasiljoin['jabatan_nama']; ?> -
											<?= $row['guru']; ?>
										</p>
										<?php
										}
									}
									?>
							</span>
						</label>
					</div>
				</div>
				<!--<div class="col-md-8">
				<div class="card-body">
					<h5 class="card-title">D3 Teknik Informatika 1C</h5>
					<p class="card-text"><small class="text-muted">Politeknik Negeri Indramayu</small></p>
				</div>
			</div>-->
				<div class="wallet-footer">
					<div class="item">
						<div class="sa">
							<a href="./absensi.php">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-calendar-plus"></i>
								</div>
								<strong>Absen</strong>
							</a>
						</div>
					</div>
					<div class="item">
						<div class="sa">
							<a href="./profil.php">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-person-lines-fill"></i>
								</div>
								<strong>Profil</strong>
							</a>
						</div>
					</div>
					<div class="item">
						<div class="sa">
							<a href="./riwayat.php">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-card-checklist"></i>
								</div>
								<strong>Riwayat</strong>
							</a>
						</div>
					</div>
					<div class="item">
						<div class="sa">
							<a href="./logout.php">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-box-arrow-left"></i>
								</div>
								<strong>Logout</strong>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="card p-3">
				<?php
				// set nilai kurun waktu
				$kurun_waktu = 7; // 7 hari terakhir absen
				
				// menghitung tanggal 7 hari yang lalu
				$tanggal_kurang = date('Y-m-d', strtotime('-' . $kurun_waktu . ' days'));

				// query untuk mengambil data absensi karyawan dengan nip tertentu dan kurun waktu tertentu
				$query = "SELECT absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, status_absen.nama_status, absen.keterangan
          FROM absen 
          JOIN status_absen ON absen.id_status = status_absen.id_status 
          WHERE absen.nip = $userid AND absen.tanggal_absen >= '$tanggal_kurang'
          ORDER BY absen.tanggal_absen DESC";

				// menjalankan query dan menyimpan hasilnya dalam variabel $result
				$result = mysqli_query($conn, $query);
				?>
				<div class="table-responsive">
					<table class="table table-bordered table-striped mt-3">
						<thead>
							<tr>
								<th>Tanggal Absen</th>
								<th>Jam Masuk</th>
								<th>Jam Keluar</th>
								<th>Status</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// loop untuk menampilkan data absensi karyawan
							while ($row = mysqli_fetch_assoc($result)) {
								// Format tanggal dalam bahasa Indonesia
								$hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
								$bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

								$tanggal = date_create($row['tanggal_absen']);
								$hari_tanggal = $hari[date_format($tanggal, "w")];
								$tanggal_indo = $hari_tanggal . ", " . date_format($tanggal, "d") . " " . $bulan[date_format($tanggal, "m") - 1] . " " . date_format($tanggal, "Y");

								// menampilkan data absensi karyawan dalam baris tabel
								echo "<tr>";
								echo "<td>" . $tanggal_indo . "</td>";
								echo "<td>" . $row['jam_masuk'] . "</td>";
								echo "<td>" . $row['jam_keluar'] . "</td>";
								echo "<td>" . $row['nama_status'] . "</td>";
								echo "<td>" . $row['keterangan'] . "</td>";
								echo "</tr>";
							}

							// fungsi untuk mengubah angka bulan menjadi nama bulan dalam bahasa Indonesia
							function bulan($angka_bulan)
							{
								$nama_bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
								return $nama_bulan[intval($angka_bulan)];
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>

</html>