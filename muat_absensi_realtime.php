<?php
include_once 'cfgdb.php';

// set default timezone
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');

$query = "SELECT absen.id_absen, absen.nip, pengguna.nama, pengguna.foto_profil, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan, absen.foto_absen, absen.latlong 
      FROM absen 
      JOIN status_absen ON absen.id_status = status_absen.id_status
      JOIN pengguna ON absen.nip = pengguna.nip
      WHERE tanggal_absen = '$tanggal'
      ORDER BY absen.jam_masuk DESC";
$result = mysqli_query($conn, $query);

// Mendapatkan waktu saat ini
$current_time = time();

// Memproses setiap baris hasil query
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

        // Mendapatkan waktu absensi
        $absensi_time = strtotime($data_absen['jam_masuk']);

        // Menghitung selisih waktu antara waktu absensi dan waktu saat ini
        $time_diff = $current_time - $absensi_time;

        // Konversi selisih waktu menjadi menit
        $minutes_diff = round($time_diff / 60);

        // Tampilkan data absensi dalam format HTML
        if ($data_absen['foto_profil'] == NULL) {
            // Jika tidak ada data gambar yang tersimpan di database, gunakan gambar default
            $nama_file = "default.png";
        } else {
            // Jika ada data gambar yang tersimpan di database, tampilkan gambar tersebut
            $nama_file = $data_absen['foto_profil'];
            $path_to_file = "foto_profil/" . $nama_file;
            if (!file_exists($path_to_file)) {
                // Jika file tidak ada, gunakan gambar default
                $nama_file = "default.png";
            }
        }

        echo '<tr>
            <td width="60px">
              <div class="imgBx"><img src="foto_profil/' . $nama_file . '" alt=""></div>
            </td>
            <td>
              <h4>' . $nama . ' <br> <span>Telah melakukan absen <b>' . $status . '</b> ' . formatWaktu($minutes_diff) . '</b></span></h4>
            </td>
          </tr>';
    }
}

// Fungsi untuk mengatur format waktu
function formatWaktu($minutes_diff) {
    if ($minutes_diff < 1) {
      return "baru saja";
    } elseif ($minutes_diff === 60) {
      return "1 jam yang lalu";
    } elseif ($minutes_diff > 60) {
      $hours = floor($minutes_diff / 60);
      $remaining_minutes = $minutes_diff % 60;
      return $hours . " jam " . $remaining_minutes . " menit yang lalu";
    } else {
      return $minutes_diff . " menit yang lalu";
    }
  }
  

// Tutup koneksi database
mysqli_close($conn);
?>