
<?php

  session_start(); 

    //koneksi ke database
    require 'koneksi.php';

  //-------------------COOKIE-------------------------------
  //  cek apakah ada cookie
  if (isset($_COOKIE['id']) && isset($_COOKIE['key'])){
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // ambil username bersaarkan id
    $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $id");
    $row    = mysqli_fetch_assoc($result);

    //cek cookie dan username 
    if ($key === hash('sha256', $row['username'])){
      $_SESSION['login'] = true;
    }
  }

  //------------------SESSION----------------------------
  //cek klo udah login ditendang ke index.!!
  if (isset($_SESSION["login"]) ){
    header("location:dasboard.php");
  }

  

    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "select * from user";
        $result = mysqli_query($conn,$query);
        

        foreach ($result as $row) {
            if ($row['username'] == $username && $row['password'] == $password){
                $go = 'dasboard.php';

                //setting session
                $_SESSION["login"]=true; 

                // cek ingat saya 
                if (isset($_POST['ingat'])){

                  //buat cookie
                 // setcookie('login', 'true', time() + 60); (normal)
                  setcookie('id', $row['id'], time() + 60);
                  setcookie('key', hash('sha256', $row['username']), time() +60);
                }

                header('Location:'.$go);
            }

        }

        
        $error = true;
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

  <title>SB Admin - Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">
<?php if (isset($error)): ?>{
   echo "
  <script>
      alert(' username atau password salah !');
      document.location.href = 'login.php';
  </script>
  ";
}
 <?php endif;?>

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header"><img src="icon/per2.png"> Login</div>
      <div class="card-body">
        
        <form action="" method="post" >

          <div class="form-group" >
            <div class="form-label-group">
              <input type="input" id="inputEmail" class="form-control" placeholder="User Name" required="required" autofocus="autofocus" name="username">
              <label for="inputEmail">User Name</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="required" name="password">
              <label for="inputPassword">Password</label>
            </div>
          </div>

          <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me" name="ingat">
                Ingat saya
              </label>
            </div>
          </div>

        <!-- <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me">
                Remember Password
              </label>
            </div>
          </div> -->

        <button class="btn btn-primary btn-block" name="login">Login <img src="icon/in2.png"></button> 
        </form>

       
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  
</body>

</html>


