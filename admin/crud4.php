<?php
include_once 'main-admin.php';

$jabatan_id = "";
$jabatan_nama = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $jabatan_id = $_GET['id'];
    $sql1 = "delete from jabatan where jabatan_id = '$jabatan_id'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $jabatan_id = $_GET['id'];
    $sqldef = "select * from jabatan where jabatan_id = '$jabatan_id'";
    $q1 = mysqli_query($conn, $sqldef);
    $r1 = mysqli_fetch_array($q1);
    $jabatan_id = $r1['jabatan_id'];
    $jabatan_nama = $r1['jabatan_nama'];

    if ($jabatan_id == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $jabatan_id = $_POST['jabatan_id'];
    $jabatan_nama = $_POST['jabatan_nama'];

    if ($jabatan_id && $jabatan_nama) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update jabatan set jabatan_nama='$jabatan_nama' where jabatan_id = '$jabatan_id'";
            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into jabatan(jabatan_id,jabatan_nama) values ('$jabatan_id','$jabatan_nama')";
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
                Edit jabatan
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
                        <label for="jabatan_id" class="col-sm-2 col-form-label">ID Jabatan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="jabatan_id" name="jabatan_id" value="<?php echo $jabatan_id ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jabatan_nama" class="col-sm-2 col-form-label">Nama Jabatan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="jabatan_nama" name="jabatan_nama" value="<?php echo $jabatan_nama ?>" required>
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
                                <th scope="col">ID Jabatan</th>
                                <th scope="col">Nama Jabatan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $sql2 = "SELECT * FROM jabatan";
                                    $q2 = mysqli_query($conn, $sql2);
                                    $urut = 1;
                                    while ($r2 = mysqli_fetch_array($q2)) {
                                        $jabatan_id = $r2['jabatan_id'];
                                        $jabatan_nama  = $r2['jabatan_nama'];
                                        ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $jabatan_id ?>
                                </td>
                                <td scope="row">
                                    <?php echo $jabatan_nama ?>
                                </td>
                                <td scope="row">
                                    <a href="crud4.php?op=edit&id=<?php echo $jabatan_id ?>"><button type="button"
                                            class="btn btn-warning">Edit</button></a>
                                    <a href="crud4.php?op=delete&id=<?php echo $jabatan_id ?>"
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