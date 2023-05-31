<?php

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
if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "delete from pengguna where id = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id = $_GET['id'];
    $sqldef = "select * from pengguna where id = '$id'";
    $sql1 = "SELECT pengguna.id where id = '$id', pengguna.nip, pengguna.nama, jabatan.jabatan_nama, pengguna.guru
                                             FROM pengguna
                                             INNER JOIN jabatan ON pengguna.jabatan_id where id = '$id' = jabatan.jabatan_id where id = '$id'
                                             ORDER BY pengguna.id where id = '$id' DESC";
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
}
if (isset($_POST['simpan'])) { //untuk create
    $nip = $_POST['nip'];
    $password = md5($_POST['password']);
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan_id'];
    $guru = $_POST['guru'];

    if ($nip && $password && $nama && $jabatan && $guru) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update pengguna set nip = '$nip',password='$password',nama='$nama',jabatan_id = '$jabatan',guru='$guru' where id = '$id'";
            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into pengguna(nip,nama,password,jabatan_id,guru) values ('$nip','$nama','$password','$jabatan','$guru')";
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

if ($op == 'simpan') {
    $nip = $_POST['editNip'];
    $nama = $_POST['editNama'];
    $password = md5($_POST['editPassword']);
    $jabatan = $_POST['editJabatan'];
    $guru = $_POST['editGuru'];

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
    <div class="mx-auto">
        <div class="card">
            <div class="card-header">
                Pendaftaran
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
                        <label for="nip" class="col-sm-2 col-form-label">NIP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $nip ?>"
                                required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password"
                                value="<?php echo $password ?>" required>
                        </div>
                    </div>
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
                                        id="jabatan_bph" <?php if ($jabatan == "3")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_bph">BPH</label>
                                </div>
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
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Tempatkan formulir edit di sini -->
                            <form action="crud.php?op=simpan" method="POST">
                                <!-- Tambahkan input field yang diperlukan untuk mengedit data -->
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
                                    <input type="password" class="form-control" id="editPassword" name="editPassword">
                                </div>
                                <div class="mb-3">
                                    <label for="editJabatan" class="form-label">Jabatan</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="editJabatan" value="1"
                                            id="editJabatan_guru" required>
                                        <label class="form-check-label" for="editJabatan_guru">Guru</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="editJabatan" value="2"
                                            id="editJabatan_tu" required>
                                        <label class="form-check-label" for="editJabatan_tu">Tata Usaha</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="editJabatan" value="3"
                                            id="editJabatan_bph" required>
                                        <label class="form-check-label" for="editJabatan_bph">BPH</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editGuru" class="form-label">Guru</label>
                                    <select class="form-control" id="editGuru" name="editGuru" required>
                                        <option value="">- Pilih guru -</option>
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

            <!-- untuk mengeluarkan data -->
            <div class="card mb-5">
                <div class="card-header text-white bg-secondary">
                    Data Pengguna
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">NIP</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Guru</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $sql2 = "SELECT pengguna.id, pengguna.nip, pengguna.nama, pengguna.password, jabatan.jabatan_nama, pengguna.guru
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
                                    <!--<a href="crud.php?op=edit&id=<?php echo $id ?>"><button type="button"
                                            class="btn btn-warning">Edit</button></a>-->
                                    <a data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $id ?>" data-nip="<?php echo $nip ?>"
                                        data-nama="<?php echo $nama ?>" data-password="<?php echo $password ?>" data-jabatan="<?php echo $jabatan ?>"
                                        data-guru="<?php echo $guru ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <a href="crud.php?op=delete&id=<?php echo $id ?>"
                                        onclick="return confirm('Yakin mau hapus data?')"><button type="button"
                                            class="btn btn-danger">Hapus</button></a>
                                </td>
                            </tr>
                            <?php
                                    }
                                    ?>
                    </tbody>

                </table>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('.table').DataTable();
            });
        </script>
        <script>
            var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
                keyboard: false
            });

            // Menangkap event klik tombol "Edit" pada setiap baris tabel
            var editButtons = document.querySelectorAll('a[data-bs-toggle="modal"]');
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
                    // Memilih radio button berdasarkan nilai jabatan
                    var jabatanRadioButtons = document.getElementsByName('editJabatan');
                    jabatanRadioButtons.forEach(function (radioButton) {
                        if (jabatan === "Guru" && radioButton.value === "1") {
                            radioButton.checked = true;
                        } else if (jabatan === "Tata Usaha" && radioButton.value === "2") {
                            radioButton.checked = true;
                        } else if (jabatan === "BPH" && radioButton.value === "3") {
                            radioButton.checked = true;
                        }
                    });

                    var editGuruSelect = document.getElementById('editGuru');
                    // Loop melalui setiap opsi dan membandingkannya dengan nilai guru
                    for (var i = 0; i < editGuruSelect.options.length; i++) {
                        if (editGuruSelect.options[i].value === guru) {
                            // Jika nilai opsi sama dengan guru, atur opsi tersebut sebagai terpilih
                            editGuruSelect.options[i].selected = true;
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