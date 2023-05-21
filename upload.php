<?php
// Rayya
session_start(); // Mulai session
include_once 'sw-header.php';

//memeriksa apakah ada file yang diunggah
if(isset($_FILES['image'])){
  $image = $_FILES['image']['name'];
  $tmp_image = $_FILES['image']['tmp_name'];
  $file_ext = strtolower(end(explode('.', $image)));
  
  //membuat array untuk ekstensi file yang diperbolehkan
  $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
  
  //memeriksa apakah ekstensi file diizinkan
  if(in_array($file_ext, $allowed_ext)){
      //membuat nama file baru dengan gabungan nilai nip dan nama file
      $new_filename = $userid."_".$image;
      
      //memeriksa apakah pengguna sudah memiliki data gambar yang tersimpan di database
      $sql_check = "SELECT * FROM gambar WHERE nip='$userid'";
      $result_check = mysqli_query($conn, $sql_check);
      if(mysqli_num_rows($result_check) > 0){
          //menghapus file yang lama
          $row_check = mysqli_fetch_assoc($result_check);
          $old_filename = $row_check['nama_file'];
          unlink("foto_profil/".$old_filename);
          
          //memperbarui data gambar dengan data gambar yang baru
          $sql_update = "UPDATE gambar SET nama_file='$new_filename' WHERE nip='$userid'";
          mysqli_query($conn, $sql_update);
          
          //memindahkan file ke direktori tujuan dengan nama file baru
          move_uploaded_file($tmp_image, "foto_profil/".$new_filename);
          
          echo "Gambar berhasil diunggah dan diperbarui";
      }
      else{
          //memindahkan file ke direktori tujuan dengan nama file baru
          move_uploaded_file($tmp_image, "foto_profil/".$new_filename);
      
          //memasukkan data gambar ke database dengan nama file baru
          $sql = "INSERT INTO gambar (nip, nama_file) VALUES ('$userid', '$new_filename')";
          mysqli_query($conn, $sql);
      
          echo "Gambar berhasil diunggah";
      }
  }
  else{
      echo "Hanya file dengan ekstensi jpg, jpeg, png, atau gif yang diizinkan";
  }
}
?>