<?php
session_start(); // Mulai session

// Jika user belum login, alihkan ke halaman login
if (!isset($_SESSION['nip'])) {
    header("Location: login");
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "presensi";
$nama = "";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data pengguna sesuai dengan sesi
$userid = $_SESSION['nip'];

// Menyiapkan query untuk mengambil nama pengguna berdasarkan NIP
$hsl = $conn->query("SELECT nama FROM pengguna WHERE nip='$userid'");
$ambil = $hsl->fetch_assoc();
$nama = $ambil['nama'];

require_once 'vendor/autoload.php';

// Mengimpor class PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Jika tombol "Ekspor" diklik
//if (isset($_POST['export'])) {
// Membuat objek Spreadsheet
$spreadsheet = new Spreadsheet();

// Menambahkan teks NIP sebelum tabel
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('B2', 'NIP: ' . $userid)
    ->setCellValue('B3', 'Nama: ' . $nama);

// Menambahkan header kolom
$spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A5', 'No.')
    ->setCellValue('B5', 'Tanggal')
    ->setCellValue('C5', 'Jam Masuk')
    ->setCellValue('D5', 'Jam Keluar')
    ->setCellValue('E5', 'Status');

// Menyiapkan query untuk mengambil data absensi pengguna berdasarkan NIP
$query = "SELECT tanggal_absen, jam_masuk, jam_keluar, id_status FROM absen WHERE userid='$userid'";
// Khusus join tabel status_absen
$sqljo = "SELECT absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, status_absen.nama_status
FROM absen
JOIN status_absen ON absen.id_status = status_absen.id_status
WHERE userid = '$userid'";
// Jika terjadi kesalahan
$result = $conn->query($sqljo);
if (!$result) {
    die('Kesalahan: ' . $conn->error);
}

// Menambahkan data ke file excel
$row = 6;
$no = 1;
while ($data = $result->fetch_assoc()) {
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, $no)
        ->setCellValue('B' . $row, $data['tanggal_absen'])
        ->setCellValue('C' . $row, $data['jam_masuk'])
        ->setCellValue('D' . $row, $data['jam_keluar'])
        ->setCellValue('E' . $row, $data['nama_status']);

    // Set kolom No dan Status rata kiri
    $spreadsheet->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    $no++;
    $row++;
}

// Menambahkan garis tepi pada setiap baris dan kolom pada tabel, dimulai dari baris ke-5 dan kolom A sampai E
$lastRow = $spreadsheet->getActiveSheet()->getHighestRow();
$lastColumn = $spreadsheet->getActiveSheet()->getHighestColumn();
$spreadsheet->getActiveSheet()->getStyle('A5:'.$lastColumn.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Mengatur teks header pada kolom agar terletak di tengah
$spreadsheet->getActiveSheet()->getStyle('A5:'.$lastColumn.$lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

// Menentukan warna background pada baris ke-5
$spreadsheet->getActiveSheet()->getStyle('A5:E5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

// Menambahkan border ke sel B2 dan B3
$spreadsheet->getActiveSheet()->getStyle('B2:B3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Memberi background kuning pada sel B2 dan B3
$spreadsheet->getActiveSheet()->getStyle('B2:B3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$spreadsheet->getActiveSheet()->getStyle('B2:B3')->getFill()->getStartColor()->setARGB('FFFF00');

// Mengatur lebar kolom
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);

// Menyimpan file excel
$writer = new Xlsx($spreadsheet);
$namafile = 'rekap-absensi-' . $userid . '.xlsx';
$writer->save($namafile);

// Mengirim file excel ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $namafile . '"');
header('Cache-Control: max-age=0');
readfile($namafile);
exit();
//}
?>