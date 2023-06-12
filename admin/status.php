<?php
session_start();

include_once 'main-admin.php';

$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'simpan') {
    $id_status = $_POST['editId'];
    $nama_status = $_POST['editNama'];

    if ($id_status && $nama_status) {
        $sql1 = "update status_absen set nama_status='$nama_status' where id_status = '$id_status'";
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
    <title>Data Status Absen</title>
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

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header">
                Data Status Absen
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Keterangan Status</th>
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
                            $nama_status = $r2['nama_status'];
                            ?>
                            <tr>
                                <td scope="row">
                                    <?php echo $id_status ?>
                                </td>
                                <td scope="row">
                                    <?php echo $nama_status ?>
                                </td>
                                <td scope="row">
                                    <?php if($id_status != 1){
                                        ?><a data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $id_status ?>" data-nama="<?php echo $nama_status ?>">
                                            <button type="button" class="btn btn-warning">Edit</button>
                                        </a>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-warning" disabled>Edit</button>
                                    <?php } ?>
                                    <!--<a href="?op=delete&id=<?php echo $id_status ?>"
                                        onclick="return confirm('Yakin mau delete data?')"><button type="button"
                                            class="btn btn-danger">Delete</button></a>-->
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>

                </table>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var tables = document.querySelectorAll('.table');
                        tables.forEach(function (table) {
                            new DataTable(table);
                        });
                    });

                    // Menangkap event klik tombol "Edit" pada setiap baris tabel
                    var editButtons = document.querySelectorAll('a[data-bs-toggle="modal"]');
                    editButtons.forEach(function (button) {
                        button.addEventListener('click', function () {
                            // Mendapatkan data dari atribut data-* pada tombol
                            var id_status = this.getAttribute('data-id');
                            var nama_status = this.getAttribute('data-nama');

                            // Mengisi nilai input field di dalam modal dengan data yang diperoleh
                            document.getElementById('editId').value = id_status;
                            document.getElementById('editNama').value = nama_status;

                            // Menampilkan modal edit
                            editModal.show();
                        });
                    });
                </script>
            </div>
        </div>

        <!-- Modal Edit Status -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Status Absen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="?op=simpan" method="POST">
                            <div class="mb-3">
                                <label for="editNama" class="form-label">Keterangan Status</label>
                                <input type="text" class="form-control" id="editNama" name="editNama" required>
                            </div>

                            <input type="hidden" id="editId" name="editId">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>