<?php

session_start();
// cek apakah sudah login atau belum klo tidak, tendang kembali
  if (!isset($_SESSION["login"]) ){
    header("location:login.php");
    exit;
  }

 require 'koneksi_kata.php';
$id = $_GET["id"];

if (hapus ($id) > 0 ) {
	echo "
	<script>
			alert(' Data Berhasil dihapus !');
			document.location.href = '../kata_kunci.php';
	</script>
	";

} else {
	echo "
	<script>
			alert(' Data Gagal dihapus !');
			document.location.href = '../kata_kunci.php';
	</script>
	";
}

  ?>