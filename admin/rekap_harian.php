<?php
session_start();

include_once 'main-admin.php';


// set default timezone
date_default_timezone_set('Asia/Jakarta');

// ambil tahun dan bulan dari parameter GET, atau gunakan tanggal hari ini
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

// ambil jumlah hari pada bulan ini
$jumlah_hari = date('t', strtotime($tahun . '-' . $bulan . '-01'));


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
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$nama_hari = $nama_hari_arr[date('N', strtotime($tanggal))];
$nama_bulan = $nama_bulan_arr[intval(date('m', strtotime($tanggal))) - 1];

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'hapus') {
    $id = $_GET['id'];
    $sqlfotoabsen = $conn->query("SELECT foto_absen FROM absen WHERE id_absen = '$id'");
    if ($sqlfotoabsen->num_rows > 0) {
        $row = $sqlfotoabsen->fetch_assoc();
        $fotoAbsen = $row["foto_absen"];
        $fotoPath = '../hasil_absen/' . $fotoAbsen;
        if (file_exists($fotoPath)) {
            unlink($fotoPath);
        }
    }

    $sql1 = "DELETE FROM absen WHERE id_absen = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tabel Absen</title>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />

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

    td.text-center {
        vertical-align: middle;
    }

    .ftabsen,
    .swal2-image {
        border-radius: 8px
    }

    .ftabsen:hover {
        cursor: pointer;
        transform: scale(1.1);
        transition: all .3s ease
    }
</style>

<body>
    <!--Modal Map-->
    <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Peta Lokasi</h5>
                </div>
                <div class="modal-body">
                    <div id="mapid" style="height: 400px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        onClick="$('#mapModal').modal('hide')">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="kolomkanan">
        <div class="mx-auto">

            <div class="card p-3" style="margin-bottom:50px">

                <h3 class="mb-3">Tabel Absen -
                    <?php echo $nama_hari . ', ' . date('d', strtotime($tanggal)) . ' ' . $nama_bulan . ' ' . date('Y', strtotime($tanggal)) ?>
                </h3>

                <!-- Tampilkan form input kalender dan tombol cari -->
                <form method="get">
                    <div class="form-group">
                        <label for="tanggal" class="col-sm-2 col-form-label">Input tanggal:</label>
                        <div class="form-group row">
                            <div class="col-sm">
                                <input type="date" id="tanggal" name="tanggal" value="<?php echo date('Y-m-d'); ?>"
                                    class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form>

                <br>

                <?php
                $query = "SELECT COUNT(*) AS total_absensi FROM absen WHERE tanggal_absen = '$tanggal'";
                $result = mysqli_query($conn, $query);
                $data = mysqli_fetch_assoc($result);
                $totalAbsensi = $data['total_absensi'];

                if ($totalAbsensi > 0) {
                    ?>
                    <!-- Tampilkan tabel absensi -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="text-align:center">
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th width="180">Keterangan</th>
                                    <th width="50">Foto</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $bg_color = ($nama_hari == 'Minggu') ? 'bg-warning' : '';

                                   $query = "SELECT absen.id_absen, absen.nip, pengguna.nama, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan, absen.foto_absen, absen.latlong 
    FROM absen 
    JOIN status_absen ON absen.id_status = status_absen.id_status
    JOIN pengguna ON absen.nip = pengguna.nip
    WHERE tanggal_absen = '$tanggal'
    ORDER BY absen.jam_masuk DESC";
                                   $result = mysqli_query($conn, $query);

                                   if (mysqli_num_rows($result) > 0) {
                                       while ($data_absen = mysqli_fetch_assoc($result)) {
                                           $id = $data_absen['id_absen'];
                                           $nip = $data_absen['nip'];
                                           $nama = $data_absen['nama'];
                                           $jam_masuk = $data_absen['jam_masuk'];
                                           $jam_keluar = $data_absen['jam_keluar'];
                                           $status = $data_absen['nama_status'];
                                           $keterangan = $data_absen['keterangan'];
                                           $fotoAbsen = $data_absen['foto_absen'];
                                           $latlong = $data_absen['latlong'];

                                           ?>
                                        <tr class="<?php echo $bg_color; ?>">
                                            <td>
                                                <?php echo $nip; ?>
                                            </td>
                                            <td>
                                                <?php echo $nama; ?>
                                            </td>
                                            <td>
                                                <?php echo $jam_masuk; ?>
                                            </td>
                                            <td>
                                                <?php echo $jam_keluar; ?>
                                            </td>
                                            <td>
                                                <?php echo $status; ?>
                                            </td>
                                            <td>
                                                <?php echo $keterangan; ?>
                                            </td>

                                            <?php if (!empty($fotoAbsen)): ?>
                                                <td class="text-center">
                                                    <img class="ftabsen" src="../hasil_absen/<?php echo $fotoAbsen; ?>" alt="Foto Absen"
                                                        id="<?php echo $i ?>" width="50px" height="50px"
                                                        onclick="showFoto('<?php echo $fotoAbsen; ?>', '<?php echo $nama; ?>')">
                                                </td>
                                            <?php else: ?>
                                                <td></td>
                                            <?php endif; ?>

                                            <?php if (!empty($latlong)): ?>
                                                <td class="text-center">
                                                    <button type="button" class="tmlokasi btn btn-primary btn-sm"
                                                        onclick="showModalMap('<?php echo $latlong; ?>', '<?php echo $nama; ?>')">Lihat</button>
                                                </td>
                                            <?php else: ?>
                                                <td></td>
                                            <?php endif; ?>

                                            <td class="text-center">
                                                <button type='button' onclick='return confirmDelete(`<?php echo $id ?>`)' class='btn btn-danger btn-sm'>Hapus</button>
                                            </td>
                                        </tr>
                                        <?php
                                       }
                                   } else {
                                       $nip = '';
                                       $nama = '';
                                       $jam_masuk = '';
                                       $jam_keluar = '';
                                       $status = '';
                                       $keterangan = '-';
                                       $fotoAbsen = '';
                                       $latlong = '';
                                       $tombolHapus = '';

                                       if ($nama_hari == 'Minggu') {
                                           $keterangan = 'Libur Akhir Pekan';
                                       } else {
                                           $keterangan = '';
                                       }

                                       ?>
                                    <tr class="<?php echo $bg_color; ?>">
                                        <td>
                                            <?php echo $nip; ?>
                                        </td>
                                        <td>
                                            <?php echo $nama; ?>
                                        </td>
                                        <td>
                                            <?php echo $jam_masuk; ?>
                                        </td>
                                        <td>
                                            <?php echo $jam_keluar; ?>
                                        </td>
                                        <td>
                                            <?php echo $status; ?>
                                        </td>
                                        <td>
                                            <?php echo $keterangan; ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                   }
                                   ?>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function () {
                                $('.table').DataTable();
                            });
                        </script>
                        <script>
                            function showFoto(fotoAbsen,nama) {
                                swal.fire({
                                    title: 'Foto Absen: ' + nama,
                                    imageUrl: '../hasil_absen/' + fotoAbsen,
                                    imageWidth: 300,
                                });
                            }
                            function confirmDelete(id) {
                                // Menggunakan SweetAlert untuk konfirmasi penghapusan
                                Swal.fire({
                                    title: "Konfirmasi",
                                    text: "Apakah Anda yakin ingin menghapus absensi ini?",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: "Ya, Hapus",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    // Jika pengguna mengklik "Ya, Hapus", redirect ke URL hapus
                                    if (result.isConfirmed) {
                                        window.location.href = "?op=hapus&id=" + id;
                                    }
                                });

                                // Mengembalikan false untuk mencegah tindakan default dari tautan
                                return false;
                            }
                            var mymap;
                            $('#mapModal').on('hidden.bs.modal', function () {
                                mymap.remove();
                            });
                            function showModalMap(latlong,nama) {
                                $('#mapModal').modal('show');
                                $('#mapModal').on('shown.bs.modal', function () {
                                    var mapContainer = document.getElementById('mapid');

                                    // Hapus peta yang ada jika sudah diinisialisasi sebelumnya
                                    if (mapContainer && mapContainer._leaflet_id) {
                                        mapContainer._leaflet_id = null;
                                    }

                                    // Split latlong menjadi latitude dan longitude
                                    var coordinates = latlong.split(',');
                                    var latitude = parseFloat(coordinates[0]);
                                    var longitude = parseFloat(coordinates[1]);

                                    // Gunakan nilai latitude dan longitude dalam setView()
                                    mymap = L.map('mapid').setView([latitude, longitude], 13);

                                    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                                        attribution: 'Map data &copy; <a href="https://www.mapbox.com/">Mapbox</a>',
                                        maxZoom: 18,
                                        tileSize: 512,
                                        zoomOffset: -1,
                                        id: 'mapbox/streets-v11',
                                        accessToken: 'pk.eyJ1IjoiYWRpZ3VuYXdhbnhkIiwiYSI6ImNrcWp2Yjg2cDA0ZjAydnJ1YjN0aDNnbm4ifQ.htvHCgSgN0UuV8hhZBfBfQ'
                                    }).addTo(mymap);

                                    L.marker([latitude, longitude]).addTo(mymap);
                                    L.circle([latitude, longitude], 550, {
                                        color: 'red',
                                        fillColor: '#f03',
                                        fillOpacity: 0.5
                                    }).addTo(mymap).bindPopup("" + nama).openPopup();
                                    var popup = L.popup();
                                    function onMapClick(e) {
                                        popup
                                            .setLatLng(e.latlng)
                                            .setContent("" + e.latlng.toString())
                                            .openOn(mymap);
                                    }
                                    mymap.on('click', onMapClick);

                                    //var marker = L.marker([latitude, longitude]).addTo(mymap);
                                });
                            }
                        </script>
                    </div>
                    <?php
                } else {
                    ?>
                    <!-- Tampilkan pesan jika tidak ada absensi -->
                    <div class="alert alert-info">Tidak/belum ada absensi untuk
                        <?php echo $nama_hari . ', ' . date('d', strtotime($tanggal)) . ' ' . $nama_bulan . ' ' . date('Y', strtotime($tanggal)) ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>


</body>

</html>