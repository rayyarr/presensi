<?php
/*$host = "localhost";
$user = "root";
$pass = "";
$db = "dbcrud";
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
die("Tidak bisa terkoneksi ke database");
}

$nip = "";
$password = "";
$nama = "";
$jabatan = "";
$guru = "";
$sukses = "";
$error = "";
*/
session_start(); // Mulai session

// Jika user belum login, alihkan ke halaman login
if (!isset($_SESSION['nip'])) {
    header("Location: login.php");
    exit();
}

include_once 'sw-header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | SMP SMA MKGR Kertasemaya</title>
    <style>
        .mx-auto {
            max-width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto" style="margin-top:120px">

        <div class="card mb-3 p-3">
            <div class="leftP">
                <div class="profileIcon leftC flex solo">
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
        </div>
    </div>

    <div class="mx-auto">
		<!-- untuk mengeluarkan data -->
		<div class="card">
			<div class="card-header text-white bg-secondary">
				Riwayat Absensi
			</div>
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<!--<th scope="col">NIP</th>-->
							<th scope="col">Tanggal</th>
							<th scope="col">Jam Masuk</th>
							<th scope="col">Jam Keluar</th>
							<th scope="col">Status</th>
                            <th scope="col">Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($d->rowCount() > 0) {
							while ($row = $d->fetch(PDO::FETCH_ASSOC)) {
								?>
								<tr>
									<!--<th>
																								<?= $row['userid']; ?>
									</th>-->
									<th>
										<?= $row['tanggal_absen']; ?>
									</th>
									<th>
										<?= $row['jam_masuk']; ?>
									</th>
									<th>
										<?= $row['jam_keluar']; ?>
									</th>
									<th>
										<?= $row['nama_status']; ?>
									</th>
                                    <th>
										<?= $row['keterangan']; ?>
									</th>
								</tr>
							<?php }
						} ?>
					</tbody>
				</table>
				<!--<form method="POST" action="">
					<a class="btn btn-outline-dark btn-sm" href="absen_masuk.php">Absen masuk</a>
					<a class="btn btn-outline-dark btn-sm" href="absen_keluar.php">Absen keluar</a>
					<a class="btn btn-outline-success btn-sm" href="absen_sakit.php">Absen sakit</a>
					<button type="submit" name="logout" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>-->
                <a class="btn btn-outline-primary btn-sm" href="ekspor_rekap.php">Ekspor EXCEL</a>
			</div>
		</div>
        
    </body>

    </html>