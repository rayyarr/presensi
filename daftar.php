<?php
// Aini

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "presensi";

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
$error_message = '';

if (isset($_POST['daftar'])) { //untuk create
  $nip = $_POST['nip'];
  $password = md5($_POST['password']);
  $nama = $_POST['nama'];
  $jabatan = $_POST['jabatan_id'];
  $guru = $_POST['guru'];

  if ($nip && $password && $nama && $jabatan && $guru) {
      if ($op == 'edit') { //untuk update
          $sql1 = "update pengguna set nip = '$nip',password='$password',nama='$nama',jabatan_id = '$jabatan',guru='$guru' where id = '$id'";
          $q1 = mysqli_query($koneksi, $sql1);
          if ($q1) {
              $sukses = "Data berhasil diupdate";
          } else {
              $error = "Data gagal diupdate";
          }
      } else { //untuk insert
          $sql1 = "insert into pengguna(nip,nama,password,jabatan_id,guru) values ('$nip','$nama','$password','$jabatan','$guru')";
          $q1 = mysqli_query($koneksi, $sql1);
          if ($q1) {
              $sukses = "Berhasil memasukkan data baru";
          } else {
              $error = "Gagal memasukkan data";
          }
      }
  } else {
      $error = "Silakan masukkan semua data";
  }
}
?>
<?php
                                    $sql2 = "SELECT pengguna.id, pengguna.nip, pengguna.nama, jabatan.jabatan_nama, pengguna.guru
                                             FROM pengguna
                                             INNER JOIN jabatan ON pengguna.jabatan_id = jabatan.jabatan_id
                                             ORDER BY pengguna.id DESC";
                                    $q2 = mysqli_query($koneksi, $sql2);
                                    $urut = 1;
                                    while ($r2 = mysqli_fetch_array($q2)) {
                                        $id = $r2['id'];
                                        $nip = $r2['nip'];
                                        $nama = $r2['nama'];
                                        $jabatan = $r2['jabatan_nama'];
                                        $guru = $r2['guru'];
                                    }
                                        ?>

<!DOCTYPE html>
<html>

<head>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />

  <title>SMP dan SMA Pesantren MKGR Kertasemaya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>

<body>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #000;
    }

    .bg-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('./img/fullpage.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      z-index: -1;
      /* agar elemen ini berada di bawah konten */
      animation-name: zoom;
      animation-duration: 1s;
      animation-timing-function: ease-in-out;
      animation-fill-mode: forwards;
      opacity: 0;
    }

    @keyframes zoom {
      from {
        transform: scale(1);
      }

      to {
        transform: scale(1.2);
        opacity: 0.5;
      }
    }

    @keyframes tara {
      from {
        margin-top: 0;
        opacity: 0;
      }

      to {
        margin-top: 70px;
        opacity: 1;
      }
    }

    a {
      text-decoration: none;
    }

    p {
      margin-top: 15px
    }

    .background {
      height: 520px;
      position: absolute;
      transform: translate(-50%, -50%);
      top: 50%;
      left: 50%
    }

    form h3,
    label {
      font-weight: 500
    }

    input,
    label {
      display: block
    }

    *,
    :after,
    :before {
      padding: 0;
      margin: 0;
      box-sizing: border-box
    }

    .social,
    label {
      margin-top: 30px
    }

    /*body{background: rgb(81,172,255);background: linear-gradient(90deg, rgba(81,172,255,1) 0%, rgba(156,208,255,1) 50%, rgba(250,255,255,1) 100%);}*/
    .background {
      max-width: 330px
    }

    .background .shape {
      height: 200px;
      width: 200px;
      position: absolute;
      border-radius: 50%
    }

    .shape:first-child {
      background: linear-gradient(#1845ad, #23a2f6);
      left: -20px;
      top: -50px
    }

    .shape:last-child {
      background: linear-gradient(#1845ad, #23a2f6);
      right: -20px;
      bottom: -50px
    }

    form {
      margin-top: 5vh;
      background-color: rgba(0, 0, 0, 0.8);
      max-width: 300px;
      margin-left: auto;
      margin-right: auto;
      border-radius: 10px;
      /*box-shadow:0 0 40px rgba(8,7,16,.6);*/
      padding: 50px 35px;
      transition: all 0.3s ease;
    }

    form * {
      font-family: Poppins, sans-serif;
      color: #fff;
      letter-spacing: .5px;
      outline: 0;
      border: none
    }

    .social div,
    input {
      border-radius: 3px
    }

    form h3 {
      font-size: 32px;
      line-height: 42px;
    }

    form h3,
    form p {
      text-align: center
    }

    @media only screen and (min-width: 500px) {
      form {
        max-width: 350px;
        margin-top: 0;
        animation-name: tara;
        animation-duration: .7s;
        animation-timing-function: ease-in-out;
        animation-fill-mode: forwards;
      }
    }

    label {
      font-size: 16px
    }

    input {
      height: 50px;
      width: 100%;
      background-color: rgb(255 255 255 / 8%);
      padding: 0 10px;
      margin-top: 8px;
      font-size: 14px;
      font-weight: 300
    }

    ::placeholder {
      color: #e5e5e5
    }

    button {
      margin-top: 30px;
      margin-bottom: 25px;
      width: 100%;
      background-color: #243763;
      color: #fff;
      padding: 15px 0;
      font-size: 18px;
      font-weight: 600;
      border-radius: 5px;
      cursor: pointer
    }

    .social {
      display: flex
    }

    .social div {
      background: rgba(255, 255, 255, .27);
      width: 150px;
      padding: 5px 10px 10px 5px;
      color: #eaf0fb;
      text-align: center
    }

    .social div:hover {
      background-color: rgba(255, 255, 255, .47)
    }

    .social .fb {
      margin-left: 25px
    }

    .social i {
      margin-right: 4px
    }

    .back-button {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  width: 40px;
  height: 40px;
  padding: 10px;
  border-radius: 50%;
  background-color: #ffffff17;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease-in-out;
}

.back-button:hover {
  opacity:.7;
}

.line {
  stroke-width: 2;
  stroke: #fff;
  fill: none;
}

  </style>
  <!--<div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    -->
  <div class="bg-image"></div>
  <form method="POST" action="" id="form-login">
    <a href="https://rayyarr.github.io/presensi/" class="back-button">
      <svg class='line' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
        <g
          transform='translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) translate(5.000000, 8.500000)'>
          <path d='M14,0 C14,0 9.856,7 7,7 C4.145,7 0,0 0,0'></path>
        </g>
      </svg>
    </a>
    <h3>Daftar</h3>
    <p>Masukkan Data Anda</p>
    <?php if ($error_message): ?>
      <div class="alert alert-danger text-center" role="alert">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>

    <label for="nip">NIP</label>
    <input type="text" placeholder="Nomor Induk Pegawai" id="nip" name="nip">

    <label for="password">Password</label>
    <input type="password" placeholder="Kata Sandi" id="password" name="password">

    <label for="nama">Nama</label>
    <input type="text" placeholder="Nama Lengkap" id="nama" name="nama">

    <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                                <div class="form-check">
                                <input class="form-check-input" type="radio" name="jabatan_id" value="1" id="jabatan_guru"
                                    <?php if ($jabatan == "1")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_guru">Guru</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jabatan_id" value="2"
                                        id="jabatan_tu" <?php if ($jabatan == "2")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_tu">Tata Usaha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jabatan_id" value="3"
                                        id="jabatan_pdh" <?php if ($jabatan == "3")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_pdh">PDH</label>
                                </div>
                                <label class="label" for="select4">Jabatan</label>
                                <select class="form-control custom-select" name="position_id">';
                                      $query="SELECT * from position order by position_name ASC";
                                      $result = $connection->query($query);
                                      while($rowa = $result->fetch_assoc()) { 
                                      if($rowa['position_id'] == $row_user['position_id']){
                                        echo'<option value="'.$rowa['position_id'].'" selected>'.$rowa['position_name'].'</option>';
                                      }else{
                                        echo'<option value="'.$rowa['position_id'].'">'.$rowa['position_name'].'</option>';
                                      }
                                      }echo'
                                </select>
                            </div>
                        </div>
                        <label for="guru" class="col-sm-2 col-form-label">Guru</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="guru" id="guru">
                                    <option value="">- Pilih guru -</option>
                                    <option value="SMP" <?php if ($guru == "SMP")
                                        echo "selected" ?>>SMP</option>
                                    <option value="SMA" <?php if ($guru == "SMA")
                                        echo "selected" ?>>SMA</option>
                                    <option value="SMP SMA" <?php if ($guru == "SMP SMA")
                                        echo "selected" ?>>SMP SMA</option>
                                </select>
                            </div>

    <button type="submit" name="daftar">Daftar</button>

    <!--<div class="social">
          <div class="go"><i class="fab fa-google"></i>  Google</div>
          <div class="fb"><i class="fab fa-facebook"></i>  Facebook</div>
        </div>-->
  </form>
</body>

</html>