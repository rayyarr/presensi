<?php
include_once 'main-admin.php';

$id_status = "";
$nama_status = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id_status = $_GET['id'];
    $sql1 = "delete from status_absen where id_status = '$id_status'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id_status = $_GET['id'];
    $sqldef = "select * from status_absen where id_status = '$id_status'";
    $q1 = mysqli_query($conn, $sqldef);
    $r1 = mysqli_fetch_array($q1);
    $id_status = $r1['id_status'];
    $nama_status = $r1['nama_status'];

    if ($id_status == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $id_status = $_POST['id_status'];
    $nama_status = $_POST['nama_status'];

    if ($id_status && $nama_status) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update status_absen set nama_status='$nama_status' where id_status = '$id_status'";
            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into status_absen(id_status,nama_status) values ('$id_status','$nama_status')";
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
        <div class="card">
            <div class="card-header">
                Edit status_absen
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
                        <label for="id_status" class="col-sm-2 col-form-label">ID Status</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_status" name="id_status" value="<?php echo $id_status ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_status" class="col-sm-2 col-form-label">Nama Status</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_status" name="nama_status" value="<?php echo $nama_status ?>" required>
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
                <div class="card-header text-white bg-secondary">
                    Data Pengguna
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID Status</th>
                                <th scope="col">Nama Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $sql2 = "SELECT * FROM status_absen";
                                    $q2 = mysqli_query($conn, $sql2);
                                    $urut = 1;
                                    while ($r2 = mysqli_fetch_array($q2)) {
                                        $id_status = $r2['id_status'];
                                        $nama_status  = $r2['nama_status'];
                                        ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $id_status ?>
                                </td>
                                <td scope="row">
                                    <?php echo $nama_status ?>
                                </td>
                                <td scope="row">
                                    <a href="crud3.php?op=edit&id=<?php echo $id_status ?>"><button type="button"
                                            class="btn btn-warning">Edit</button></a>
                                    <a href="crud3.php?op=delete&id=<?php echo $id_status ?>"
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