





<?php

session_start();
// cek apakah sudah login atau belum klo tidak, tendang kembali
  if (!isset($_SESSION["login"]) ){
    header("location:login.php");
    exit;
  }

//============================================================================================================
//  DECLARATION
//============================================================================================================

require 'koneksi.php';

$judul        = query("SELECT * FROM skripsi");           // MENGAMBIL DATA JUDUL DI DATABASE
$kata_kunci       = query("SELECT * FROM kata_kunci");        // MENGAMBIL DATA KATA KUNCI DI DATABASE
$cluster_terdekat = array();                                  // DEKLARASI VARIABEL ARRAY
$total_cluster    = 5;                  // MENGAMBIL DATA DARI POST
$nilai_cluster    = array();
$hasil_tfIdf    = array();


for ($i = 0; $i < count($judul); $i++){
  $pusatAwal[$i] = 0;
}

for ($i = 0; $i < count($kata_kunci); $i++){
  for ($j = 0; $j < count($judul); $j++){
    $hasil_tfIdf[$i][$j]  = 0;
    $cluster_terdekat[$j] = 0;
  }
}

for ($i = 0; $i < count($judul); $i++){
  for ($j = 0; $j < $total_cluster; $j++){
    $nilai_cluster[$i][$j] = 0;
  }
}


//============================================================================================================
//  TEXT MINING
//============================================================================================================



function preproses($teks) {
 
    //1. ubah ke huruf kecil            
    $teks = strtolower(trim($teks));
     
    //hilangkan tanda baca
    $teks = str_replace("'", " ", $teks);
 
    $teks = str_replace("-", " ", $teks);
 
    $teks = str_replace(")", " ", $teks);
 
    $teks = str_replace("(", " ", $teks);
 
    $teks = str_replace("\"", " ", $teks);
 
    $teks = str_replace("/", " ", $teks);
 
    $teks = str_replace("=", " ", $teks);
 
    $teks = str_replace(".", " ", $teks);
 
    $teks = str_replace(",", " ", $teks);
 
    $teks = str_replace(":", " ", $teks);
 
    $teks = str_replace(";", " ", $teks);
 
    $teks = str_replace("!", " ", $teks);
 
    $teks = str_replace("?", " ", $teks);
                 
    //2. hapus stoplist
    //daftar stop word diletakkan di array
    //anda boleh menggunakan database sebagai gantinya
    $astoplist = array ("yang", "juga", "dari", "dia", "kami", "kamu", "ini", "itu", 
                               "atau", "dan", "tersebut", "pada", "dengan", "adalah", "yaitu");     
                                             
    foreach ($astoplist as $i => $value) {
        $teks = str_replace($astoplist[$i], "", $teks);
    }
             
    //3. terapkan stemming
    //pemetaan term --> stem hanya menggunakan $arrayName = array('' => , );
    //index ganjil menunjukkan term dan index genap adalah stem dari term tersebut
    //anda boleh menggunakan database sebagai gantinya
    $astemlist = array("pertemuan", "temu", "bertemu", "temu", "cr9", "cristiano ronaldo", "berharap", "harap");
     
    //perhatikan cara mengubah suatu term ke bentuk stemnya
    for ($i=0; $i<count($astemlist); $i = $i +2) {
        //ganti term (jika ditemukan term pada index ganjil) dengan stem pada index genap ($i=1)        
        $teks = str_replace($astemlist[$i], $astemlist[$i+1], $teks);
    }
                 
 
    //hilangkan ruang kosong di awal & akhir teks   
    $teks = trim($teks);
 
    return $teks;
 

}

//============================================================================================================
//  MEMBUAT DUPLIKAT JUDUL SEKALIGUS MELAKUKAN TEXT MINING
//============================================================================================================
$judul_temp = [];
$kata_kunci_temp = [];
$i = 0;
foreach ($judul as $row){
    $judul_temp[$i] = preproses($row['judul_skripsi']);
    $i++;
}

$i = 0;
foreach ($kata_kunci as $row){
    $kata_kunci_temp[$i] = $row['kata'];
    $i++;
}

//============================================================================================================
//  MENCARI PUSAT AWAL
//============================================================================================================

$a = floor(count($judul_temp) / $total_cluster);     // total judul disetiap kelompok judul

for ($i = 0; $i < $total_cluster; $i++){


    $b = $a;
    if (($a * ($i + 1)) > count($judul_temp))
        $b = ($a * ($i + 1)) - count($judul_temp);


    $genap  = ($a % 2 == 0);
    $median = 0;
    if ($genap){
        $median = floor((($a / 2) + (($a / 2) + 1)) / 2);
    }else{
        $median = floor(($a + 1)/ 2);
    }



    $pusatAwal[$i] = $median + ($a * $i);

}

// for ($i = 0; $i < $total_cluster; $i++){
//     echo $pusatAwal[$i].'<br>';
// }



//============================================================================================================
//  MEMERIKSA KATA2 DITIAP JUDUL
//============================================================================================================


$termA  = array(); //beberapa kali kata trsebut muncul di seluruh judul tertentu
$termB  = array(); // beberapa kali kata tersebut muncul di seluruh judul

 for ($i = 0; $i < count($kata_kunci_temp); $i++ ){ //memriksa satu persatu kata kunci
    $termB[$i] = 0;

    for ($j = 0 ; $j < count($judul_temp); $j++){

        $telahMuncul    = false;
        $termA [$i][$j] = 0;
        $str            = strtolower($judul_temp[$j]) . " ";
        $subStr         = "";

        for ($k = 1; $k  < strlen($str); $k++) { 

            $nextChar = $str[$k];

            if ($nextChar != " "){
                    $subStr .= $nextChar;
            }else{

                if ($kata_kunci_temp[$i] == $subStr){
                $termA[$i][$j]++;
                    if (!$telahMuncul){
                        $termB[$i]++;
                    }
                }
                
                $subStr = "";
            }


        }

    }

}





//============================================================================================================
//  FREKUENSI TERM
//============================================================================================================
$idf    = array();
$tfidf  = array();

for ($i = 0; $i < count($kata_kunci_temp); $i++){
    for ($j = 0; $j < count($judul_temp); $j++){

        if ($termB[$i] != 0)
            $idf[$i][$j]   = log10( count($judul_temp) / $termB[$i] );
        else
            $idf[$i][$j]   = 0;
        
        $tfidf[$i][$j] = $termA[$i][$j] * $idf[$i][$j];



         if (is_nan($tfidf[$i][$j])){
            $tfidf[$i][$j] = 0;
         }

         $hasil_tfIdf[$i][$j] = $tfidf[$i][$j];
         
    } 

}

//===========================================
//  CLUSTERING
//==============================================================

$hasil;
for ($i=0; $i < count($judul_temp) ; $i++) { 
    for ($j=0; $j < $total_cluster ; $j++) { 
        $hasil[$j] = 0;

        for ($k = 0; $k < count($kata_kunci_temp) ; $k++) { 
            $hasil[$j] += ( $tfidf[$k][$i] - $tfidf[$k][$pusatAwal[$j]] ) * ( $tfidf[$k][$i]  - $tfidf[$k][$pusatAwal[$j]] );
        }

        $res = sqrt($hasil[$j]);
        $nilai_cluster[$i][$j] = $res;
    }
}


//===========================================
//  JARAK TERDEKAT DARI CLUSTER
//==============================================================
 for ($i=0; $i < count($judul_temp); $i++) { 
     $terkecil = 99999;

     for ($j=0; $j < $total_cluster; $j++) { 

         if ($nilai_cluster[$i][$j] < $terkecil){
            $terkecil  = $nilai_cluster[$i][$j];
            $cluster_terdekat[$i] = $j;
         }


     }
 }


for ($i = 0; $i < count($judul_temp); $i++){
    $judul_temp[$i] = strtoupper($judul_temp[$i]);
}

// for ($i = 0; $i < count($judul_temp); $i++){
//     for ($j = 0; $j < count($total_cluster); $j++){
//      echo $nilai_cluster[$i][$j] . "<br><br>";
//     }
// }


// for ($i = 0; $i < count($judul_temp); $i++){
//     echo $cluster_terdekat[$i] . "<br><br>";
// }
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

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

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
          <a class="dropdown-item" href="login.php" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div class="garis" style="width: 100%;height: 6px; background-color:#20B2AA"></div>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">

       <li class="nav-item">
          <a class="nav-link" href="dasboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dasboard</span>
          </a>
        </li>

      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-folder"></i>
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
          <a class="dropdown-item" href="text_processing.php">Analisa Teks</a>
        </div>
      </li>
      
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li>
          Halaman Hasil Preprocessing
          </li>
        </ol>

        <!-- Icon Cards
Area Chart Example
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-area"></i>
            Area Chart Example</div>
          <div class="card-body">
            <canvas id="myAreaChart" width="100%" height="30"></canvas>
          </div>
          <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div> 
-->
        

        <form action="" method="post">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                  
             </div>
          </div>

          <div class="col-md-5">
              <div class="form-group">
                
             </div>
          </div>
        </div>
          
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
            <span>Copyright © Rahmat 2019</span>
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
