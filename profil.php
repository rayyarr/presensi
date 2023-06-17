<?php
session_start();
include_once 'cfgall.php';

$nip = "";
$password = "";
$nama = "";
$jabatan_id = "";
$guru = "";
$sukses = "";
$error = "";

$sqldef = "SELECT * FROM pengguna WHERE nip = ?";
$stmt1 = $conn->prepare($sqldef);
$stmt1->bind_param("s", $userid);
$stmt1->execute();
$result1 = $stmt1->get_result();
$r1 = $result1->fetch_assoc();

$nip = $r1['nip'];
//$password = md5($r1['password']);
$nama = $r1['nama'];
$jabatan_id = $r1['jabatan_id'];
$guru = $r1['guru'];

if ($nip == '') {
    $error = "Data tidak ditemukan";
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $jabatan_id = $_POST['jabatan_id'];
    $guru = $_POST['guru'];

    if ($nama && $jabatan_id && $guru) {
        $sql1 = "UPDATE pengguna SET nama=?, jabatan_id=?, guru=? WHERE nip = ?";
        $stmt2 = $conn->prepare($sql1);
        $stmt2->bind_param("ssss", $nama, $jabatan_id, $guru, $userid);
        if ($stmt2->execute()) {
            $sukses = "Data berhasil diupdate";
        } else {
            $error = "Data gagal diupdate";
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}

//PROSES UPLOAD/UPDATE GAMBAR
if (isset($_FILES['image'])) {
    $image = $_FILES['image']['name'];
    $tmp_image = $_FILES['image']['tmp_name'];
    $image_ext = explode('.', $image);
    $file_ext = strtolower(end($image_ext));

    //membuat array untuk ekstensi file yang diperbolehkan
    $allowed_ext = array('jpg', 'jpeg', 'png');

    //memeriksa apakah ekstensi file diizinkan
    if (in_array($file_ext, $allowed_ext)) {
        //memeriksa ukuran file
        $file_size = $_FILES['image']['size']; // ukuran file dalam bytes
        $max_file_size = 500 * 1024; // 500 KB

        if ($file_size <= $max_file_size) {
            //memeriksa apakah pengguna sudah memiliki data gambar yang tersimpan di database
            $sql_check = "SELECT foto_profil FROM pengguna WHERE nip=?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $userid);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // Menghapus file yang lama berawalan $userid
                $file_directory = "foto_profil/";
                $files = glob($file_directory . $userid . "_*");
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }

            //membuat nama file baru dengan nilai nip dan uniqid
            $new_filename = $userid . "_" . uniqid() . "." . $file_ext;

            move_uploaded_file($tmp_image, "foto_profil/" . $new_filename);

            $sql = "UPDATE pengguna SET foto_profil=? WHERE nip=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $new_filename, $userid);
            $stmt->execute();

            $script = "<script>
                Swal.fire(
                    'Gambar berhasil diunggah dan diperbarui',
                    'Silakan refresh halaman ini',
                    'success'
                )
            </script>";
            echo $script;
        } else {
            $script = "<script>
                Swal.fire(
                    'Gagal Upload Profil!',
                    'Ukuran melebihi batas yang diizinkan (500KB)',
                    'error'
                );
            </script>";
            echo $script;
        }
    } else {
        $script = "<script>
            Swal.fire(
                'Gagal Upload Profil!',
                'Hanya file dengan ekstensi jpg, jpeg, atau png yang diizinkan',
                'error'
            );
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

    $query = "SELECT password FROM pengguna WHERE nip=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $oldpassword_db = $row['password'];

        if ($oldpassword == $oldpassword_db) {
            if ($newpassword == $confirmpassword) {
                $query = "UPDATE pengguna SET password=? WHERE nip=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $newpassword, $username);
                $stmt->execute();
                $script = "<script>
            Swal.fire(
                'Berhasil!',
                'Password berhasil diubah.',
                'success'
            );</script>";
                echo $script;
            } else {
                $script = "<script>
            Swal.fire(
                'Gagal!',
                'Password baru tidak cocok dengan konfirmasi password.',
                'error'
            );</script>";
                echo $script;
            }
        } else {
            $script = "<script>
            Swal.fire(
                'Gagal!',
                'Password lama salah.',
                'error'
            );</script>";
            echo $script;
        }
    } else {
        $script = "<script>
            Swal.fire(
                'Gagal!',
                'Tidak dapat menemukan pengguna dengan username tersebut.',
                'error'
            );</script>";
        echo $script;
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
                        <p class="card-text"><small class="text-muted">Ubah foto profil - <i>Abaikan jika tak
                                    perlu</i></small></p>

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
                        <script>
                            Swal.fire({
                                title: "<?php echo $error ?>",
                                icon: "error",
                            })
                        </script>
                        <?php
                    }
                    if ($sukses) {
                        ?>
                        <script>
                            Swal.fire({
                                title: "<?php echo $sukses ?>",
                                icon: "success",
                            })
                        </script>
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
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="<?php echo $nama ?>" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jabatan_id" value="1"
                                        id="jabatan_guru" <?php if ($jabatan_id == "1")
                                            echo "checked" ?>>
                                        <label class="form-check-label" for="jabatan_guru">Guru</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jabatan_id" value="2"
                                            id="jabatan_tu" <?php if ($jabatan_id == "2")
                                            echo "checked" ?>>
                                        <label class="form-check-label" for="jabatan_tu">Tata Usaha</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jabatan_id" value="3"
                                            id="jabatan_pdh" <?php if ($jabatan_id == "3")
                                            echo "checked" ?>>
                                        <label class="form-check-label" for="jabatan_pdh">PDH</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="guru" class="col-sm-2 col-form-label">Penempatan</label>
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
                                <span class="input-group-text" onclick="togglePb()"><i id="eye-icon"
                                        class="bi bi-eye-slash"></i></span>
                            </div>
                            <div>
                                <label class="form-label">Konfirmasi Password</label>
                            </div>
                            <div class="input-group mb-3">
                                <input class="form-control" type="password" name="confirmpassword"
                                    id="confirm-password-input" required>
                                <span class="input-group-text" onclick="toggleCp()"><i id="eye-icon2"
                                        class="bi bi-eye-slash"></i></span>
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