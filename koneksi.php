<?php
//konelsi ke database

$conn = mysqli_connect("localhost", "root", "", "skripsi");

function query($query)
{
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function tambah($data)
{
	global $conn;
	//ambil data dari tiap element dalam form
	$judul_skripsi 		= htmlspecialchars($data["judul_skripsi"]);
	$tahun 		= htmlentities($data["tahun"]);
	$jurusan	= htmlspecialchars($data["jurusan"]);
	$kategori	= htmlentities($data["kategori"]);;

	// query insert data
	$query = "INSERT INTO skripsi1
				VALUES
				('','$judul_skripsi','$tahun','$jurusan','$kategori')
				";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}


function hapus($id)
{
	global $conn;
	mysqli_query($conn, 'DELETE FROM skripsi1 WHERE id = $id');

	return mysqli_affected_rows($conn);
}



function ubah($data)
{
	global $conn;
	//ambil data dari tiap element dalam form
	$id = $data["id"];
	$judul 		= htmlspecialchars($data["judul_skripsi"]);

	$tahun 		= htmlentities($data["tahun"]);
	$jurusan	= htmlspecialchars($data["jurusan"]);
	$kategori	= htmlspecialchars($data["kategori"]);
	// query insert data
	$query = "UPDATE skripsi1 SET
				judul_skripsi= '$judul_skripsi',
				
				tahun 		 = '$tahun', 
				jurusan	 = '$Jurusan',
			    kategori	 = '$kategori',

				WHERE id 	 = $id   
				";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}
