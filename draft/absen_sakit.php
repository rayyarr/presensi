<?php
session_start(); // Mulai session
require_once('database.php');
require_once('Absenclass.php');
$obj = new Absensiswa;
$userid = $_SESSION['nip'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<?php

	# Sebelum kita menampilkan formulir, kita cek dulu apakah dia sudah absen sebelumnya
	if($obj->cek_Absenmasuk($userid))
	{
	//jika sudah absen sebelumnya arahkan ke index.php

				echo 
				'
				<script> 
					window.alert("Anda sudah masuk absen sakit hari ini");
					window.location.href="index.php";
					

				</script>
				';
	}
	else
	{
			$status_id = 3;
			$tanggal_absen = date('Y-m-d'); 
			$jam_masuk = NULL;
			$jam_keluar = NULL;
			if($obj->insert_Absensakit($userid,$status_id,$tanggal_absen, $jam_masuk, $jam_keluar))
			{
				echo 
				'
				<script> 
					window.alert("Anda berhasil absen sakit hari ini");
					window.location.href="index.php";
					

				</script>
				';
				
			}
			else
			{
				echo 
				"
				<script> 
					alert('Anda gagal absen hari ini');
				</script>
				";
				
			}
		
?>

<form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
	<table style="border: 1px solid #ccc;" width="500px">
		<tr>
			<td colspan="2">Formulir Absen masuk</td>
		</tr>
		<tr>
			<td>Siap untuk absen ?</td>
			<td><input type="submit" name="absen" value="Klik Absen"></td>
		</tr>
		

	</table>
	
</form>


<?php }?>

	</body>
</html>