<?php
session_start();

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
if ($op == 'hapus') {
    $jabatan_id = $_GET['id'];
    $sql1 = "DELETE FROM jabatan WHERE jabatan_id = '$jabatan_id'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
if ($op == 'tambah') {
    $jabatan_nama = $_POST['tambahNama'];

    $sql_cek_nama = "SELECT * FROM jabatan WHERE jabatan_nama='$jabatan_nama'";
    $q_cek_nama = mysqli_query($conn, $sql_cek_nama);
    $jml_cek_nama = mysqli_num_rows($q_cek_nama);

    if ($jml_cek_nama > 0) {
        $error = "Nama jabatan sudah ada!";
    } else {
        if ($jabatan_nama) {
            $sql1 = "INSERT INTO jabatan (jabatan_nama) VALUES ('$jabatan_nama')";

            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Berhasil menambah data jabatan: $jabatan_nama";
            } else {
                $error = "Gagal menambah data jabatan";
            }
        } else {
            $error = "Silakan masukkan semua data";
        }
    }
}
if ($op == 'simpan') {
    $jabatan_id = $_POST['editId'];
    $jabatan_nama = $_POST['editNama'];

    if ($jabatan_id && $jabatan_nama) {
        $sql1 = "UPDATE jabatan SET jabatan_nama='$jabatan_nama' WHERE jabatan_id = '$jabatan_id'";
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
    <title>Data Jabatan</title>
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
    <!-- Edit Data Jabatan -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=simpan" method="POST">
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama Jabatan</label>
                            <input type="text" class="form-control" id="editNama" name="editNama" required>
                        </div>

                        <input type="hidden" id="editId" name="editId">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Data Jabatan -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=tambah" method="POST">
                        <div class="mb-3">
                            <label for="tambahNama" class="form-label">Nama Jabatan</label>
                            <input type="text" class="form-control" id="tambahNama" name="tambahNama" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto">

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex align-items-center">Data Jabatan</div>
                <a data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <button type="button" class="btn btn-primary">Tambah Jabatan</button>
                </a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
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
                            $jabatan_nama = $r2['jabatan_nama'];
                            ?>
                            <tr>
                                <td scope="row">
                                    <?php echo $jabatan_id ?>
                                </td>
                                <td scope="row">
                                    <?php echo $jabatan_nama ?>
                                </td>
                                <td scope="row">
                                    <a id="iniEditModal" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $jabatan_id ?>" data-jabatan="<?php echo $jabatan_nama ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <button type='button' onclick='return confirmDelete(`<?php echo $jabatan_id ?>`)'
                                        class='btn btn-danger'>Hapus</button>
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

                    function confirmDelete(jabatan_id) {
                        // Menggunakan SweetAlert untuk konfirmasi penghapusan
                        Swal.fire({
                            title: "Konfirmasi",
                            html: "Apakah Anda yakin ingin menghapus?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Hapus",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            // Jika pengguna mengklik "Ya, Hapus", redirect ke URL hapus
                            if (result.isConfirmed) {
                                window.location.href = "?op=hapus&id=" + jabatan_id;
                            }
                        });

                        // Mengembalikan false untuk mencegah tindakan default dari tautan
                        return false;
                    }

                    var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
                        keyboard: false
                    });

                    // Menangkap event klik tombol "Edit" pada setiap baris tabel
                    var editButtons = document.querySelectorAll('a[id="iniEditModal"]');
                    editButtons.forEach(function (button) {
                        button.addEventListener('click', function () {
                            // Mendapatkan data dari atribut data-* pada tombol
                            var id = this.getAttribute('data-id');
                            var jabatan = this.getAttribute('data-jabatan');

                            // Mengisi nilai input field di dalam modal dengan data yang diperoleh
                            document.getElementById('editId').value = id;
                            document.getElementById('editNama').value = jabatan;

                            // Menampilkan modal edit
                            editModal.show();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</body>

</html>