<?php 
session_start();
if(!isset($_SESSION['role'])) { header("location:index.php"); exit; }
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Laundry Modern</title>
    <style>
        body { 
            font-family: 'Segoe UI', Roboto, sans-serif; 
            margin: 0; 
            display: flex; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh;
            color: white;
        }

        /* Sidebar Glassmorphism */
        .sidebar { 
            width: 280px; 
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); 
            height: 100vh; 
            padding: 25px; 
            box-sizing: border-box; 
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 10px 0 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
        }

        .sidebar h3 {
            font-size: 24px;
            letter-spacing: 2px;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .sidebar p {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
        }

        .menu-item { 
            display: block; 
            color: rgba(255,255,255,0.9); 
            text-decoration: none; 
            padding: 14px 18px; 
            border-radius: 12px; 
            margin-bottom: 8px; 
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .menu-item:hover { 
            background: rgba(255, 255, 255, 0.2); 
            transform: translateX(10px); 
            color: white;
        }

        /* Highlight menu Kelola Petugas agar mudah ditemukan */
        .btn-highlight {
            background: rgba(241, 196, 15, 0.2);
            border: 1px solid rgba(241, 196, 15, 0.5);
        }

        .active { 
            background: #ffffff !important; 
            color: #764ba2 !important; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .content { 
            flex: 1; 
            padding: 40px; 
            overflow-y: auto;
        }

        .header { 
            background: rgba(255, 255, 255, 0.95); 
            padding: 30px; 
            border-radius: 20px; 
            margin-bottom: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            color: #2d3436;
            animation: fadeIn 0.8s ease;
        }

        .header h2 { margin: 0; color: #6c5ce7; font-size: 28px; }
        .header p { color: #636e72; margin-top: 10px; font-size: 16px; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        hr { border: 0; border-top: 1px solid rgba(255,255,255,0.2); margin: 20px 0; }
        
        .logout {
            margin-top: 50px;
            background: rgba(231, 76, 60, 0.2);
        }
        .logout:hover { background: rgba(231, 76, 60, 0.8) !important; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>LAUNDRY PRO</h3>
    <p>👋 Halo, <b><?php echo $_SESSION['nama']; ?></b></p>
    <hr>
    
    <a href="dashboard.php" class="menu-item active">Dashboard</a>
    
    <?php if($role == 'admin' || $role == 'kasir'): ?>
        <a href="registrasi_pelanggan.php" class="menu-item">Registrasi Pelanggan</a>
    <?php endif; ?>

    <?php if($role == 'admin'): ?>
        <a href="outlet.php" class="menu-item">CRUD Outlet</a>
        <a href="paket.php" class="menu-item">CRUD Paket Cucian</a>
        <a href="pengguna.php" class="menu-item">CRUD Pengguna</a>
    <?php endif; ?>

    <?php if($role == 'admin' || $role == 'kasir'): ?>
        <a href="transaksi.php" class="menu-item">Entri Transaksi</a>
    <?php endif; ?>

    <a href="laporan.php" class="menu-item">Generate Laporan</a>
    
    <a href="logout.php" class="menu-item logout">Logout</a>
</div>

<div class="content">
    <div class="header">
        <h2>Selamat Datang Kembali!</h2>
        <p>Akses Anda saat ini: <span style="background: #6c5ce7; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;"><?php echo strtoupper($role); ?></span></p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px;">
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.2);">
            <small>Total Transaksi</small>
            <h2 style="margin: 5px 0;">10</h2>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.2);">
            <small>Pelanggan Aktif</small>
            <h2 style="margin: 5px 0;">40</h2>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.2);">
            <small>Status Petugas</small>
            <h2 style="margin: 5px 0;">Aktif</h2>
        </div>
    </div>
</div>

</body>
</html>