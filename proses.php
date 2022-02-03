<?php

session_start();

if (!isset($_SESSION["login"])) {
  header("location:login.php");
  exit;
}

//============================================================================================================
//	DECLARATION
//============================================================================================================

require 'koneksi.php';

$kategori         = query("SELECT * FROM kategori");

$judul             = query("SELECT * FROM skripsi1");           // MENGAMBIL DATA JUDUL DI DATABASE
$kata_kunci       = query("SELECT * FROM kata_kunci");        // MENGAMBIL DATA KATA KUNCI DI DATABASE
$cluster_terdekat = array();                                  // DEKLARASI VARIABEL ARRAY
$total_cluster     = $_POST["total_cluster"];                  // MENGAMBIL DATA DARI POST
$nilai_cluster    = array();
$hasil_tfIdf      = array();
$kata_kunci_temp  = array();

//============================================================================================================
//  MEMBUAT DUPLIKAT JUDUL 
//============================================================================================================

$judul_temp = []; // untuk tampung judul skripsi
$i = 0;
foreach ($judul as $row) {
  $judul_temp[$i] = $row['judul_skripsi'];
  $i++;
}

//============================================================================================================
//  MENGUBAH SEMUA NILAI AWAL JADI NOL
//============================================================================================================

for ($i = 0; $i < count([$total_cluster]); $i++) {
  $pusatAwal[$i] = 0;
}

for ($i = 0; $i < count($kata_kunci); $i++) {
  for ($j = 0; $j < count($judul); $j++) {
    $hasil_tfIdf[$i][$j]  = 0;
    $cluster_terdekat[$j] = 0;
  }
}

for ($i = 0; $i < count($judul); $i++) {
  for ($j = 0; $j < $total_cluster; $j++) {
    $nilai_cluster[$i][$j] = 0;
  }
}



//============================================================================================================
//  MEMBUAT DUPLIKAT KATA KUNCI
//============================================================================================================
$kata_kunci_temp = [];
$i = 0;
foreach ($kata_kunci as $row) {
  $kata_kunci_temp[$i] = $row['kata'];
  $i++;
}



//============================================================================================================
//  MENCARI PUSAT AWAL
//============================================================================================================

$a = floor(count($judul_temp) / $total_cluster);     // total judul di setiap kelompok judul

for ($i = 0; $i < $total_cluster; $i++) {  //melooping sebanyak total cluster

  $b = $a;

  if (($a * ($i + 1)) > count($judul_temp))
    $b = ($a * ($i + 1)) - count($judul_temp);

  $pusatAwal[$i] = ($a * $i) + rand(0, $b - 1);   //RANDOM
  // $genap  = ($a % 2 == 0);

  // $median = 0;
  // if ($genap){
  //     $median = floor((($a / 2) + (($a / 2) + 1)) / 2);   //rumus mean genap
  // }else{
  //     $median = floor(($a + 1)/ 2);   //rumus mean genap
  // }

  // $pusatAwal[$i] = $median + ($a * $i);

}

// for ($i = 0; $i < $total_cluster; $i++){
//     echo $pusatAwal[$i].'<br>';
// }



//============================================================================================================
//  MEMERIKSA KATA2 DITIAP JUDUL
//============================================================================================================


$termA  = array(); // beberapa kali kata trsebut muncul di seluruh judul tertentu
$termB  = array(); // beberapa kali kata tersebut muncul di seluruh judul

for ($i = 0; $i < count($kata_kunci_temp); $i++) { //memriksa satu persatu kata kunci
  $termB[$i] = 0;

  for ($j = 0; $j < count($judul_temp); $j++) {

    $telahMuncul    = false;
    $termA[$i][$j] = 0;
    $str            = strtolower($judul_temp[$j]) . " ";
    $subStr         = "";

    for ($k = 1; $k  < strlen($str); $k++) {

      $nextChar = $str[$k];

      if ($nextChar != " ") {
        $subStr .= $nextChar;
      } else {

        if ($kata_kunci_temp[$i] == $subStr) {
          $termA[$i][$j]++;
          if (!$telahMuncul) {
            $termB[$i]++;
          }
        }

        $subStr = "";
      }
    }
  }
}


//============================================================================================================
//  MELAKUKAN OPERASI FREKUENSI TERM TF - IDF
//============================================================================================================
$idf    = array();
$tfidf  = array();

for ($i = 0; $i < count($kata_kunci_temp); $i++) {
  for ($j = 0; $j < count($judul_temp); $j++) {

    if ($termB[$i] != 0)
      $idf[$i][$j]   = log10(count($judul_temp) / $termB[$i]);
    else
      $idf[$i][$j]   = 0;

    $tfidf[$i][$j] = $termA[$i][$j] * $idf[$i][$j];



    if (is_nan($tfidf[$i][$j])) {
      $tfidf[$i][$j] = 0;
    }

    $hasil_tfIdf[$i][$j] = $tfidf[$i][$j];
  }
}

//===========================================
//  RUMUS CLUSTERING
//==============================================================

$hasil;
for ($i = 0; $i < count($judul_temp); $i++) {
  for ($j = 0; $j < $total_cluster; $j++) {
    $hasil[$j] = 0;

    for ($k = 0; $k < count($kata_kunci_temp); $k++) {
      $hasil[$j] += ($tfidf[$k][$i] - $tfidf[$k][$pusatAwal[$j]]) * ($tfidf[$k][$i]  - $tfidf[$k][$pusatAwal[$j]]);
    }

    $res = sqrt($hasil[$j]);

    $nilai_cluster[$i][$j] = $res;
  }
}


//===========================================
//  JARAK TERDEKAT DARI CLUSTER
//==============================================================
for ($i = 0; $i < count($judul_temp); $i++) {
  $terkecil = 99999;

  for ($j = 0; $j < $total_cluster; $j++) {

    if ($nilai_cluster[$i][$j] < $terkecil) {
      $terkecil  = $nilai_cluster[$i][$j];
      $cluster_terdekat[$i] = $j;
    }
  }
}


for ($i = 0; $i < count($judul_temp); $i++) {
  $judul_temp[$i] = strtoupper($judul_temp[$i]);
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


      <li class="nav-item ">
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

          <!--<a class="dropdown-item" href="text_processing.php">Analisa Teks</a>-->
        </div>
      </li>

    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <div class="row">
          <div class="col-md-1">
            <button type="submit" class="btn btn-danger" style=""><a href="clustering.php" style="text-decoration: none; position: relative; color: white;"> Kembali </a>
            </button>
          </div>


          <div class="col-md-10 offset-md-1">
            <ol class="breadcrumb" style="background-color: #52CBBE;color:white;">
              <li class="breadcrumb-item">
                Hasil Klasterisasi (Total Cluster : <?php echo $total_cluster; ?> )
              </li>
            </ol>
          </div>

          <!--    <div class="col-md-2">
                <button type="submit" class="btn btn-primary" style=""><a href="tambah_data_proses.php"  style="text-decoration: none; position: relative; color: white; "> Tambah data </a>
                </button>
            </div>
 -->
        </div>

        <br>

        <!--  yyy  <div class="card mb-3">
          <div class="card-header" style="background-color: #00A0A8;color:white;" >
            <i class="fas fa-table"></i>
            Tabel Hasil Clustering Tanpa Text Mining</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                <thead style="background-color:#00A0A8; color: white;text-align: center;">
                  <tr>
                    <th>No.</th>
                    <th style="width : 60%;">Judul Skripsi</th>



                    <?php
                    for ($i = 0; $i < $total_cluster; $i++) {
                    ?> yyy -->


        <!--   NOO  <th style="font-size:12px;">Jarak ke Cluster<?php echo " ";  ?><?php echo $i + 1;  ?></th> -->


        <!--  <?php
                    }
              ?> -->


        <!--  <th>Ada Di Cluster</th> -->
        <!--NOOO <th>Kategori</th> -->
        <!--  </tr>
                </thead>

                <tfoot>
                  <tr>
                    <th>No.</th>
                    <th style="width : 60%;">Judul Skripsi</th>

 -->
        <!--NOO  <?php
                  for ($i = 0; $i < $total_cluster; $i++) {
                  ?>
                    <th>Cluster <?php echo $i + 1;  ?></th>
                    <?php
                  }
                    ?>
 -->

        <!-- <th>Ada Di Cluster</th> -->
        <!--   NOO <th>kategori</th> -->
        <!--  </tr>
                </tfoot>


                <tbody>
                    <?php
                    $query = query("SELECT judul_skripsi, kategori FROM skripsi");
                    $i = 0;
                    foreach ($query as $row) {
                      $kategori_judul[$i] = $row['kategori'];
                      $i++;
                    }
                    for ($j = 0; $j < count($judul_temp); $j++) {
                    ?>
                  <tr>
                    <td><?php echo $j + 1; ?></td>
                    <td><?php echo $judul_temp[$j]; ?></td>
 -->


        <!-- NOO <?php
                      for ($i = 0; $i < $total_cluster; $i++) {
                  ?>
                    <td style="text-align: center"><?php echo number_format($nilai_cluster[$j][$i], 2);  ?></th>
                    <?php
                      }
                    ?> -->


        <!-- <td style="text-align: center"><?php echo $cluster_terdekat[$j] + 1; ?></td> -->


        <!--NOO <td><?php echo $kategori_judul[$j]; ?></td> -->

        <!-- </tr>

                  <?php
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">K-Means 1</div>
        </div>
 -->






        <!-------------------------------DENGAN TEXT MINING-------------------------------------------------------->
        <!-------------------------------DENGAN TEXT MINING-------------------------------------------------------->
        <!-------------------------------DENGAN TEXT MINING-------------------------------------------------------->
        <!-------------------------------DENGAN TEXT MINING-------------------------------------------------------->


        <?php

        //============================================================================================================
        //  DECLARATION
        //============================================================================================================
        foreach (array_keys($GLOBALS) as $k) unset($k);
        unset($k);

        $kategori         = query("SELECT * FROM kategori");
        $title_judul      = query("SELECT * FROM skripsi1");
        $judul            = query("SELECT * FROM skripsi1");           // MENGAMBIL DATA JUDUL DI DATABASE
        $kata_kunci       = query("SELECT * FROM kata_kunci");        // MENGAMBIL DATA KATA KUNCI DI DATABASE
        $cluster_terdekat = array();                                  // DEKLARASI VARIABEL ARRAY
        $nilai_cluster    = array();
        $hasil_tfIdf      = array();
        $kata_kunci_temp  = array();



        //============================================================================================================
        //  TEXT MINING
        //============================================================================================================
        function preproses($teks)
        {
          global $kata_kunci_temp;
          //1. ubah ke huruf kecil            
          $teks = strtolower(trim($teks));



          //hilangkan tanda baca
          $data = array('_', '-', '/', '\\', ',', '.', '#', ':', ';', '\'', '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+');
          $teks = str_replace($data, ' ', $teks);



          //2. hapus stoplist
          //daftar stop word diletakkan di array
          //anda boleh menggunakan database sebagai gantinya
          $astoplist = array(
            " yang ", " juga ", " dari ", " dia ", " kami ", " kamu ", " ini ", " itu ",
            " atau ", " dan ", " tersebut ", " pada ", " dengan ", " adalah ", " yaitu ",
          );

          for ($i = 0; $i < count($astoplist); $i++) {
            $teks = str_replace($astoplist[$i], " ", $teks);
          }




          //3. terapkan stemming
          //pemetaan term --> stem hanya menggunakan $arrayName = array('' => , );
          //index ganjil menunjukkan term dan index genap adalah stem dari term tersebut
          //anda boleh menggunakan database sebagai gantinya
          $astemlist = array(
            "naive", "naive", "mobile", "mobile",
            "sistem", "sistem", "pakar", "pakar", "factor", "factor",
            "pembelajaran", "belajar", "certainty", "certainty",
            "monitoring", "monitoring", "reality", "reality", "pembelajaran", "belajar", "website", "web", "penjadwalan", "jadwal", "pembangunan",
            "bangun", "kemahasiswaan", "mahasiswa", "perancangan", "rancang",
            "rasperry", "rasperry", "implementasi", "implementasi", "alat", "alat", "metode", "metode",
            "algoritma", "algoritma", "virtual", "virtual"

            //   // lah|kah|tah|pun==========================================================
            //   "hancurlah", "hancur","benarkah", "benar","apatah", "apa","siapapun", "siapa",

            //  // ku|mu|nya================================================================
            //   "jubahku","jubah","bajumu", "baju","celananya", "celana",

            //  // i|kan|an=================================================================
            //   "hantui","hantu","belikan","beli","jualan", "jual",

            //  //combination of suffixes========================================================
            //   "bukumukah", "buku", "miliknyalah", "milik", "kulitkupun", "kulit", "berikanku", "beri", "sakitimu", "sakit", "beriannya", "beri", "kasihilah", "kasih",

            //   // plain prefix========================================================
            //   "dibuang", "buang", "kesakitan", "sakit", "sesuap", "suap",

            //  // rule 1a : berV -> ber-V==================================================
            //   "beradu", "adu",

            //  // rule 1b : berV -> be-rV ber-V==================================================
            //   "berambut", "rambut",

            //  // rule 2 : berCAP -> ber-CAP ber-V==================================================
            //   "bersuara", "suara",

            //  // rule 3 : berCAerV -> ber-CAerV where C != 'r'===========================================
            //   "berdaerah", "daerah",

            //   // rule 4 : belajar -> bel-ajar=========================================
            //   "belajar", "ajar",

            //   // rule 5 : beC1erC2 -> be-C1erC2 where C1 != {'r'|'l'}=========================================
            //   "bekerja", "kerja","beternak", "ternak",

            //   // rule 6a : terV -> ter-V=========================================
            //   "terasing", "asing",

            //  // rule 6b : terV -> te-rV========================================
            //  "teraup", "raup",

            //  // rule 7 : terCerV -> ter-CerV where C != 'r'=======================================
            //  "tergerak", "gerak",

            //  // rule 8 : terCP -> ter-CP where C != "r" and P != "er"=======================================
            //  "terpuruk", "puruk",

            //  // rule 9 : teC1erC2 -> te-C1erC2 where C1 != 'r'=====================================
            //  "teterbang", "terbang",

            //  // rule 10 : me{l|r|w|y}V -> me-{l|r|w|y}V=======================================
            //  "melipat", "lipat",
            //  "meringkas", "ringkas",
            //  "mewarnai", "warna",
            //  "meyakinkan", "yakin",

            //  // rule 11 : mem{b|f|v} -> mem-{b|f|v}=======================================
            //  "membangun", "bangun",
            //  "memfitnah", "fitnah",
            //  "memvonis", "vonis",

            //  // rule 12 : mempe{r|l} -> mem-pe======================================
            //  "memperbarui", "baru",
            //  "mempelajari", "ajar",

            //   // rule 13a : mem{rV|V} -> mem{rV|V}=====================================
            //  "meminum", "minum",

            //  // rule 13b : mem{rV|V} -> me-p{rV|V}=====================================
            //  "memukul", "pukul",

            //  // rule 14 : men{c|d|j|z} -> men-{c|d|j|z}=====================================
            //  "mencinta", "cinta",
            //  "mendua", "dua",
            //  "menjauh", "jauh",
            //  "menziarah", "ziarah",

            //  // rule 15a : men{V} -> me-n{V}}=====================================
            //  "menuklir", "nuklir",

            //  // rule 16 : meng{g|h|q} -> meng-{g|h|q}=====================================
            //  "menggila", "gila",
            //  "menghajar", "hajar",
            //  "mengqasar", "qasar",

            //   // rule 17a : mengV -> meng-V=====================================
            //  "mengudara", "udara",

            //  // rule 17b : mengV -> meng-kV=====================================
            //  "mengupas", "kupas",

            //   // rule 18 : menyV -> meny-sV====================================
            //  "menyuarakan", "suara",

            //  // rule 19 : mempV -> mem-pV where V != 'e'===================================
            //  "mempopulerkan", "populer",

            //  // rule 20 : pe{w|y}V -> pe-{w|y}V==================================
            //  "pewarna", "warna",

            //  // rule 21a : perV -> per-V===================================
            //  "peradilan", "adil",

            //  // rule 21b : perV -> pe-rV===================================
            //  "perumahan", "rumah",

            //  // rule 23 : perCAP -> per-CAP where C != 'r' and P != 'er===================================
            //  "permuka", "muka",

            //   // rule 24 : perCAerV -> per-CAerV where C != 'r'===================================
            //  'perdaerah', 'daerah',

            //   // // rule 25 : pem{b|f|v} -> pem-{b|f|v}===================================
            //  "pembangun", "bangun",
            //  "pemfitnah", "fitnah",
            //  "pemvonis", "vonis",

            //   //rule 26a : pem{rV|V} -> pe-m{rV|V}===================================
            //  "peminum", "minum",

            //   // rule 26b : pem{rV|V} -> pe-p{rV|V}===================================
            //  "pemukul", "pukul",

            //   // rule 27 : men{c|d|j|z} -> men-{c|d|j|z}===================================
            //  "pencinta", "cinta",
            //  "pendahulu", "dahulu",
            //  "penjarah", "jarah",
            //  "penziarah", "ziarah",

            //   // rule 28a : pen{V} -> pe-n{V}===========================================
            //  "penasihat", "nasihat",

            //  // rule 28b : pen{V} -> pe-t{V}===========================================
            //  "penangkap", "tangkap",

            //  // rule 29 : peng{g|h|q} -> peng-{g|h|q}==========================================
            //  "penggila", "gila",
            //  "penghajar", "hajar",
            //  "pengqasar", "qasar",

            //   // rule 30b : pengV -> peng-kV==========================================
            //  "pengupas", "kupas",

            //  // rule 32 : pelV -> pe-lV except pelajar -> ajar==========================================
            //  "pelajar", "ajar",
            //  "pelabuhan", "labuh",

            // // rule 34 : peCP -> pe-CP where C != {r|w|y|l|m|n} and P != 'er'==========================================
            //  "petarung", "tarung",

            //  // rule 34 : peCP -> pe-CP where C != {r|w|y|l|m|n} and P != 'er'==========================================
            //  "terpercaya", "percaya",

            //  //rule 36 : peC1erC2 -> pe-C1erC2 where C1 != {r|w|y|l|m|n}==========================================
            //  "pekerja", "kerja",
            //  "peserta", "serta",

            //  // CS modify rule 12==========================================
            //  "mempengaruhi", "pengaruh",

            //  // CS modify rule 16==========================================
            //  "mempengaruhi", "pengaruh",

            //  // CS adjusting rule precedence==========================================
            //  "bersekolah", "sekolah",
            //  "bertahan", "tahan",
            //  "mencapai", "capai",
            //  "dimulai", "mulai",
            //  "petani", "tani",
            //  "terabai", "abai",

            //  // ECS==========================================
            //   "mensyaratkan", "syarat",
            //   "mensyukuri", "syukur",
            //   "mengebom", "bom",
            //   "mempromosikan", "promosi",
            //   "memproteksi", "proteksi",
            //   "memprediksi", "prediksi",
            //   "pengkajian", "kaji",
            //   "pengebom", "bom",
            //   "perbaikan", "baik",
            //   "kebaikannya", "baik",
            //   "bisikan", "bisik",
            //   "menerangi", "terang",
            //   "berimanlah", "iman",
            //   "memuaskan", "puas",
            //   "berpelanggan", "langgan",
            //   "bermakanan", "makan",

            //   // CC (Modified ECS)==============================================
            //   "menyala", "nyala",
            //   "menyanyikan", "nyanyi",
            //   "menyatakannya", "nyata",
            //   "penyanyi", "nyanyi",
            //   "penyawaan", "nyawa",

            //   // CC infix========================================================
            //   "rerata", "rata",
            //   "lelembut", "lembut",
            //   "lemigas", "ligas",
            //   "kinerja", "kerja",

            //    //  plurals========================================================
            //     "buku-buk","buk",
            //    "berbalas-balasa","bala",
            //    "bolak-bali","bolak-bali",

            //    // combination of prefix + suffix=======================================================
            //    "bertebaran", "tebar",
            //    "terasingkan", "asing",
            //    "membangunkan", "bangun",
            //    "mencintai", "cinta",
            //    "menduakan", "dua",
            //    "menjauhi", "jauh",
            //    "menggilai", "gila",
            //    "pembangunan", "bangun",

            //    // return the word if not found in the dictionary=======================================================
            //   "marwan", "marwan",
            //   "subarkah", "subarkah",

            //    // recursively remove prefix=======================================================
            //    "memberdayakan", "daya",
            //    "persemakmuran", "makmur",
            //    "keberuntunganmu", "untung",
            //    "kesepersepuluhnya", "sepuluh",

            //    // ISSUES=======================================================
            //    "Perekonomian", "ekonomi",
            //    "menahan", "tahan",

            //     // adopted foreign suffixes=======================================================
            //    "idealis", "ideal",
            //    "idealisme", "ideal",
            //    "finalisasi", "final",

            //    // sastrawi additional rules=======================================================
            //   "penstabilan", "stabil",
            //   "pentranskripsi", "transkripsi",
            //   "mentaati", "taat",
            //   "meniru-nirukan", "tiru",
            //   "menyepak-nyepak", "sepak",
            //   "melewati", "lewat",
            //   "menganga", "nganga",
            //   "kupukul", "pukul",
            //   "kauhajar", "hajar"


          );
          //============================================================================================================
          //   END TEXT MINING
          //============================================================================================================






          //perhatikan cara mengubah suatu term ke bentuk stemnya
          for ($i = 0; $i < count($astemlist); $i = $i + 2) {
            //ganti term (jika ditemukan term pada index ganjil) dengan stem pada index genap ($i=1)        
            if (str_replace($astemlist[$i], $astemlist[$i + 1], $teks)) {
              $teks = str_replace($astemlist[$i], $astemlist[$i + 1], $teks);
              $result = false;

              for ($j = 0; $j < count($kata_kunci_temp); $j++) {
                if ($astemlist[$i + 1] == $kata_kunci_temp[$j])
                  $result = true;
              }

              if ($result == false) {
                $kata_kunci_temp[] = $astemlist[$i + 1];
              }
            }
          }





          //hilangkan ruang kosong di awal & akhir teks   
          $teks = trim($teks);

          return $teks;
        }

        //============================================================================================================
        //  MEMBUAT DUPLIKAT JUDUL SEKALIGUS MELAKUKAN TEXT MINING
        //============================================================================================================
        $judul_temp = [];
        $i = 0;
        foreach ($judul as $row) {
          $judul_temp[$i] = preproses($row['judul_skripsi']);
          $i++;
        }

        //============================================================================================================
        //  MEMBUAT DUPLIKAT JUDUL SEKALIGUS MELAKUKAN TEXT MINING
        //============================================================================================================

        for ($i = 0; $i < count([$total_cluster]); $i++) {
          $pusatAwal[$i] = 0;
        }

        for ($i = 0; $i < count($kata_kunci_temp); $i++) {
          for ($j = 0; $j < count($judul); $j++) {
            $hasil_tfIdf[$i][$j]  = 0;
            $cluster_terdekat[$j] = 0;
          }
        }

        for ($i = 0; $i < count($judul); $i++) {
          for ($j = 0; $j < $total_cluster; $j++) {
            $nilai_cluster[$i][$j] = 0;
          }
        }



        //============================================================================================================
        //  MEMBUAT DUPLIKAT KATA KUNCI
        //============================================================================================================
        // $i = 0;
        // foreach ($kata_kunci as $row){
        //     $kata_kunci_temp[$i] = $row['kata'];
        //     $i++;
        // }



        //============================================================================================================
        //  MENCARI PUSAT AWAL
        //============================================================================================================

        $a = floor(count($judul_temp) / $total_cluster);     // total judul disetiap kelompok judul

        for ($i = 0; $i < $total_cluster; $i++) {


          $b = $a;
          if (($a * ($i + 1)) > count($judul_temp))
            $b = ($a * ($i + 1)) - count($judul_temp);

          // $pusatAwal[$i] = ($a * $i) + rand(0, $b - 1);   //RANDOM
          $genap  = ($a % 2 == 0);
          $median = 0;
          if ($genap) {
            $median = floor((($a / 2) + (($a / 2) + 1)) / 2);
          } else {
            $median = floor(($a + 1) / 2);
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

        for ($i = 0; $i < count($kata_kunci_temp); $i++) { //memriksa satu persatu kata kunci
          $termB[$i] = 0;

          for ($j = 0; $j < count($judul_temp); $j++) {

            $telahMuncul    = false;
            $termA[$i][$j] = 0;
            $str            = strtolower($judul_temp[$j]) . " ";
            $subStr         = "";

            for ($k = 1; $k  < strlen($str); $k++) {

              $nextChar = $str[$k];

              if ($nextChar != " ") {
                $subStr .= $nextChar;
              } else {

                if ($kata_kunci_temp[$i] == $subStr) {
                  $termA[$i][$j]++;
                  if (!$telahMuncul) {
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

        for ($i = 0; $i < count($kata_kunci_temp); $i++) {
          for ($j = 0; $j < count($judul_temp); $j++) {

            if ($termB[$i] != 0)
              $idf[$i][$j]   = log10(count($judul_temp) / $termB[$i]);
            else
              $idf[$i][$j]   = 0;

            $tfidf[$i][$j] = $termA[$i][$j] * $idf[$i][$j];



            if (is_nan($tfidf[$i][$j])) {
              $tfidf[$i][$j] = 0;
            }

            $hasil_tfIdf[$i][$j] = $tfidf[$i][$j];
          }
        }

        //===========================================
        //  CLUSTERING
        //==============================================================
        unset($hasil);
        $hasil;
        for ($i = 0; $i < count($judul_temp); $i++) {
          for ($j = 0; $j < $total_cluster; $j++) {
            $hasil[$j] = 0;

            for ($k = 0; $k < count($kata_kunci_temp); $k++) {
              $hasil[$j] += ($tfidf[$k][$i] - $tfidf[$k][$pusatAwal[$j]]) * ($tfidf[$k][$i]  - $tfidf[$k][$pusatAwal[$j]]);
            }

            $res = sqrt($hasil[$j]);
            $nilai_cluster[$i][$j] = $res;
          }
        }


        //===========================================
        //  JARAK TERDEKAT DARI CLUSTER
        //==============================================================
        for ($i = 0; $i < count($judul_temp); $i++) {
          $terkecil = 99999;

          for ($j = 0; $j < $total_cluster; $j++) {

            if ($nilai_cluster[$i][$j] < $terkecil) {
              $terkecil  = $nilai_cluster[$i][$j];
              $cluster_terdekat[$i] = $j;
            }
          }
        }


        for ($i = 0; $i < count($judul_temp); $i++) {
          $judul_temp[$i] = strtoupper($judul_temp[$i]);
        }

        ?>


        <br>

        <div class="card mb-3">
          <div class="card-header" style="background-color: #00A0A8;color:white;">
            <i class="fas fa-table"></i>
            Tabel Hasil Clustering Dengan Text Mining
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                <thead style="background-color: #00A0A8;color: white;text-align: center;">
                  <tr>
                    <th>No.</th>
                    <!--   <th style="width : 60%;">Abstrak</th> -->
                    <th style="width : 60%;">judul</th>

                    <?php
                    for ($i = 0; $i < $total_cluster; $i++) {
                    ?>


                      <!-- <th style="font-size:12px;">Jarak ke Cluster<?php echo " ";  ?><?php echo $i + 1;  ?></th> -->


                    <?php
                    }
                    ?>


                    <th>Berada di Cluster</th>
                    <!-- <th>kategori</th> -->

                  </tr>
                </thead>

                <tfoot style="text-align: center;">
                  <tr>
                    <th>No.</th>
                    <!-- <th style="width : 60%;">abstrak</th> -->
                    <th style="width : 60%;">Judul Skripsi</th>


                    <!-- <?php
                          for ($i = 0; $i < $total_cluster; $i++) {
                          ?>
                      <th>Cluster <?php echo $i + 1;  ?></th>
                    <?php
                          }
                    ?> -->


                    <th>Berada di Cluster</th>
                    <!-- <th>kategori</th> -->
                  </tr>
                </tfoot>


                <tbody>

                  <?php
                  $query = mysqli_query($conn, "SELECT judul_skripsi, kategori FROM skripsi1");
                  $judl_tampil = query("SELECT judul_skripsi FROM skripsi1");
                  $kata_kunci = mysqli_query($conn, "SELECT * FROM kata_kunci");
                  $term = mysqli_fetch_assoc($kata_kunci);

                  $i = 0;
                  $j = 0;
                  while ($data = mysqli_fetch_array($query)) {
                    $kategori_judul[$j] = $data['kategori'];
                    $j++;
                    foreach ($judl_tampil as $jt) {
                      $lihat_judul[$i] = $jt['judul_skripsi'];
                      $i++;
                    }
                  }
                  for ($j = 0; $j < count($judul_temp); $j++) {
                  ?>
                    <tr>
                      <td><?php echo $j + 1; ?></td>
                      <!--  <td><?php echo $judul_temp[$j]; ?></td> -->
                      <td>
                        <?= $lihat_judul[$j] ?> <br>
                        <?php
                        $pecah = explode(" ", $lihat_judul[$j]);
                        $keyword = mysqli_query($conn, "SELECT kata FROM kata_kunci");
                        for ($a = 0; $a < count($pecah); $a++) {
                          // echo $pecah[$a] . "<br>";
                          while ($key = mysqli_fetch_array($keyword)) {
                            // echo $key['kata'] . "<br>";
                            if ($pecah[$a] == ucwords($key['kata'])) {
                              echo "<span class='badge badge-warning'>" . ucwords($key['kata']) . "</span>" . "<br>";
                            }
                          }
                        }
                        ?>

                      </td>

                      <td style="text-align: center"><?php echo $cluster_terdekat[$j] + 1; ?></td>
                      <!-- <td>
                        <?php
                        if ($kategori_judul[$j] == 1) {
                          echo "AI";
                        } else if ($kategori_judul[$j] == 2) {
                          echo "RPL";
                        } else if ($kategori_judul[$j] == 3) {
                          echo "Jaringan";
                        } else {
                          echo "Tidak ada";
                        }
                        ?>
                      </td> -->
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">K-Means 2</div>
        </div>

        <!-- SDFFSDFSDF-->




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
            <h5 class="modal-title" id="exampleModalLabel">yakin mau keluar?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">pilih logout untuk keluar! </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="Logout.php">Logout</a>
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