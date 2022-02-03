<?php
//konelsi ke database

$conn = mysqli_connect("localhost","root","","skripsi");

function query ($query){
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while ( $row = mysqli_fetch_assoc($result) ){
		$rows[] = $row;
	}

	return $rows; 
}

function tambah ($data){
	global $conn;
	//ambil data dari tiap element dalam form
	$kata 		= htmlspecialchars($data["kata"]) ;

	// query insert data
	$query = "INSERT INTO kata_kunci
				VALUES
				('','$kata')
				";
	mysqli_query($conn,$query);

	return mysqli_affected_rows($conn);
}


function hapus ($id){
	global $conn;
	mysqli_query($conn, "DELETE FROM kata_kunci WHERE id = $id");

	return mysqli_affected_rows($conn);
}



function ubah($id, $kata){

	global $conn;
	//ambil data dari tiap element dalam form
	$value	= htmlspecialchars($kata);

	// query insert data
	$query = "UPDATE kata_kunci 
	SET kata = '$value' 
	WHERE id 	 = $id";
	mysqli_query($conn,$query);
 	//echo mysqli_error($conn);

	return mysqli_affected_rows($conn);
}

  ?>