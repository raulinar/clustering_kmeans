<!DOCTYPE html>
<html>
<head>
 <title>From login</title>
 <link rel="stylesheet" type="text/css" href="css/style_login.css">
</head>
<body>

<?php
    if (isset($_GET['error'])){
        if ($_GET['error'] == 1){
            echo '<div id="error">USERNAME atau PASSWORD salah!</div>';
        }
    }
?>

<div class="login">
    <h2 class="login-header">Form Login</h2>
        <form class="login-container" method="post" action="">
            <p>    <input type="text" placeholder="Admin Name" name="user"></p>
            <p>    <input type="password" placeholder="Password" name="pass"></p>
            
            <p>
                <input type="submit" value="Log in" name="login">
            </p>
        </form>
</div>

</body>
</html>


<?php 

    //koneksi ke database
    include 'koneksi.php';

    if(isset($_POST['login'])){
        $username = $_POST['user'];
        $password = $_POST['pass'];

        $query = "select * from admin";
        $result = mysqli_query($konek,$query);
        $go = 'form_login.php?error=1';

        foreach ($result as $row) {
            if ($row['admin'] == $username && $row['password'] == $password){
                $go = 'home.php';
            }
        }

        setcookie('userid',$username,time()+3600);

        header('Location:'.$go);

    }
?>