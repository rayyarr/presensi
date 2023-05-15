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
        <!-- untuk memasukkan data -->
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
                            <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $nip ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>" required>
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
                                <input class="form-check-input" type="radio" name="jabatan_id" value="1" id="jabatan_guru"
                                    <?php if ($jabatan == "1")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_guru">Guru</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jabatan_id" value="2"
                                        id="jabatan_tu" <?php if ($jabatan == "2")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_tu">Tata Usaha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jabatan_id" value="3"
                                        id="jabatan_pdh" <?php if ($jabatan == "3")
                                        echo "checked" ?>>
                                    <label class="form-check-label" for="jabatan_pdh">PDH</label>
                                </div>
                                <!--<label class="label" for="select4">Jabatan</label>
                                <select class="form-control custom-select" name="position_id">';
                                      $query="SELECT * from position order by position_name ASC";
                                      $result = $connection->query($query);
                                      while($rowa = $result->fetch_assoc()) { 
                                      if($rowa['position_id'] == $row_user['position_id']){
                                        echo'<option value="'.$rowa['position_id'].'" selected>'.$rowa['position_name'].'</option>';
                                      }else{
                                        echo'<option value="'.$rowa['position_id'].'">'.$rowa['position_name'].'</option>';
                                      }
                                      }echo'
                                </select>-->
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

            <!-- untuk mengeluarkan data -->
            <div class="card mb-5">
                <div class="card-header text-white bg-secondary">
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
                                    <a href="crud.php?op=edit&id=<?php echo $id ?>"><button type="button"
                                            class="btn btn-warning">Edit</button></a>
                                    <a href="crud.php?op=delete&id=<?php echo $id ?>"
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