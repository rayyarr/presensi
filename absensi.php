<?php

$nip = "";
$password = "";
$nama = "";
$jabatan = "";
$guru = "";
$sukses = "";
$error = "";

session_start(); // Mulai session

// Jika user belum login, alihkan ke halaman login
if (!isset($_SESSION['nip'])) {
    header("Location: login");
    exit();
}

include_once 'sw-header.php';

//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (isset($_POST['simpan'])) {
    $tanggal_absen = date('Y-m-d');
    $jam = date('H:i:s');
    $id_status = $_POST['id_status'];
    $keterangan = $_POST['keterangan'];
    $ku = "SELECT * FROM absen WHERE nip='$userid' AND tanggal_absen='$tanggal_absen'";
    $hsl = mysqli_query($conn, $ku);
    if (mysqli_num_rows($hsl) > 0) {
        // Jika data sudah ada, berikan pesan error dan hentikan proses
        echo '<script>
        popupJudul = "Gagal!";
        popupText = "Kamu sudah absen hari ini!";
        popupIcon = "error";
        </script>';
    } else {
        $sqlabs = "INSERT INTO absen SET nip = '$userid',tanggal_absen='$tanggal_absen', id_status='$id_status', tgl_keluar='$tanggal_absen', keterangan='$keterangan'";
        $hslabs = mysqli_query($conn, $sqlabs);
        echo '<script>
        popupJudul = "Berhasil!";
        popupText = "Kamu telah melakukan izin kehadiran.";
        popupIcon = "success";
        </script>';
    }
    echo '<script>
    swal.fire({
        title: "" + popupJudul,
        text: "" + popupText,
        icon: "" + popupIcon,
    }).then((result) => {
        setTimeout(function () {
            window.location.href = "login";
         }, 400);
    })
    </script>';
}

// Eksekusi query dan mengambil isi jadwal masuk
$sqlW = "SELECT waktu_masuk, waktu_pulang FROM jadwal WHERE id_jadwal = 1";
$hasilW = mysqli_query($conn, $sqlW);

// Cek apakah query berhasil dijalankan
if (mysqli_num_rows($hasilW) > 0) {
    // Looping untuk membaca nilai waktu_masuk dari setiap baris data
    while ($row = mysqli_fetch_assoc($hasilW)) {
        $jam_masuk = $row["waktu_masuk"];
        $jam_pulang = $row["waktu_pulang"];
    }
} else {
    $jam_masuk = date('H:i:s');
    $jam_pulang = date('H:i:s');
}
$jam_masuk = date('H:i', strtotime($jam_masuk)); // mengubah format waktu
$jam_masuk = $jam_masuk . " WIB"; // menambahkan "WIB" pada akhir string

$jam_pulang = date('H:i', strtotime($jam_pulang)); // mengubah format waktu
$jam_pulang = $jam_pulang . " WIB"; // menambahkan "WIB" pada akhir string

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | SMP SMA MKGR Kertasemaya</title>
    <style>
        .mx-auto {
            max-width: 800px
        }

        .card {
            margin-top: 10px;
        }

        .fade {
            background-color: rgb(0 0 0 / 60%);
        }

        .button-text {
            line-height: 1.3em
        }

        .button-alt {
            display: flex;
            justify-content: center;
            align-items: center
        }

        .button-alt svg {
            width: 13px;
            height: 13px;
            margin-right: 5px
        }
    </style>
</head>

<body>
    <div class="kolomkanan">
        <div class="mx-auto">

            <div class="card mb-5 p-3">
                <div class="leftP">
                    <div class="profileIcon leftC flex solo">
                        <label class="a flexIns fc" for="forProfile">
                            <span class="avatar flex center">
                                <img class="iniprofil" src="foto_profil/<?php echo $nama_file; ?>"
                                    alt="<?php echo $nama_file; ?>">
                            </span>
                            <span class="n flex column">
                                <span class="fontS">
                                    <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        // tampilkan nama
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <h4>
                                                <?= $row['nama']; ?>
                                            </h4>
                                        </span>
                                        <p class="opacity" style="margin-bottom:0">
                                            NIP
                                            <?= $row['nip']; ?> -
                                            <?= $hasiljoin['jabatan_nama']; ?> -
                                            <?= $row['guru']; ?>
                                        </p>
                                        <?php
                                        }
                                    }
                                    ?>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card p-3 mb-5 text-center">
                <!--<div class="alert alert-info" role="alert">
                Sistem ini menggunakan akses lokasi agar dapat bekerja.
            </div>-->
                <div class="mb-3">
                    <span class="d-block"><i class="bi-geo-alt text-success"></i> <a class="text-success"
                            href="https://goo.gl/maps/zwucsvHDvmTCVtYUA" target="_blank">SMP SMA MKGR</a>: <b
                            id="my-location">belum terdeteksi</b></span>
                </div>
                <div class="mb-3">
                    <span class="d-block">
                        Lokasi Anda: <b id="your-location">belum terdeteksi</b></span>
                    <span class="d-block">Lat: <b id="your-latitude">belum terdeteksi</b> - | - Long: <b
                            id="your-longitude">belum terdeteksi</b></span>
                </div>
                <div class="mb-3">
                    <div class="d-block">
                        <span>Jarak Anda: <b id="our-distance">belum terdeteksi</b></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-block">
                        <span>Waktu saat ini: <b id="jam">belum terdeteksi</b> <b>WIB</b></span>
                    </div>
                </div>
                <!--<button id="allow-location-button" type="button" class="btn btn-primary">
                Izinkan akses lokasi saya
            </button>-->
                <div class="wallet-footer" style="flex-wrap:wrap;justify-content:space-between">
                    <div class="item">
                        <a href="absen_masuk.php">
                            <div class="button-container btn-outline-biru">
                                <div class="button-icon">
                                    <i class="bi bi-calendar2-check"></i>
                                </div>
                                <div class="button-text">
                                    <div class="button-title">ABSEN MASUK</div>
                                    <div class="button-alt"><svg class='line' xmlns='http://www.w3.org/2000/svg'
                                            viewBox='0 0 24 24'>
                                            <path
                                                d='M184.7647,181.67261l-2.6583-2.65825a2,2,0,0,1-.5858-1.41423v-3.75937'
                                                transform='translate(-169.5206 -166.42857)'></path>
                                            <rect class='cls-3' x='2' y='2' width='20' height='20' rx='5'></rect>
                                        </svg>
                                        <?php echo $jam_masuk; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <a href="absen_keluar.php">
                            <div class="button-container btn-outline-biru">
                                <div class="button-icon">
                                    <i class="bi bi-calendar2-minus"></i>
                                </div>
                                <div class="button-text">
                                    <div class="button-title">ABSEN KELUAR</div>
                                    <div class="button-alt"><svg class='line' xmlns='http://www.w3.org/2000/svg'
                                            viewBox='0 0 24 24'>
                                            <path
                                                d='M184.7647,181.67261l-2.6583-2.65825a2,2,0,0,1-.5858-1.41423v-3.75937'
                                                transform='translate(-169.5206 -166.42857)'></path>
                                            <rect class='cls-3' x='2' y='2' width='20' height='20' rx='5'></rect>
                                        </svg>
                                        <?php echo $jam_pulang; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <label onclick="showModal()">
                            <div class="button-container btn-outline-danger">
                                <div class="button-icon">
                                    <i class="bi bi-calendar2-x"></i>
                                </div>
                                <div class="button-text">
                                    <div class="button-title">IZIN KEHADIRAN</div>
                                    <div class="button-alt">Cuti tidak hadir</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <!--
                <div class="item">
                    <div class="sa">
                        <a href="absen_masuk.php">
                            <div class="icon-wrapper bg-biru">
                                <i class="bi bi-calendar2-check"></i>
                            </div>
                            <strong>Absen Masuk</strong>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <div class="sa">
                        <a href="absen_keluar.php">
                            <div class="icon-wrapper bg-biru">
                                <i class="bi bi-calendar2-minus"></i>
                            </div>
                            <strong>Absen Keluar</strong>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <div class="sa">
                        <label onclick="showModal()">
                            <div class="icon-wrapper bg-biru">
                                <i class="bi bi-calendar2-x"></i>
                            </div>
                            <strong>Izin Kehadiran</strong>
                        </label>
                    </div>
                </div> -->
                </div>
            </div>
            <!--
        <div class="p-3">
            <a class="btn btn-outline-dark btn-sm" href="absen_masuk.php">Absen masuk</a>
            <a class="btn btn-outline-dark btn-sm" href="absen_keluar.php">Absen keluar</a>
            <a class="btn btn-outline-success btn-sm" href="absen_sakit.php">Absen sakit</a>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="showModal()">Izin Tidak Hadir</button>
        </div>
        -->
        </div>
        <!--<script src="lokasi.js"></script>-->

        <!-- Modal -->
        <div class="modal fade" id="absenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Absen Sakit / Izin</h5>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="id_status">Jenis Absen</label>
                                <select class="form-control mt-2" id="absenSelect" name="id_status">
                                    <option value="3">Sakit</option>
                                    <option value="2">Izin</option>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="keteranganTextarea">Keterangan (opsional):</label>
                                <textarea class="form-control mt-2" name="keterangan" id="keteranganTextarea"
                                    rows="3"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onClick="$('#absenModal').modal('hide')">Batal</button>
                        <!--<button type="button" class="btn btn-primary"
                        onclick="insertAbsensi(<?php echo $userid; ?>)">Submit</button>-->
                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary" />
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showModal() {
            $('#absenModal').modal('show');
        }

        function insertAbsensi(userid) {
            var absenSelect = document.getElementById("absenSelect");
            var id_status = absenSelect.options[absenSelect.selectedIndex].value;

            if (id_status === "") {
                alert("Anda harus memilih jenis absen!");
            } else {
                var keterangan = document.getElementById("keteranganTextarea").value;
                console.log(userid);

                // Mengirim data absen ke PHP menggunakan Ajax
                $.ajax({
                    url: "",
                    type: "POST",
                    data: {
                        userid: userid,
                        id_status: id_status,
                        keterangan: keterangan
                    },
                    success: function (data) {
                        $('#absenModal').modal('hide');
                    },
                    error: function () {
                        alert("Terjadi kesalahan saat menyimpan absen!");
                    }
                });
            }
        }

        //////////////////////////////////////////////////////////////

        window.addEventListener("load", () => {
            const allowLocationButton = document.querySelector(
                "#allow-location-button"
            );
            const myLocation = document.querySelector("#my-location");
            const ourDistance = document.querySelector("#our-distance");
            const yourLatitude = document.querySelector("#your-latitude");
            const yourLongitude = document.querySelector("#your-longitude");
            const yourLocation = document.querySelector("#your-location");

            const myLat = "-6.5005329587694884"; // Latitude SMP SMA MKGR Kertasemaya
            const myLon = "108.36078998178328"; // Longitude SMP SMA MKGR Kertasemaya
            jarak = 1000; // Jarak Default

            function isSupportLocation() {
                if (navigator.geolocation) {
                    //allowLocationButton.classList.remove("d-none");

                    navigator.geolocation.getCurrentPosition(showPosition, (err) => {
                        switch (err.code) {
                            case 1:
                                swal.fire({
                                    title: "Gagal",
                                    text: "Anda tidak mengizinkan lokasi.",
                                    icon: "error",
                                });
                                tombolAbsenMasuk.setAttribute('style', 'pointer-events:none;opacity:.65');
                                tombolAbsenKeluar.setAttribute('style', 'pointer-events:none;opacity:.65');
                                //allowLocationButton.setAttribute("disabled", true);
                                break;
                            default:
                                break;
                        }
                    });
                } else {
                    swal({
                        title: "Gagal",
                        text: "browser ini tidak mendukung akses lokasi.",
                        icon: "error",
                    });
                }
            }

            function showPosition(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                const apiUrl = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=id`;

                fetch(apiUrl, { headers: { "Content-Type": "application/json" } })
                    .then((res) => res.json())
                    .then((res) => {
                        console.log(res);
                        const city = res.city === "" ? "" : res.city + ", ";
                        const provinsi = res.principalSubdivision === "" ? "" : res.principalSubdivision + ", ";
                        const negara =
                            res.countryName === "" ? "" : " " + res.countryName;

                        yourLatitude.innerText = res.latitude;
                        yourLongitude.innerText = res.longitude;
                        yourLocation.innerText = `${city}${provinsi}${negara}`;

                        const userLat = res.latitude;
                        const userLon = res.longitude;

                        calculateDistance(userLat, userLon);
                    });
            }

            function calculateDistance(userLat, userLon) {
                const apiUrl = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${myLat}&longitude=${myLon}&localityLanguage=id`;

                fetch(apiUrl, { headers: { "Content-Type": "application/json" } })
                    .then((res) => res.json())
                    .then((res) => {
                        myLocation.innerText = `${res.locality}, ${res.principalSubdivision}, ${res.countryName}`;
                    });

                const R = 6371e3; // metres
                const φ1 = (userLat * Math.PI) / 180; // φ, λ in radians
                const φ2 = (myLat * Math.PI) / 180;
                const Δφ = ((myLat - userLat) * Math.PI) / 180;
                const Δλ = ((myLon - userLon) * Math.PI) / 180;

                const a =
                    Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                    Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                const d = R * c; // in metres
                const distance = d.toFixed(0);

                const distanceInKm = distance / 1000;

                jarak = Intl.NumberFormat('id-ID', { minimumFractionDigits: 3 }).format(distanceInKm);

                console.log(distanceInKm.toFixed(3));

                console.log(Intl.NumberFormat().format(distanceInKm) + " kilometer");

                ourDistance.innerText = jarak + " kilometer";
            }

            // Tombol Absen Masuk
            var tombolAbsenMasuk = document.querySelector('a[href="absen_masuk.php"]');
            if (tombolAbsenMasuk) {
                tombolAbsenMasuk.addEventListener('click', function (event) {
                    event.preventDefault();
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'absen_masuk.php';

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'jarak';
                    input.value = jarak;

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                });
            }

            // Tombol Absen Keluar
            var tombolAbsenKeluar = document.querySelector('a[href="absen_keluar.php"]');
            if (tombolAbsenKeluar) {
                tombolAbsenKeluar.addEventListener('click', function (event) {
                    event.preventDefault();
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'absen_keluar.php';

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'jarak';
                    input.value = jarak;

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                });
            }

            isSupportLocation();

        });

        // untuk jam saat ini
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            d.setHours(d.getHours()); // Waktu Indonesia Barat (GMT+7)
            var t = d.toLocaleTimeString('en-US', { hour12: false });
            document.getElementById("jam").innerHTML = t;
        } myTimer();
    </script>
</body>

</html>