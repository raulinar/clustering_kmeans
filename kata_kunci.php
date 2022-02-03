<?php 

session_start();

  if (!isset($_SESSION["login"]) ){
    header("location:login.php");
    exit;
  }

require 'operasikata/koneksi_kata.php';
$katakunci = query("SELECT * FROM kata_kunci");
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

  <nav class="navbar navbar-expand navbar-dark  static-top" style="background-color:#003636">

    <a class="navbar-brand mr-1" href="dasboard.php">K-Means Clustering</a>

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
          <a class="dropdown-item" href="login.php" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div class="garis" style="width: 100%;height: 6px; background-color:#20B2AA"></div>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav" style="background-color: #001C1C">

        <li class="nav-item ">
          <a class="nav-link" href="dasboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dasboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-table"></i>
            <span>Data Skripsi</span>
          </a>
        </li>

          <li class="nav-item  active dropdown">
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
          Kata Kunci
          </li>
          
        </ol>

        
        <div class="row">
           <div class="col-md-2 offset-md-10">
                <button type="button" class="btn btn-primary" style=""> <img src="icon/add.png"><a href="operasikata/tambah_data.php" style="text-decoration: none; position: relative; color: white;">Tambah Data</a>
                </button>
            </div>
        </div>
        <br>

        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Tabel Data Kata Kunci</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Kata kunci (KeyTerm)</th>
                    <th>Aksi</th>
                  </tr>
                </thead>

                <tfoot>
                   <tr>
                  <th>No.</th>
                    <th>Kata kunci (KeyTerm)</th>
                     <th>Aksi</th>
                  </tr>

                </tfoot>

                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($katakunci as $kakun ): ?>
                      <tr>
                        <td> <?php echo $i;  ?></td>
                        <td> <?php echo $kakun["kata"];  ?></td>
                        <td align="text-center"> 
                         <button type="button" class="btn btn-primary" style="">
                                  <a href="operasikata/edit_kata.php?id=<?= $kakun["id"]; ?>" 
                                    style="text-decoration: none; position: relative; color: white;"> 
                                   Ubah 
                                  </a>
                              </button>
                              <button type="button" class="btn btn-danger"
                                      >
                                   <a href="operasikata/hapus_kata.php?id=<?= $kakun["id"]; ?>" onclick = "return confirm('Klik Ok untuk lanjut menghapus?');" style="text-decoration: none; position: relative;color: white;"> Hapus</a>
                              </button>
                         </td>

                      </tr>
                  <?php $i++; ?>
                <?php endforeach;  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

      </div>
   

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © Raulina Rauzan</span>
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
          <h5 class="modal-title" id="exampleModalLabel">yakin mau kelua?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">pilih logout untuk keluar!</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="logout.php">Logout</a>
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
