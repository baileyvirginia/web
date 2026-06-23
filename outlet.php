<?php
session_start();
include 'koneksi.php';

// Pastikan koneksi tidak error
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SESSION['role'] != 'admin') { header("location:dashboard.php"); exit; }

// --- PROSES HAPUS DATA ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    // Cek kembali nama kolom di database, jika bukan 'id', ganti 'id' di bawah ini:
    mysqli_query($conn, "DELETE FROM outlet WHERE id_outlet='$id_hapus'");
    header("location:outlet.php");
    exit;
}

// --- PROSES TAMBAH DATA ---
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']); 
    $tlp = mysqli_real_escape_string($conn, $_POST['tlp']);
    mysqli_query($conn, "INSERT INTO outlet (nama, alamat, tlp) VALUES ('$nama', '$alamat', '$tlp')");
    header("location:outlet.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Outlet Laundry</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 40px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; margin: 0; color: #2d3436; }
        .card { background: rgba(255, 255, 255, 0.95); padding: 30px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); max-width: 900px; margin: auto; }
        h2 { color: #4834d4; margin-top: 10px; text-align: center; }
        .btn-back { text-decoration: none; color: #764ba2; font-weight: bold; display: inline-block; margin-bottom: 20px; transition: 0.3s; }
        form input, form textarea { width: 100%; padding: 12px; margin: 8px 0 15px 0; border: 1px solid #ddd; border-radius: 10px; box-sizing: border-box; background: #f9f9ff; }
        .btn-save { background: #6c5ce7; color: white; border: none; padding: 12px 25px; border-radius: 10px; cursor: pointer; width: 100%; font-weight: bold; transition: 0.3s; }
        .btn-save:hover { background: #5f27cd; transform: translateY(-2px); }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; background: white; border-radius: 12px; overflow: hidden; }
        th { background: #6c5ce7; color: white; padding: 15px; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 15px; font-size: 14px; }
        .btn-del { color: white; text-decoration: none; font-weight: bold; background: #ff7675; padding: 6px 12px; border-radius: 6px; font-size: 12px; }
    </style>
</head>
<body>
<div class="card">
    <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    <h2>Manajemen Outlet</h2>
    
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Outlet" required>
        <textarea name="alamat" placeholder="Alamat Outlet" rows="2" required></textarea>
        <input type="text" name="tlp" placeholder="No Telepon" required>
        <button type="submit" name="simpan" class="btn-save"> Simpan Data Outlet</button>
    </form>
    
    <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $n=1; 
            $q=mysqli_query($conn, "SELECT * FROM outlet");
            while($d=mysqli_fetch_array($q)){
                // --- BAGIAN PENTING: CEK NAMA KOLOM ---
                // Jika error 'Undefined array key id' muncul lagi, 
                // ganti $d['id'] di bawah ini menjadi nama kolom ID yang benar di database kamu.
                $id_outlet = $d[0]; // Menggunakan index 0 sebagai alternatif jika nama kolom tidak pasti
            ?>
                <tr>
                    <td><?php echo $n++; ?></td>
                    <td><b><?php echo $d['nama']; ?></b></td>
                    <td><?php echo $d['alamat']; ?></td>
                    <td><?php echo $d['tlp']; ?></td>
                    <td>
                        <a href="outlet.php?hapus=<?php echo $id_outlet; ?>" 
                           class="btn-del" 
                           onclick="return confirm('Hapus outlet ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>