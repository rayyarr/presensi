<?php
/*$host = "localhost";
$user = "root";
$pass = "";
$db = "dbcrud";
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) { //cek koneksi
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
    header("Location: login");
    exit();
}

include_once 'sw-header.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tabel Absen</title>
</head>
<style>
    .mx-auto {
        max-width: 800px
    }

    .card {
        margin-top: 10px;
    }

    .table>tbody {
        font-size: 14px
    }
</style>

<body>
    <div class="kolomkanan">
        <div class="mx-auto">

            <div class="card mb-5 p-3">
                <div class="leftP">
                    <div class="profileIcon leftC flex solo">
                        <label class="a flexIns fc" for="forProfile">
                            <span class="avatar flex center">
                                <img class="iniprofil" src="foto_profil/<?php echo $nama_file; ?>"
                                    alt="<?php echo $nama_file; ?>">
                            </span>
                            <span class="n flex column">
                                <span class="fontS">
                                    <h4>
                                        <?php echo $nama ?>
                                    </h4>
                                </span>
                                <p class="opacity" style="margin-bottom:0">
                                    NIP
                                    <?php echo $nip ?> -
                                    <?php echo $jabatan ?> -
                                    <?php echo $guru ?>
                                </p>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card p-3" style="margin-bottom:50px">

                <!--<form method="post" action="">
                <label for="bulan">Pilih Bulan:</label>
                <select name="bulan" id="bulan">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
                <button type="submit" name="submit">Tampilkan</button>
            </form>-->

                <?php
                // set default timezone
                date_default_timezone_set('Asia/Jakarta');

                // ambil tahun dan bulan dari parameter GET, atau gunakan tanggal hari ini
                $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
                $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

                // ambil jumlah hari pada bulan ini
                $jumlah_hari = date('t', strtotime($tahun . '-' . $bulan . '-01'));

                // buat array kosong untuk absen
                $absen = array();

                // ambil data absen dari database
                $sql = "SELECT tanggal_absen, nip, keterangan FROM absen WHERE YEAR(tanggal_absen) = '$tahun' AND MONTH(tanggal_absen) = '$bulan'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // tambahkan data absen ke array
                        $tanggal = date('j', strtotime($row['tanggal_absen']));
                        $absen[$row['nip']][$tanggal] = $row['keterangan'];
                    }
                }
                ?>
                <h3 class="mb-3">Tabel Absen</h3>
                <form method="get" action="">
                    <div class="form-group row mb-3">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="tahun" name="tahun"
                                value="<?php echo $tahun; ?>">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="bulan" class="col-sm-2 col-form-label">Bulan:</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="bulan" name="bulan">
                                <?php
                                $nama_bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                                for ($i = 1; $i <= 12; $i++) {
                                    $selected = ($i == $bulan) ? 'selected' : '';
                                    echo '<option value="' . sprintf("%02d", $i) . '" ' . $selected . '>' . $nama_bulan[$i - 1] . '</option>';
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a class="btn btn-success"
                        href="eksporEXCEL.php?tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>">Ekspor EXCEL</a>
                </form>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="text-align:center">
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // ambil jumlah hari pada bulan dan tahun yang dipilih
                            $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                            $nama_hari_arr = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
                            $nama_bulan_arr = array(
                                'Januari',
                                'Februari',
                                'Maret',
                                'April',
                                'Mei',
                                'Juni',
                                'Juli',
                                'Agustus',
                                'September',
                                'Oktober',
                                'November',
                                'Desember'
                            );

                            // looping tanggal dari 1 sampai jumlah hari pada bulan ini
                            for ($i = 1; $i <= $jumlah_hari; $i++) {
                                // ambil nama hari dalam bahasa Indonesia
                                $nama_hari = $nama_hari_arr[date('N', strtotime($tahun . '-' . $bulan . '-' . $i))];

                                // ambil nama bulan dalam bahasa Indonesia
                                $nama_bulan = $nama_bulan_arr[intval($bulan) - 1];
                                // tampilkan baris tabel
                                $bg_color = ($nama_hari == 'Minggu') ? 'bg-warning' : '';
                                echo '<tr class="' . $bg_color . '">';
                                echo '<td>' . $nama_hari . ', ' . str_pad($i, 2, '0', STR_PAD_LEFT) . ' ' . $nama_bulan . ' ' . $tahun . '</td>';

                                // ambil data absen dari database berdasarkan tanggal dan nip
                                $query = "SELECT absen.id_absen, absen.nip, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan 
                        FROM absen 
                        JOIN status_absen ON absen.id_status = status_absen.id_status 
                        WHERE nip = $userid AND tanggal_absen = '$tahun-$bulan-" . str_pad($i, 2, '0', STR_PAD_LEFT) . "'
                        ORDER BY absen.id_absen DESC";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    // jika data absen ditemukan, tampilkan status dan keterangan
                                    $data_absen = mysqli_fetch_assoc($result);
                                    $jam_masuk = $data_absen['jam_masuk'];
                                    $jam_keluar = $data_absen['jam_keluar'];
                                    $status = $data_absen['nama_status'];
                                    $keterangan = $data_absen['keterangan'];
                                } else {
                                    // jika data absen tidak ditemukan, tampilkan status kosong dan keterangan kosong
                                    $jam_masuk = '';
                                    $jam_keluar = '';
                                    $status = '';
                                    $keterangan = '-';
                                    // tambahkan keterangan untuk hari Minggu
                                    if ($nama_hari == 'Minggu') {
                                        $keterangan = 'Libur Akhir Pekan';
                                    } else {
                                        $keterangan = '';
                                    }
                                }

                                echo '<td>' . $jam_masuk . '</td>';
                                echo '<td>' . $jam_keluar . '</td>';
                                echo '<td>' . $status . '</td>';
                                echo '<td>' . $keterangan . '</td>';
                                echo '</tr>';
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