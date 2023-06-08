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
if ($op == 'show') {
    $nip = $_GET['nip'];
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

        <!-- untuk mengeluarkan data -->
        <div class="card mb-3">
            <div class="card-header">
                Data Pengguna
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Guru</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT pengguna.id, pengguna.nip, pengguna.nama, jabatan.jabatan_nama, pengguna.guru
                                             FROM pengguna
                                             INNER JOIN jabatan ON pengguna.jabatan_id = jabatan.jabatan_id
                                             ORDER BY pengguna.id DESC";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $nip = $r2['nip'];
                            $nama = $r2['nama'];
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
                                    <a href="hasil_rekap?nip=<?php echo $nip ?>"><button type="button"
                                            class="btn btn-warning">Lihat</button></a>
                                    <a class="btn btn-success" href="ekspor_rekap?nip=<?php echo $nip; ?>">Ekspor</a>
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
</body>

</html>