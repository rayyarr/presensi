<?php
session_start();

include_once 'main-admin.php';

$nip = "";
$password = "";
$nama = "";
$jabatan = "";
$guru = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'hapus') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM pengguna WHERE id = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus pengguna";
    } else {
        $error = "Gagal melakukan hapus pengguna";
    }
}
if ($op == 'tambah') {
    $nip = $_POST['tambahNip'];
    $nama = $_POST['tambahNama'];
    $password = md5($_POST['tambahPassword']);
    $jabatan = $_POST['tambahJabatan'];
    $guru = $_POST['tambahPenempatan'];

    $sql_cek_nip = "SELECT * FROM pengguna WHERE nip='$nip'";
    $q_cek_nip = mysqli_query($conn, $sql_cek_nip);
    $jml_cek_nip = mysqli_num_rows($q_cek_nip);

    if ($jml_cek_nip > 0) {
        $error = "NIP sudah terdaftar!";
    } else {
        if ($nip && $password && $nama && $jabatan && $guru) {
            $sql1 = "INSERT INTO pengguna (nip,nama,password,jabatan_id,guru) VALUES ('$nip','$nama','$password','$jabatan','$guru')";

            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Berhasil menambah data pengguna: $nama";
            } else {
                $error = "Gagal menambah data pengguna";
            }
        } else {
            $error = "Silakan masukkan semua data";
        }
    }
}

if ($op == 'simpan') {
    $nip = $_POST['editNip'];
    $nama = $_POST['editNama'];
    $password = md5($_POST['editPassword']);
    $jabatan = $_POST['editJabatan'];
    $guru = $_POST['editPenempatan'];

    if ($password && $nama && $jabatan && $guru) {
        $sql1 = "update pengguna set nama='$nama', password='$password', jabatan_id = '$jabatan', guru='$guru' where nip = '$nip'";

        $q1 = mysqli_query($conn, $sql1);
        if ($q1) {
            $sukses = "Data berhasil diupdate untuk $nama";
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
    <title>Data Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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
    <!-- Edit Data Pengguna -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=simpan" method="POST">
                        <div class="mb-3 d-none">
                            <label for="editNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="editNip" name="editNip">
                        </div>
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="editNama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="editPassword" required>
                        </div>
                        <div class="mb-3">
                            <?php
                            $sqljabatan = "SELECT * FROM jabatan";
                            $result = mysqli_query($conn, $sqljabatan);

                            $options = '';
                            while ($row = mysqli_fetch_assoc($result)) {
                                $options .= '<option value="' . $row['jabatan_id'] . '">' . $row['jabatan_nama'] . '</option>';
                            }
                            ?>
                            <label for="editJabatan" class="form-label">Jabatan</label>
                            <div>
                                <select class="form-select" name="editJabatan" id="editJabatan" required>
                                    <option value="">- Pilih Jabatan -</option>
                                    <?php echo $options ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editPenempatan" class="form-label">Penempatan</label>
                            <select class="form-select" id="editPenempatan" name="editPenempatan" required>
                                <option value="">- Pilih Penempatan -</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="SMP SMA">SMP SMA</option>
                            </select>
                        </div>

                        <input type="hidden" id="editId" name="editId">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Data Pengguna -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=tambah" method="POST">
                        <div class="mb-3">
                            <label for="tambahNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="tambahNip" name="tambahNip" required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="tambahNama" name="tambahNama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="tambahPassword" name="tambahPassword"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahJabatan" class="form-label">Jabatan</label>
                            <div>
                                <select class="form-select" name="tambahJabatan" id="tambahJabatan" required>
                                    <option value="">- Pilih Jabatan -</option>
                                    <?php echo $options ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tambahPenempatan" class="form-label">Penempatan</label>
                            <select class="form-select" id="tambahPenempatan" name="tambahPenempatan" required>
                                <option value="">- Pilih Penempatan -</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="SMP SMA">SMP SMA</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto mb-5">
        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex align-items-center">Data Pengguna</div>
                <a data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <button type="button" class="btn btn-primary">Tambah Pengguna</button>
                </a>
            </div>

            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Penempatan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT pengguna.id, pengguna.nip, pengguna.nama, pengguna.password, jabatan.jabatan_id, jabatan.jabatan_nama, pengguna.guru
                                             FROM pengguna
                                             INNER JOIN jabatan ON pengguna.jabatan_id = jabatan.jabatan_id
                                             ORDER BY pengguna.nama ASC";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $nip = $r2['nip'];
                            $nama = $r2['nama'];
                            $password = md5($r2['password']);
                            $id_jabatan = $r2['jabatan_id'];
                            $jabatan = $r2['jabatan_nama'];
                            $guru = $r2['guru'];

                            ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $nip ?>
                                </td>
                                <td scope="row">
                                    <?php echo $nama ?>
                                </td>
                                <td scope="row">
                                    <?php echo $jabatan ?>
                                </td>
                                <td scope="row">
                                    <?php echo $guru ?>
                                </td>
                                <td scope="row">
                                    <a id="iniEditModal" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $id ?>" data-nip="<?php echo $nip ?>"
                                        data-nama="<?php echo $nama ?>" data-password="<?php echo $password ?>"
                                        data-jabatan="<?php echo $id_jabatan ?>" data-guru="<?php echo $guru ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <button type='button' onclick='return confirmDelete(`<?php echo $id ?>`,`<?php echo $nama ?>`)'
                                        class='btn btn-danger'>Hapus</button>
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


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tables = document.querySelectorAll('.table');
            tables.forEach(function (table) {
                new DataTable(table);
            });
        });

        function confirmDelete(id,nama) {
            // Menggunakan SweetAlert untuk konfirmasi penghapusan
            Swal.fire({
                title: "Konfirmasi",
                html: "Apakah Anda yakin ingin menghapus <b>`" + nama + "`</b>?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                // Jika pengguna mengklik "Ya, Hapus", redirect ke URL hapus
                if (result.isConfirmed) {
                    window.location.href = "?op=hapus&id=" + id;
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
                var nip = this.getAttribute('data-nip');
                var nama = this.getAttribute('data-nama');
                var password = this.getAttribute('data-password');
                var jabatan = this.getAttribute('data-jabatan');
                var guru = this.getAttribute('data-guru');

                // Mengisi nilai input field di dalam modal dengan data yang diperoleh
                document.getElementById('editId').value = id;
                document.getElementById('editNip').value = nip;
                document.getElementById('editNama').value = nama;
                document.getElementById('editPassword').value = password;

                var editJabatanSelect = document.getElementById('editJabatan');
                // Loop melalui setiap opsi dan membandingkannya dengan nilai guru
                for (var i = 0; i < editJabatanSelect.options.length; i++) {
                    if (editJabatanSelect.options[i].value === jabatan) {
                        // Jika nilai opsi sama dengan guru, atur opsi tersebut sebagai terpilih
                        editJabatanSelect.options[i].selected = true;
                        break;
                    }
                }

                var editPenempatanSelect = document.getElementById('editPenempatan');
                // Loop melalui setiap opsi dan membandingkannya dengan nilai guru
                for (var i = 0; i < editPenempatanSelect.options.length; i++) {
                    if (editPenempatanSelect.options[i].value === guru) {
                        // Jika nilai opsi sama dengan guru, atur opsi tersebut sebagai terpilih
                        editPenempatanSelect.options[i].selected = true;
                        break;
                    }
                }

                // Menampilkan modal edit
                editModal.show();
            });
        });
    </script>
</body>

</html>