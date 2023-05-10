<?php

/*$nip = "";
$password = "";
$nama = "";
$jabatan = "";
$guru = "";
$sukses = "";
$error = "";*/

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
                <div class="mb-3" style="align-items:center;margin-right:auto;margin-left:auto">
                    <video id="video" width="280" height="200" style="transform: scaleX(-1);" autoplay></video>
                </div>

                <!-- -->

                <div class="mb-3">
                    <span class="d-block"><i class="bi-geo-alt text-success"></i> <a class="text-success"
                            href="https://goo.gl/maps/zwucsvHDvmTCVtYUA" target="_blank">SMP SMA MKGR</a>: <b
                            id="my-location">belum terdeteksi</b></span>
                </div>
                <div class="mb-3">
                    <span class="d-block">
                        Lokasi Anda: <b id="your-location">belum terdeteksi</b></span>
                    <span class="d-block">Lat: <b id="your-latitude">belum terdeteksi</b> - | - Long: <b
                            id="your-longitude">belum terdeteksi</b> - | - <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-sm" onclick="showModalMap()">
                            Tampilkan Peta
                        </button></span>
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
                <div class="mb-3">
                    <div class="d-block">
                        <span id="blocation" class="d-flex justify-content-center align-items-center"
                            style="color:#0d6efd;font-weight:700"><a id="allow-location-button" type="button">Izinkan
                                Lokasi</a> <i class="bi bi-box-arrow-up-right"
                                style="margin-left:8px;font-size:13px"></i></span>
                    </div>
                </div>
                <!--< button id = "allow-location-button" type = "button" class="btn btn-primary" >
                        Izinkan akses lokasi saya
            </button > -->
                <div class="wallet-footer" style="flex-wrap:wrap;justify-content:space-between">
                    <div class="item">
                        <label id="captureButton">
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
                        </label>
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
            <!-- < div class="p-3">
                <a class="btn btn-outline-dark btn-sm" href="absen_masuk.php">Absen masuk</a>
                <a class="btn btn-outline-dark btn-sm" href="absen_keluar.php">Absen keluar</a>
                <a class="btn btn-outline-success btn-sm" href="absen_sakit.php">Absen sakit</a>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="showModal()">Izin Tidak
                    Hadir</button>
        </div>
        -->
        </div>
        <!--< script src="lokasi.js">
        </script> -->

        <!--Modal Izin-->
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

        <!--Modal Map-->
        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mapModalLabel">Peta Lokasi</h5>
                    </div>
                    <div class="modal-body">
                        <div id="mapid" style="height: 400px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onClick="$('#mapModal').modal('hide')">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />

    <script>
        function showModal() {
            $('#absenModal').modal('show');
        }
        function showModalMap() {
            $('#mapModal').modal('show');
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
            const buttonLocation = document.querySelector("#blocation");
            const myLocation = document.querySelector("#my-location");
            const ourDistance = document.querySelector("#our-distance");
            const yourLatitude = document.querySelector("#your-latitude");
            const yourLongitude = document.querySelector("#your-longitude");
            const yourLocation = document.querySelector("#your-location");

            const myLat = "-6.5005329587694884"; // Latitude SMP SMA MKGR Kertasemaya
            const myLon = "108.36078998178328"; // Longitude SMP SMA MKGR Kertasemaya
            myLocation.innerText = "Kertasemaya, Jawa Barat, Indonesia";
            jarak = 1000; // Jarak Default

            allowLocationButton.addEventListener('click', function () {
                requestLocationPermission();
            });

            function requestLocationPermission() {
                // Meminta izin akses lokasi
                navigator.permissions.query({ name: 'geolocation' }).then(function (result) {
                    if (result.state == 'granted') {
                        // Jika izin akses lokasi telah diberikan, panggil fungsi untuk mendapatkan lokasi
                        isSupportLocation();
                    } else if (result.state == 'prompt') {
                        // Jika pengguna belum memberikan izin akses lokasi, minta izin akses lokasi
                        navigator.geolocation.getCurrentPosition(isSupportLocation, showError);
                    } else if (result.state == 'denied') {
                        // Jika pengguna telah memblokir izin akses lokasi, tampilkan pesan kesalahan
                        swal.fire({
                            title: "Gagal",
                            html: "Anda telah memblokir akses lokasi.<br>Harap izinkan akses lokasi pada pengaturan browser Anda.",
                            icon: "error",
                        });
                        buttonLocation.classList.remove("d-none");
                        tombolAbsenMasuk.setAttribute('style', 'pointer-events:none;opacity:.65');
                        tombolAbsenKeluar.setAttribute('style', 'pointer-events:none;opacity:.65');
                    }
                    result.onchange = function () {
                        // Jika pengguna mengubah izin akses lokasi, panggil fungsi untuk memeriksa ulang izin akses lokasi
                        requestLocationPermission();
                    }
                });
            }

            function isSupportLocation() {
                if (navigator.geolocation) {
                    buttonLocation.classList.remove("d-none");
                    navigator.geolocation.getCurrentPosition(showPosition);
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
                yourLatitude.innerText = latitude;
                yourLongitude.innerText = longitude;

                var mymap;
                $('#mapModal').on('hidden.bs.modal', function () {
                    mymap.remove();
                });
                $('#mapModal').on('shown.bs.modal', function () {
                    mymap = L.map('mapid').setView([latitude, longitude], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                        maxZoom: 18,
                        tileSize: 512,
                        zoomOffset: -1
                    }).addTo(mymap);

                    var marker = L.marker([latitude, longitude]).addTo(mymap);
                });

                buttonLocation.classList.add("d-none");
                tombolAbsenMasuk.setAttribute('style', '');
                tombolAbsenKeluar.setAttribute('style', '');

                const apiUrl = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=id`;

                fetch(apiUrl, { headers: { "Content-Type": "application/json" } })
                    .then((res) => res.json())
                    .then((res) => {
                        console.log(res);
                        const city = res.city === "" ? "" : res.city + ", ";
                        const provinsi = res.principalSubdivision === "" ? "" : res.principalSubdivision + ", ";
                        const negara =
                            res.countryName === "" ? "" : " " + res.countryName;

                        yourLocation.innerText = `${city}${provinsi}${negara}`;

                        const userLat = latitude;
                        const userLon = longitude;

                        calculateDistance(userLat, userLon);
                    });
            }

            function calculateDistance(userLat, userLon) {
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
                    Webcam.snap(function (data_uri) {
                        event.preventDefault();
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'absen_masuk.php';

                        var inputJarak = document.createElement('input');
                        inputJarak.type = 'hidden';
                        inputJarak.name = 'jarak';
                        inputJarak.value = jarak;
                        form.appendChild(inputJarak);

                        var inputDataUri = document.createElement('input');
                        inputDataUri.type = 'hidden';
                        inputDataUri.name = 'data_uri';
                        inputDataUri.value = data_uri;
                        form.appendChild(inputDataUri);

                        document.body.appendChild(form);
                        form.submit();
                    });
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

            requestLocationPermission();

        });

        // Mengambil elemen video dan tombol ambil foto
        const video = document.getElementById("video");
        const captureButton = document.getElementById("captureButton");

        function requestCameraPermission() {
            // Meminta izin akses kamera
            navigator.permissions.query({ name: 'camera' }).then(function (result) {
                if (result.state === 'granted') {
                    // Jika izin akses kamera telah diberikan, coba akses kamera lagi
                    accessCamera();
                } else if (result.state === 'prompt') {
                    // Jika pengguna belum memberikan izin akses kamera, minta izin akses kamera
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(function (stream) {
                            video.srcObject = stream;
                        })
                        .catch(function (error) {
                            showError("Error accessing webcam: " + error);
                        });
                } else if (result.state === 'denied') {
                    // Jika pengguna telah memblokir izin akses kamera, tampilkan pesan kesalahan
                    Swal.fire({
                        title: "Gagal",
                        html: "Anda telah memblokir akses kamera.<br>Harap izinkan akses kamera pada pengaturan browser Anda.",
                        icon: "error",
                    });
                    // Tambahkan logika tambahan jika diperlukan setelah pemblokiran izin akses kamera
                }
                result.onchange = function () {
                    // Jika pengguna mengubah izin akses kamera, panggil fungsi untuk memeriksa ulang izin akses kamera
                    requestCameraPermission();
                };
            });
        }

        // Fungsi untuk mengakses kamera dan mengatur aliran video
        function accessCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                })
                .catch(function (error) {
                    showError("Error accessing webcam: " + error);
                });
        }

        // Fungsi untuk menampilkan pesan kesalahan
        function showError(error) {
            console.log(error);
            // Tambahkan logika tambahan jika diperlukan untuk menangani kesalahan akses kamera
        }

        // Panggil fungsi untuk meminta izin akses kamera
        requestCameraPermission();

        ///////////////////////////////////////////////////////

        // Mengambil foto saat tombol ambil foto diklik
        captureButton.addEventListener("click", async function () {
            // Memeriksa izin kamera
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                // Izin kamera diberikan, melanjutkan dengan kode pengambilan foto
                stream.getTracks().forEach((track) => track.stop()); // Menutup stream kamera yang tidak digunakan
                takePhoto();
            } catch (error) {
                // Izin kamera tidak diberikan atau terjadi kesalahan lain
                Swal.fire("Harap izinkan akses kamera!");
            }
        });

        // Fungsi untuk mengambil foto
        function takePhoto() {
            // Membuat elemen canvas untuk mengambil foto
            const canvas = document.createElement("canvas");
            const context = canvas.getContext("2d");

            // Mengatur ukuran canvas sesuai dengan ukuran video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Menggambar video pada canvas
            context.scale(-1, 1); // Tambahkan baris ini untuk membalikkan gambar secara horizontal
            context.drawImage(video, 0, 0, -canvas.width, canvas.height);

            // Mengubah foto menjadi URL data (base64)
            const photo = canvas.toDataURL("image/png");

            if (photo) {
                // Menampilkan SweetAlert2 dengan pesan dan foto
                Swal.fire({
                    title: "Ingin Absen Masuk?",
                    text: "",
                    imageUrl: photo,
                    imageAlt: "Foto Absen",
                    showCancelButton: true,
                    confirmButtonText: "Masuk",
                    cancelButtonText: "Batal",
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            const image = new Image();
                            image.src = photo;
                            image.onerror = () => {
                                Swal.showValidationMessage("Foto tidak dapat dimuat!");
                                resolve(false);
                            };
                            image.onload = () => {
                                resolve(true);
                            };
                        });
                    },
                }).then((result) => {
                    // Jika tombol "Absen Masuk" pada SweetAlert2 diklik
                    if (result.isConfirmed) {
                        // Buat formulir dinamis
                        const form = document.createElement("form");
                        form.action = "absen_masuk.php";
                        form.method = "POST";
                        form.style.display = "none";

                        // Tambahkan input jarak ke dalam formulir
                        const jarakInput = document.createElement("input");
                        jarakInput.type = "hidden";
                        jarakInput.name = "jarak";
                        jarakInput.value = jarak;
                        form.appendChild(jarakInput);

                        // Tambahkan input foto ke dalam formulir
                        const photoInput = document.createElement("input");
                        photoInput.type = "hidden";
                        photoInput.name = "photo";
                        photoInput.value = photo;
                        form.appendChild(photoInput);

                        // Tambahkan formulir ke dalam dokumen
                        document.body.appendChild(form);

                        // Submit formulir
                        form.submit();
                    }
                });
            } else {
                Swal.fire("Tidak dapat menangkap gambar!");
            }
        }

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