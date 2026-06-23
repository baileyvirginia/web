<?php 
include 'koneksi.php';

if(isset($_POST['register'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Sebaiknya gunakan password_hash untuk keamanan produksi
    $role = $_POST['role'];
    $id_outlet = $_POST['id_outlet'];
header("location:dashboard.php");

    // Cek apakah username sudah ada
    $cek_user = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if(mysqli_num_rows($cek_user) > 0){
        $pesan = "Username sudah digunakan!";
    } else {
        $query = mysqli_query($conn, "INSERT INTO user (nama, username, password, role, id_outlet) 
                                      VALUES ('$nama', '$username', '$password', '$role', '$id_outlet')");
        if($query){
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='index.php';</script>";
        } else {
            $pesan = "Gagal mendaftar, coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun Laundry</title>
    <style>
        /* Mengambil style yang sama dengan login agar serasi */
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; 
        }
        .box { 
            background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(15px); padding: 30px; border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 380px; 
            border: 1px solid rgba(255, 255, 255, 0.2); text-align: center;
        }
        h2 { color: white; margin-bottom: 20px; }
        input, select { 
            width: 100%; padding: 12px; margin: 8px 0; border: none; 
            border-radius: 10px; box-sizing: border-box; background: rgba(255, 255, 255, 0.9);
        }
        button { 
            width: 100%; padding: 12px; background: #ffffff; color: #764ba2; 
            border: none; border-radius: 10px; cursor: pointer; font-weight: bold; margin-top: 15px;
        }
        .error { background: #e74c3c; color: white; padding: 10px; border-radius: 8px; margin-bottom: 10px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>DAFTAR AKUN</h2>
        
        <?php if(isset($pesan)): ?>
            <div class="error"><?php echo $pesan; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <select name="role" required>
                <option value=""> Pilih Jabatan </option>
                <option value="admin">pelanggan</option>
            </select>

            <button type="submit" name="register">DAFTAR SEKARANG</button>
        </form>
        
        <p style="color: white; font-size: 13px; margin-top: 15px;">
            Sudah punya akun? <a href="index.php" style="color: #f1f2f6; font-weight: bold; text-decoration: none;">Login</a>
        </p>
    </div>
</body>
</html>