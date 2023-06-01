<?php
include_once 'main-admin.php';

$id_jadwal = "";
$waktu_masuk = "";
$waktu_pulang = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id_jadwal = $_GET['id'];
    $sql1 = "delete from jadwal where id_jadwal= '$id_jadwal'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id_jadwal = $_GET['id'];
    $sqldef = "select * from jadwal where id_jadwal = '$id_jadwal'";
    $q1 = mysqli_query($conn, $sqldef);
    $r1 = mysqli_fetch_array($q1);
    $id_jadwal = $r1['id_jadwal'];
    $waktu_masuk = $r1['waktu_masuk'];
    $waktu_pulang = $r1['waktu_pulang'];

    if ($id_jadwal == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $id_jadwal = $_POST['id_jadwal'];
    $waktu_masuk = $_POST['waktu_masuk'];
    $waktu_pulang = $_POST['waktu_pulang'];

    if ($id_jadwal && $waktu_masuk && $waktu_pulang) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update jadwal set waktu_masuk='$waktu_masuk',waktu_pulang='$waktu_pulang' where id_jadwal = '$id_jadwal'";
            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into jadwal(id_jadwal,waktu_masuk,waktu_pulang) values ('$id_jadwal','$waktu_masuk','$waktu_pulang')";
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
                Edit Jadwal
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
                        <label for="id_jadwal" class="col-sm-2 col-form-label">ID Jadwal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_jadwal" name="id_jadwal"
                                value="<?php echo $id_jadwal ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="waktu_masuk" class="col-sm-2 col-form-label">Waktu Masuk</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control" id="waktu_masuk" name="waktu_masuk"
                                value="<?php echo $waktu_masuk ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="waktu_pulang" class="col-sm-2 col-form-label">Waktu Pulang</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control" id="waktu_pulang" name="waktu_pulang"
                                value="<?php echo $waktu_pulang ?>" required>
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
                            <th scope="col">ID Jadwal</th>
                            <th scope="col">Waktu Masuk</th>
                            <th scope="col">Waktu Pulang</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM jadwal";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id_jadwal = $r2['id_jadwal'];
                            $waktu_masuk = $r2['waktu_masuk'];
                            $waktu_pulang = $r2['waktu_pulang'];
                            ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $id_jadwal ?>
                                </td>
                                <td scope="row">
                                    <?php echo $waktu_masuk ?>
                                </td>
                                <td scope="row">
                                    <?php echo $waktu_pulang ?>
                                </td>
                                <td scope="row">
                                    <a href="crud2.php?op=edit&id=<?php echo $id_jadwal ?>"><button type="button"
                                            class="btn btn-warning">Edit</button></a>
                                    <a href="crud2.php?op=delete&id=<?php echo $id_jadwal ?>"
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