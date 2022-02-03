<?php

session_start();
// cek apakah sudah login atau belum klo tidak, tendang kembali
if (!isset($_SESSION["login"])) {
  header("location:login.php");
  exit;
}

require 'koneksi.php';

//anbil id
$id = $_GET["id"];

//query data skripsi berdasarkan id
$skr = query("SELECT * FROM skripsi1 WHERE id = $id")[0];


//cek apakah tombol udah kepencet atau belum
if (isset($_POST["submit"])) {
  $id = $_POST['id'];
  $judul = $_POST['judul'];
  $tahun = $_POST['tahun'];
  $jurusan = $_POST['jurusan'];

  $query = mysqli_query($conn, "UPDATE `skripsi1` SET 
  `judul_skripsi` = '$judul', 
  `tahun` = '$tahun', 
  `jurusan` = '$jurusan' WHERE id = '$id'");
  if ($query) {
    echo "<script language=javascript>
  	window.alert('Data Berhasil Dirubah');
  	window.location='index.php';
  	</script>";
  } else {
    echo "<script language=javascript>
  	window.alert('Data Gagal Dirubah');
  	window.location='edit_data.php?id=$id';
  	</script>";
  }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>K-Means</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark static-top" style="background-color:#003636">

    <a class="navbar-brand mr-1" href="index.html">K-Means Clustering</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group">
      </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div class="garis" style="width: 100%;height: 6px; background-color:#20B2AA"></div>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav" style="background-color: #001C1C">


      <li class="nav-item">
        <a class="nav-link" href="dasboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dasboard</span>
        </a>
      </li>

      <li class="nav-item active ">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Data Skripsi</span>
        </a>
      </li>


      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Clustering</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">

          <a class="dropdown-item" href="clustering.php">Proses</a>
          <a class="dropdown-item" href="kata_kunci.php">Data Kata Kunci</a>
          <!--<a class="dropdown-item" href="text_processing.php">Analisa Teks</a>-->
        </div>
      </li>

    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            Halaman Ubah Data
          </li>
        </ol>
        <form action="" method="post">
          <input type="hidden" name="id" value="<?php echo $skr["id"]; ?>">
          <div class="form-group">
            <label for="judul_skripsi">Judul Skripsi</label>
            <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul Skripsi" required value="<?= $skr["judul_skripsi"]; ?>">
          </div>

          <div class="form-group">
            <label for="tahun">Tahun terbit = <?= $skr["tahun"]; ?> </label>
            <select class="custom-select" name="tahun" id="tahun" required placeholder="tahun">
              <option selected value="<?= $skr["tahun"]; ?>">Pilih tahun</option>
              <option value="2020" <?= $skr['tahun'] = '2020' ? 'selected' : null ?>>2020</option>
              <option value="2019" <?= $skr['tahun'] = '2019' ? 'selected' : null ?>>2019</option>
            </select>
          </div>

          <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <input type="text" class="form-control" name="jurusan" id="jurusan" placeholder="Jurusan" required value="<?= $skr["jurusan"]; ?>">
          </div>
          <button type="submit" name="submit" class="btn btn-primary"> Ubah Data</button>
          <button type="button" class="btn btn-danger"> <a href="index.php" style="text-decoration: none;  color: white;">Batal/Kembali</a></button>
        </form>

        <br>
        <table>
          <tr>
            <td>
            </td>
            <td>

            </td>


          </tr>
        </table>


        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Beni Adam</span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.php">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>

</body>

</html>