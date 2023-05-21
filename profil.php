<?php
session_start(); // Mulai session
include_once 'sw-header.php';

$nip = "";
$password = "";
$nama = "";
$jabatan = "";
$guru = "";
$sukses = "";
$error = "";

//if ($op == 'edit') {
//$id = $_GET['id'];
$sqldef = "select * from pengguna where nip = '$userid'";
$q1 = mysqli_query($conn, $sqldef);
$r1 = mysqli_fetch_array($q1);
$nip = $r1['nip'];
$password = md5($r1['password']);
$nama = $r1['nama'];
$jabatan = $r1['jabatan_id'];
$guru = $r1['guru'];

if ($nip == '') {
    $error = "Data tidak ditemukan";
}
//}

if (isset($_POST['simpan'])) { //untuk update
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan_id'];
    $guru = $_POST['guru'];

    if ($nip && $password && $nama && $jabatan && $guru) {
        $sql1 = "update pengguna set nama='$nama',jabatan_id = '$jabatan',guru='$guru' where nip = '$userid'";
        $q1 = mysqli_query($conn, $sql1);
        if ($q1) {
            $sukses = "Data berhasil diupdate";
        } else {
            $error = "Data gagal diupdate";
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}

//PROSES UPLOAD/UPDATE GAMBAR
//memeriksa apakah ada file yang diunggah
if (isset($_FILES['image'])) {
    $image = $_FILES['image']['name'];
    $tmp_image = $_FILES['image']['tmp_name'];
    $image_ext = explode('.', $image);
    $file_ext = strtolower(end($image_ext));

    //membuat array untuk ekstensi file yang diperbolehkan
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

    //memeriksa apakah ekstensi file diizinkan
    if (in_array($file_ext, $allowed_ext)) {
        //membuat nama file baru dengan gabungan nilai nip dan nama file
        $new_filename = $userid . "_" . $image;

        //memeriksa apakah pengguna sudah memiliki data gambar yang tersimpan di database
        $sql_check = "SELECT foto_profil FROM pengguna WHERE nip='$userid'";
        $result_check = mysqli_query($conn, $sql_check);
        if (mysqli_num_rows($result_check) > 0) {
            //menghapus file yang lama
            $row_check = mysqli_fetch_assoc($result_check);
            $old_filename = $row_check['foto_profil'];
            if ($old_filename != NULL) {
            unlink("foto_profil/" . $old_filename);
            //memperbarui data gambar dengan data gambar yang baru
            $sql_update = "UPDATE pengguna SET foto_profil='$new_filename' WHERE nip='$userid'";
            mysqli_query($conn, $sql_update);

            //memindahkan file ke direktori tujuan dengan nama file baru
            move_uploaded_file($tmp_image, "foto_profil/" . $new_filename);

            $script = "<script>
				Swal.fire(
					'Gambar berhasil diunggah dan diperbarui',
					'Silakan refresh halaman ini',
					'success',
				  )
            </script>";
            echo $script;
            } else {
            //memindahkan file ke direktori tujuan dengan nama file baru
            move_uploaded_file($tmp_image, "foto_profil/" . $new_filename);

            //memasukkan data gambar ke database dengan nama file baru
            $sql = "UPDATE pengguna SET foto_profil='$new_filename' WHERE nip='$userid'";
            mysqli_query($conn, $sql);

            $script = "<script>
				Swal.fire(
					'Gambar berhasil diunggah',
					'Silakan refresh halaman ini',
					'success',
                );
            </script>";
            echo $script;
            }
        } //else {}
    } else {
        $script = "<script>
              alert('Hanya file dengan ekstensi jpg, jpeg, png, atau gif yang diizinkan');
            </script>";
        echo $script;
    }
}
/////////////////////////////////////////////////////////////////

// untuk ganti password

if (isset($_POST['submit'])) {
    $username = $_SESSION['nip'];
    $oldpassword = md5($_POST['oldpassword']);
    $newpassword = md5($_POST['newpassword']);
    $confirmpassword = md5($_POST['confirmpassword']);

    $query = "SELECT password FROM pengguna WHERE nip='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $oldpassword_db = $row['password'];

        if ($oldpassword == $oldpassword_db) {
            if ($newpassword == $confirmpassword) {
                $query = "UPDATE pengguna SET password='$newpassword' WHERE nip='$username'";
                mysqli_query($conn, $query);
                echo "Password berhasil diubah";
            } else {
                echo "Password baru tidak cocok dengan konfirmasi password";
            }
        } else {
            echo "Password lama salah";
        }
    } else {
        echo "Tidak dapat menemukan pengguna dengan username tersebut";
    }
}
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
<div class="kolomkanan">
		<div class="mx-auto">

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
            <div class="col">
                <div class="card-body">
                    <!--<h5 class="card-title">D3 Teknik Informatika 1C</h5>-->
                    <p class="card-text"><small class="text-muted">Ubah foto profil - <i>Abaikan jika tak perlu</i></small></p>

                    <form method="post" enctype="multipart/form-data">
                        <div class="input-group mb-3">
                            <input type="file" name="image" class="form-control" id="inputGroupFile02">
                            <input type="submit" value="Upload" class="input-group-text" for="inputGroupFile02">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- untuk memasukkan data -->
        <div class="card" style="margin-top:50px;margin-bottom:50px">
            <div class="card-header" style="background:none">
                Form Edit Profil
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                    <?php
                }
                ?>
                <?php
                if ($sukses) {
                    ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                    <?php
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nip" class="col-sm-2 col-form-label">NIP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $nip ?>"
                                disabled>
                        </div>
                    </div>
                    <!--<div class="mb-3 row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password"
                                value="<?php echo $password ?>" required>
                        </div>
                    </div>-->
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>"
                                required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <!--<label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo $jabatan ?>" required>
                        </div>-->
                        <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jabatan_id" value="1"
                                    id="jabatan_guru" <?php if ($jabatan == "1")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_guru">Guru</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jabatan_id" value="2" id="jabatan_tu"
                                    <?php if ($jabatan == "2")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_tu">Tata Usaha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jabatan_id" value="3"
                                        id="jabatan_pdh" <?php if ($jabatan == "3")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_pdh">PDH</label>
                                </div>
                                <!--<label class="label" for="select4">Jabatan</label>
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
                                </select>-->
                            </div>
                        </div>
                        <div class="mb-3 row">
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
                        </div>
                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- untuk ganti password -->
            <div class="card" style="margin-bottom:50px">
                <div class="card-header" style="background:none">
                    Form Ganti Password - <i>Abaikan jika tak perlu</i>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Password Lama</label>
                            <input class="form-control" type="password" name="oldpassword" required>
                        </div>
                        <div>
                            <label class="form-label">Password Baru</label>
                        </div>
                        <div class="input-group mb-3">
                            <input class="form-control" type="password" name="newpassword" id="password-input" required>
                            <span class="input-group-text" onclick="togglePb()"><i id="eye-icon" class="bi bi-eye-slash"></i></span>
                        </div>
                        <div>
                            <label class="form-label">Konfirmasi Password</label>
                        </div>
                        <div class="input-group mb-3">
                        <input class="form-control" type="password" name="confirmpassword" id="confirm-password-input" required>
                            <span class="input-group-text" onclick="toggleCp()"><i id="eye-icon2" class="bi bi-eye-slash"></i></span>
                        </div>
                        <div class="col-12">
                            <input type="submit" name="submit" value="Simpan" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>

        </div>
        </div>


        </div>
        <script>
            function togglePb() {
                var passwordInput = document.getElementById("password-input");
                var eyeIcon = document.getElementById("eye-icon");
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.classList.remove("bi-eye-slash");
                    eyeIcon.classList.add("bi-eye");
                } else {
                    passwordInput.type = "password";
                    eyeIcon.classList.remove("bi-eye");
                    eyeIcon.classList.add("bi-eye-slash");
                }
            }
            function toggleCp() {
                var conpasswordInput = document.getElementById("confirm-password-input");
                var eyeIcon = document.getElementById("eye-icon2");
                if (conpasswordInput.type === "password") {
                    conpasswordInput.type = "text";
                    eyeIcon.classList.remove("bi-eye-slash");
                    eyeIcon.classList.add("bi-eye");
                } else {
                    conpasswordInput.type = "password";
                    eyeIcon.classList.remove("bi-eye");
                    eyeIcon.classList.add("bi-eye-slash");
                }
            }
        </script>
    </body>

    </html>