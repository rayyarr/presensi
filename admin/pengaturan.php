<?php
session_start();

include_once 'main-admin.php';

$id = "";
$jarak = "";
$latitude = "";
$longitude = "";
$sukses = "";
$error = "";

$sqldef = "select * from pengaturan";
$q1 = mysqli_query($conn, $sqldef);
$r1 = mysqli_fetch_array($q1);
$batas_telat = $r1['batas_telat'];
$jarak = $r1['jarak'];
$latitude = $r1['latitude'];
$longitude = $r1['longitude'];

if (isset($_POST['simpan'])) {
    $batas_telat = $_POST['batas_telat'];
    $jarak = $_POST['jarak'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    if ($batas_telat && $jarak && $latitude && $longitude) {
        $sql1 = "update pengaturan set batas_telat='$batas_telat', jarak='$jarak', latitude='$latitude', longitude='$longitude' where id_pengaturan=1";
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card mb-3">
            <div class="card-header">
                Pengaturan Absensi
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="batas_telat" class="col-sm-2 col-form-label">Telat Maks. (menit)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="batas_telat" name="batas_telat"
                                value="<?php echo $batas_telat ?>">
                            <div class="form-text">Maksimal keterlambatan pengguna dalam melakukan absen masuk (menit).
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jarak" class="col-sm-2 col-form-label">Jarak Maks. (kilometer)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="jarak" name="jarak"
                                value="<?php echo $jarak ?>">
                            <div class="form-text">Jarak maksimal pengguna dari sekolah (km).</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="latitude" name="latitude"
                                value="<?php echo $latitude ?>" required>
                            <div class="form-text">Latitude sekolah. Anda bisa mengambilnya dari <i>maps.google.com</i>.
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="longitude" name="longitude"
                                value="<?php echo $longitude ?>" required>
                            <div class="form-text">Longitude sekolah. Anda bisa mengambilnya dari
                                <i>maps.google.com</i>.</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Perubahan" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>

</html>