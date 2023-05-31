<?php

include_once 'main-admin.php';

// Query untuk menghitung jumlah pengguna
$sql = "SELECT COUNT(*) AS total_pengguna FROM pengguna";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $jumlah_pengguna = $row["total_pengguna"];
} else {
  $jumlah_pengguna = 0;
}

$sql1 = "SELECT COUNT(*) AS total_absen FROM absen WHERE DATE(tanggal_absen) = '$today'";
$result = $conn->query($sql1);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $total_absen = $row["total_absen"];
} else {
  $total_absen = 0;
}

$sql = "SELECT COUNT(*) AS total_jabatan FROM jabatan";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $jumlah_jabatan = $row["total_jabatan"];
} else {
  $jumlah_jabatan = 0;
}

$sql = "SELECT COUNT(*) AS total_status FROM status_absen";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $jumlah_status = $row["total_status"];
} else {
  $jumlah_status = 0;
}

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

/////////////

$query = "SELECT absen.id_absen, absen.nip, pengguna.nama, pengguna.foto_profil, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan, absen.foto_absen, absen.latlong 
      FROM absen 
      JOIN status_absen ON absen.id_status = status_absen.id_status
      JOIN pengguna ON absen.nip = pengguna.nip
      WHERE tanggal_absen = '$tanggal'
      ORDER BY absen.id_absen DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <style>
    .mx-auto {
      max-width: 800px
    }

    .card {
      margin-top: 10px;
      border-radius: 30px;
    }

    .card-container {
      display: flex;
    }

    /* ======================= Cards ====================== */
    .cardBox {
      position: relative;
      width: 100%;
      /*padding: 20px;*/
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 20px;
    }

    .cardBox a {
      text-decoration: none;
    }

    .cardBox .card {
      position: relative;
      background: #fff;
      padding: 20px;
      border: 0;
      border-radius: 20px;
      display: flex;
      flex-direction: row;
      grid-gap: 10px;
      margin-right: 0;
      justify-content: space-between;
      cursor: pointer;
      box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
    }

    .cardBox .card .numbers {
      position: relative;
      font-weight: 500;
      font-size: 2.5rem;
      color: #2a2185;
    }

    .cardBox .card .cardName {
      color: #5f5f5f;
      font-size: 1.1rem;
      margin-top: 5px;
    }

    .cardBox .card .iconBx {
      position: absolute;
      right: 20px;
      font-size: 2.3rem;
      color: #5f5f5f;
    }

    .cardBox .card:hover {
      background: #2a2185;
    }

    .cardBox .card:hover .numbers,
    .cardBox .card:hover .cardName,
    .cardBox .card:hover .iconBx {
      color: #fff;
    }

    .recentCustomers {
      position: relative;
      display: grid;
      /*min-height: 500px;*/
      padding: 20px;
      background: var(--white);
      box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
      border-radius: 20px;
    }

    .recentCustomers .imgBx {
      position: relative;
      width: 40px;
      height: 40px;
      border-radius: 50px;
      overflow: hidden;
    }

    .recentCustomers .imgBx img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .recentCustomers table tr td {
      padding: 12px 10px;
    }

    .recentCustomers table tr td h4 {
      font-size: 16px;
      font-weight: 500;
      line-height: 1.2rem;
    }

    .recentCustomers table tr td h4 span {
      font-size: 14px;
      color: var(--black2);
    }

    .recentCustomers table tr:hover {
      background: var(--blue);
      color: var(--white);
    }

    .recentCustomers table tr:hover td h4 span {
      color: var(--white);
    }
  </style>
</head>

<body>
  <div class="mx-auto">

    <div class="cardBox mb-5">
      <a href="crud">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $jumlah_pengguna; ?>
            </div>
            <div class="cardName">Pengguna</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-people"></i>
          </div>
        </div>
      </a>

      <a href="rekap_harian">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $total_absen; ?>
            </div>
            <div class="cardName">Absen Hari Ini</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-activity"></i>
          </div>
        </div>
      </a>

      <a href="crud4">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $jumlah_jabatan; ?>
            </div>
            <div class="cardName">Jabatan</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-person-badge"></i>
          </div>
        </div>
      </a>

      <a href="crud3">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $jumlah_status; ?>
            </div>
            <div class="cardName">Status Absen</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-list-check"></i>
          </div>
        </div>
      </a>
    </div>

    <!--<div class="card mb-5 p-3" style="margin-top:30px">
      <div class="leftP">
        <div class="profileIcon leftC flex solo">
          <label class="a flexIns fc" for="forProfile">
            <span class="avatar flex center">
              <img class="iniprofil" src="foto_profil/<?php echo $nama_file; ?>" alt="<?php echo $nama_file; ?>">
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
  -->

    <!-- ================= New Customers ================ -->
    <div class="recentCustomers mb-5">
      <div class="cardHeader">
        <h3>Absensi Terakhir</h3>
      </div>

      <table id="riwayat_absensi">
      </table>
      <script>
        function loadAbsensi() {
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              var data = xhr.responseText;
              document.getElementById("riwayat_absensi").innerHTML = data;
            }
          };
          xhr.open("GET", "muat_absensi_realtime.php", true);
          xhr.send();
        }

        document.addEventListener("DOMContentLoaded", function () {
          setInterval(loadAbsensi, 3000); // Memuat data absensi setiap 5 detik (5000 ms)
          loadAbsensi(); // Memuat data absensi saat halaman pertama kali dimuat
        });
      </script>
    </div>

    <!-- TESTING -->
    <div class="card p-4 mb-5" style="border:0">
      <?php
      $query = "SELECT COUNT(*) AS total_absensi FROM absen WHERE tanggal_absen = '$tanggal'";
      $result = mysqli_query($conn, $query);
      $data = mysqli_fetch_assoc($result);
      $totalAbsensi = $data['total_absensi'];

      if ($totalAbsensi > 0) {
        ?>
        <!-- Tampilkan tabel absensi -->
        <div class="table-responsive">
          <table class="table">
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
    ORDER BY absen.id_absen DESC";
              $result = mysqli_query($conn, $query);

              if (mysqli_num_rows($result) > 0) {
                while ($data_absen = mysqli_fetch_assoc($result)) {
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
                        <img class="ftabsen" src="hasil_absen/<?php echo $fotoAbsen; ?>" alt="Foto Absen" id="<?php echo $i ?>"
                          width="50px" height="50px" onclick="showFoto('<?php echo $fotoAbsen; ?>', '<?php echo $nama; ?>')">
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
            function showFoto(fotoAbsen, nama) {
              swal.fire({
                title: 'Foto Absen: ' + nama,
                imageUrl: 'hasil_absen/' + fotoAbsen,
                imageWidth: 300,
              });
            }
            var mymap;
            $('#mapModal').on('hidden.bs.modal', function () {
              mymap.remove();
            });
            function showModalMap(latlong, nama) {
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
        <div class="alert alert-info">Tidak ada absensi untuk
          <?php echo $nama_hari . ', ' . date('d', strtotime($tanggal)) . ' ' . $nama_bulan . ' ' . date('Y', strtotime($tanggal)) ?>
        </div>
        <?php
      }
      ?>
    </div>

  </div>
</body>

</html>