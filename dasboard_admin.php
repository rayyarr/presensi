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

    /* ======================= Cards ====================== */
    .cardBox {
      position: relative;
      width: 100%;
      /*padding: 20px;*/
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 10px;
    }

    .cardBox a {
      text-decoration: none;
    }

    .cardBox .card {
      position: relative;
      background: #fff;
      padding: 20px;
      border-radius: 20px;
      display: flex;
      flex-direction:row;
      grid-gap:10px;
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
      right:20px;
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
  </style>
</head>

<body>
  <div class="mx-auto">

    <div class="cardBox">
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
            <i class="bi bi-people"></i>
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
            <i class="bi bi-people"></i>
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
            <i class="bi bi-people"></i>
          </div>
        </div>
      </a>
    </div>

  </div>
</body>

</html>