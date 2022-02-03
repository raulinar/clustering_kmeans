<?php

session_start();
// cek apakah sudah login atau belum klo tidak, tendang kembali
if (!isset($_SESSION["login"])) {
	header("location:login.php");
	exit;
}

require 'koneksi.php';
if (isset($_GET['id'])) {
	$id = $_GET["id"];
	$query = mysqli_query($conn, "DELETE FROM skripsi1 where id = '$id'");
	if ($query) {
		echo "<script language=javascript>
  	window.alert('Data Berhasil Dirubah');
  	window.location='index.php';
  	</script>";
	} else {
		echo "<script language=javascript>
  	window.alert('Data Gagal Dirubah');
  	window.location='index.php';
  	</script>";
	}
}
