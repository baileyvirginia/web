<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,
    "SELECT * FROM user WHERE username='$username' AND password='$password'");

    $data = mysqli_fetch_assoc($query);

    if(mysqli_num_rows($query) > 0){

        $_SESSION['id_user'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['id_outlet'] = $data['id_outlet'];

        header("location:dashboard.php");

    }else{

        $pesan = "Username atau Password Salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Laundry PRO</title>

    <style>

        body{
            font-family:'Segoe UI',sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            margin:0;
        }

        .box{
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            padding:40px;
            border-radius:20px;
            box-shadow:0 15px 35px rgba(0,0,0,0.2);
            width:350px;
            border:1px solid rgba(255,255,255,0.2);
            text-align:center;
        }

        h2{
            color:white;
            margin-bottom:30px;
        }

        input{
            width:100%;
            padding:12px;
            margin:10px 0;
            border:none;
            border-radius:10px;
            box-sizing:border-box;
        }

        button{
            width:100%;
            padding:12px;
            background:white;
            color:#764ba2;
            border:none;
            border-radius:10px;
            font-weight:bold;
            cursor:pointer;
            margin-top:20px;
        }

        button:hover{
            background:#f1f2f6;
        }

        .error{
            background:red;
            color:white;
            padding:10px;
            border-radius:8px;
            margin-bottom:15px;
            font-size:13px;
        }

        .register{
            margin-top:20px;
            color:white;
            font-size:14px;
        }

        .register a{
            color:#fff;
            font-weight:bold;
            text-decoration:none;
        }

    </style>

</head>
<body>

<div class="box">

    <h2>LOGIN LAUNDRY</h2>

    <?php if(isset($pesan)){ ?>
        <div class="error">
            <?php echo $pesan; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">
            MASUK SEKARANG
        </button>

    </form>

    <div class="register">
        Belum punya akun?
        <a href="register.php">Daftar</a>
    </div>

</div>

</body>
</html>