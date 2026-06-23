<?php
session_start();
include 'login.php';

// Pastikan hanya Kasir yang bisa masuk, jika Admin nyasar kemari akan tetap aman
if ($_SESSION['role'] != 'kasir' && $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir | Laundry bey</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f4f7fe;
            display: flex;
        }

        /* Sidebar Khusus Kasir */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            position: fixed;
        }

        .sidebar h2 {
            font-size: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .menu-item {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            transition: 0.3s;
            font-weight: 500;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }

        .menu-item.logout {
            background: #ff7675;
            margin-top: 50px;
            text-align: center;
        }

        .menu-item.logout:hover {
            background: #d63031;
        }

        /* Main Content */
        .main-content {
            margin-left: 290px;
            padding: 40px;
            width: 100%;
        }

        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .grid-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card-shortcut {
            background: white;
            padding: 40px 20px;
            border-radius: 20px;
            text-align: center;
            text-decoration: none;
            color: #2d3436;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: 0.3s;
            border: 1px solid #eee;
        }

        .card-shortcut:hover {
            transform: translateY(-10px);
            border-color: #6c5ce7;
            box-shadow: 0 15px 35px rgba(108, 92, 231, 0.2);
        }

        .icon {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }

        .card-shortcut h3 { margin: 0; color: #4834d4; }
        .card-shortcut p { color: #636e72; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>LAUNDRY KASIR</h2>
        <a href="dashboard_kasir.php" class="menu-item">🏠 Dashboard</a>
        <a href="entri_transaksi.php" class="menu-item">📝 Entri Transaksi</a>
        <a href="laporan.php" class="menu-item">📊 Generate Laporan</a>
        <a href="logout.php" class="menu-item logout">Keluar Akun</a>
    </div>

    <div class="main-content">
        <div class="welcome-card">
            <h1>Halo, <?php echo $_SESSION['nama']; ?>! 👋</h1>
            <p>Selamat datang di panel Kasir. Silahkan kelola transaksi hari ini.</p>
        </div>

        <div class="grid-menu">
            <a href="entri_transaksi.php" class="card-shortcut">
                <span class="icon">➕</span>
                <h3>Entri Transaksi</h3>
                <p>Input cucian baru dan pembayaran pelanggan.</p>
            </a>

            <a href="laporan.php" class="card-shortcut">
                <span class="icon">📋</span>
                <h3>Generate Laporan</h3>
                <p>Lihat dan cetak riwayat transaksi harian.</p>
            </a>
        </div>
    </div>

</body>
</html>