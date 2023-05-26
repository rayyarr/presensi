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

$today = date("Y-m-d");
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
    }

    .card-container {
      display: flex;
    }

    .card {
      margin-right: 10px;
      /* Untuk memberikan jarak antara kartu-kartu */
    }
  </style>
</head>

<body>
  <div class="mx-auto">

    <!-- ======================= Cards ================== -->
    <div class="cardBox">
      <div class="card">
        <div>
          <div class="numbers">1,504</div>
          <div class="cardName">Daily Views</div>
        </div>

        <div class="iconBx">
          <ion-icon name="eye-outline"></ion-icon>
        </div>
      </div>

      <div class="card">
        <div>
          <div class="numbers">80</div>
          <div class="cardName">Sales</div>
        </div>

        <div class="iconBx">
          <ion-icon name="cart-outline"></ion-icon>
        </div>
      </div>

      <div class="card">
        <div>
          <div class="numbers">284</div>
          <div class="cardName">Comments</div>
        </div>

        <div class="iconBx">
          <ion-icon name="chatbubbles-outline"></ion-icon>
        </div>
      </div>

      <div class="card">
        <div>
          <div class="numbers">$7,842</div>
          <div class="cardName">Earning</div>
        </div>

        <div class="iconBx">
          <ion-icon name="cash-outline"></ion-icon>
        </div>
      </div>
    </div>

    <div class="card-container">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title mb-3">
            <?php echo $jumlah_pengguna; ?> Pengguna
          </h5>
          <a href="crud" class="btn btn-primary">Lihat</a>
        </div>
      </div>

      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title mb-3">
            <?php echo $total_absen; ?> Absen Hari Ini
          </h5>
          <a href="rekap_harian" class="btn btn-primary">Lihat</a>
        </div>
      </div>

      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title mb-3">
            <?php echo $jumlah_jabatan; ?> Jabatan
          </h5>
          <a href="crud4" class="btn btn-primary">Lihat</a>
        </div>
      </div>
    </div>

    <div class="card" style="width: 17rem;">
      <div class="card-body">
        <h5 class="card-title mb-3">
          <?php echo $jumlah_status; ?> Status Absen
        </h5>
        <a href="crud3" class="btn btn-primary">Lihat</a>
      </div>
    </div>
  </div>

  </div>
</body>

</html>