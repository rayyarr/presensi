<?php
session_start();

include_once 'main-admin.php';

$id = "";
$jarak = "";
$latitude = "";
$longitude = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "delete from pengaturan where id = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id = $_GET['id'];
    $sqldef = "select * from pengaturan where id = '$id'";
    $q1 = mysqli_query($conn, $sqldef);
    $r1 = mysqli_fetch_array($q1);
    $id = $r1['id'];
    $jarak = $r1['jarak'];
    $latitude = $r1['latitude'];
    $longitude = $r1['longitude'];

    if ($id == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $id = $_POST['id'];
    $jarak = $_POST['jarak'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    if ($id && $jarak && $latitude && $longitude) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update pengaturan set jarak='$jarak', latitude='$latitude', longitude='$longitude' where id = '$id'";
            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into pengaturan(id,jarak,latitude,longitude) values ('$id','$jarak','$latitude','$longitude')";
            $q1 = mysqli_query($conn, $sql1);
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
                Edit pengaturan
            </div>
            <div class="card-body">
            <?php
                if ($error) {
                    ?>
                    <script>Swal.fire("<?php echo $error ?>");</script>
                    <?php
                    
                }
                ?>
                <?php
                if ($sukses) {
                    ?>
                    <script>Swal.fire("<?php echo $sukses ?>");</script>
                    <?php
                    
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="id" class="col-sm-2 col-form-label">ID</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id" name="id" value="<?php echo $id ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jarak" class="col-sm-2 col-form-label">Jarak</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="jarak" name="jarak" value="<?php echo $jarak ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo $latitude ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo $longitude ?>" required>
                        </div>
                    </div>
                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- untuk mengeluarkan data -->
            <div class="card">
                <div class="card-header">
                    Data Pengguna
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID </th>
                                <th scope="col">Jarak</th>
                                <th scope="col">Latitude</th>
                                <th scope="col">Longitude</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $sql2 = "SELECT * FROM pengaturan";
                                    $q2 = mysqli_query($conn, $sql2);
                                    $urut = 1;
                                    while ($r2 = mysqli_fetch_array($q2)) {
                                        $id = $r2['id'];
                                        $jarak  = $r2['jarak'];
                                        $latitude  = $r2['latitude'];
                                        $longitude  = $r2['longitude'];
                                        ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $id ?>
                                </td>
                                <td scope="row">
                                    <?php echo $jarak ?>
                                </td>
                                <td scope="row">
                                    <?php echo $latitude ?>
                                </td>
                                <td scope="row">
                                    <?php echo $longitude ?>
                                </td>
                                <td scope="row">
                                    <a href="?op=edit&id=<?php echo $id ?>"><button type="button"
                                            class="btn btn-warning">Edit</button></a>
                                    <a href="?op=delete&id=<?php echo $id ?>"
                                        onclick="return confirm('Yakin mau delete data?')"><button type="button"
                                            class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                            <?php
                                    }
                                    ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</body>
</html>