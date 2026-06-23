<?php
session_start();
include 'koneksi.php';

// CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

// TAMBAH USER
if (isset($_POST['simpan'])) {
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_outlet  = mysqli_real_escape_string($conn, $_POST['id_outlet']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']);

    $cek = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan');</script>";
    } else {
        // Menggunakan tabel 'user' sesuai kodinganmu yang lain
        $insert = mysqli_query($conn, "
            INSERT INTO user (nama, username, password, id_outlet, role)
            VALUES ('$nama','$username','$password','$id_outlet','$role')
        ");

        if ($insert) {
            echo "<script>alert('User berhasil ditambahkan'); window.location='pengguna.php';</script>";
        } else {
            die('INSERT ERROR : ' . mysqli_error($conn));
        }
    }
}

// UPDATE USER
if (isset($_POST['update'])) {
    $id         = mysqli_real_escape_string($conn, $_POST['id']);
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $id_outlet  = mysqli_real_escape_string($conn, $_POST['id_outlet']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']);

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE user SET nama='$nama', username='$username', password='$password', id_outlet='$id_outlet', role='$role' WHERE id_user='$id'");
    } else {
        $update = mysqli_query($conn, "UPDATE user SET nama='$nama', username='$username', id_outlet='$id_outlet', role='$role' WHERE id_user='$id'");
    }

    if ($update) {
        echo "<script>alert('User berhasil diupdate'); window.location='pengguna.php';</script>";
    } else {
        die('UPDATE ERROR : ' . mysqli_error($conn));
    }
}

// HAPUS USER
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    // Pastikan menggunakan id_user jika itu nama kolom di tabel user
    $hapus = mysqli_query($conn, "DELETE FROM user WHERE id_user='$id'");

    if ($hapus) {
        echo "<script>alert('User berhasil dihapus'); window.location='pengguna.php';</script>";
    } else {
        die('DELETE ERROR : ' . mysqli_error($conn));
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($conn, $_GET['edit']);
    $q_edit = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id_edit'");
    if ($q_edit && mysqli_num_rows($q_edit) > 0) { 
        $edit = mysqli_fetch_assoc($q_edit); 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna Laundry</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; margin: 0; padding: 40px 20px; }
        .container { background: rgba(255, 255, 255, 0.95); max-width: 1000px; margin: auto; padding: 30px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
        h2 { color: #4834d4; text-align: center; margin-top: 10px; }
        .btn-back { text-decoration: none; color: #764ba2; font-weight: bold; display: inline-block; margin-bottom: 20px; }
        label { font-weight: bold; color: #636e72; font-size: 14px; }
        input, select { width: 100%; padding: 12px; margin: 8px 0 15px 0; border-radius: 10px; border: 1px solid #ddd; box-sizing: border-box; background: #f9f9ff; }
        .btn { padding: 10px 15px; border: none; border-radius: 8px; text-decoration: none; cursor: pointer; color: white; font-weight: bold; transition: 0.3s; }
        .save { background: #6c5ce7; width: 100%; font-size: 16px; margin-top: 10px; }
        .edit-btn { background: #fab1a0; color: #d63031; font-size: 12px; padding: 5px 10px; border-radius: 5px; text-decoration: none;}
        .hapus-btn { background: #ff7675; font-size: 12px; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;}
        table { width: 100%; border-collapse: collapse; margin-top: 30px; background: white; border-radius: 12px; overflow: hidden; }
        table th { background: #6c5ce7; color: white; padding: 15px; text-transform: uppercase; font-size: 13px; }
        table td { border-bottom: 1px solid #eee; padding: 12px; font-size: 14px; }
        .role-badge { background: #eee; padding: 4px 8px; border-radius: 20px; font-size: 11px; color: #2d3436; }
    </style>
</head>
<body>
<div class="container">
    <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    <h2>Manajemen Pengguna</h2>

    <form method="POST">
        <?php if($edit): ?>
            <input type="hidden" name="id" value="<?= $edit['id_user']; ?>">
        <?php endif; ?>

        <label>Nama Lengkap</label>
        <input type="text" name="nama" required value="<?= $edit ? $edit['nama'] : ''; ?>">

        <label>Username</label>
        <input type="text" name="username" required value="<?= $edit ? $edit['username'] : ''; ?>">

        <label>Password</label>
        <input type="password" name="password" placeholder="<?= $edit ? 'Kosongkan jika tidak diubah' : 'Masukkan password'; ?>">

        <label>Outlet</label>
        <select name="id_outlet" required>
            <option value="">-- Pilih Outlet --</option>
            <?php
            $outlet = mysqli_query($conn, "SELECT * FROM outlet");
            while($o = mysqli_fetch_assoc($outlet)) :
                // Gunakan nama kolom yang benar dari tabel outlet (misal: id atau id_outlet)
                $id_o = isset($o['id']) ? $o['id'] : $o['id_outlet'];
                $selected = ($edit && $edit['id_outlet'] == $id_o) ? 'selected' : '';
            ?>
                <option value="<?= $id_o; ?>" <?= $selected; ?>><?= $o['nama']; ?></option>
            <?php endwhile; ?>
        </select>

        <label>Role</label>
        <select name="role" required>
            <option value="admin" <?= ($edit && $edit['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="kasir" <?= ($edit && $edit['role'] == 'kasir') ? 'selected' : ''; ?>>Kasir</option>
            <option value="owner" <?= ($edit && $edit['role'] == 'owner') ? 'selected' : ''; ?>>Owner</option>
        </select>

        <button type="submit" name="<?= $edit ? 'update' : 'simpan'; ?>" class="btn save">
            <?= $edit ? 'UPDATE DATA PENGGUNA' : 'SIMPAN PENGGUNA BARU'; ?>
        </button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Outlet</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // PERBAIKAN QUERY JOIN: 
        // Saya mencoba mengganti outlet.id menjadi outlet.id_outlet jika error berlanjut
        $query = mysqli_query($conn, "
            SELECT user.*, outlet.nama AS nama_outlet 
            FROM user 
            LEFT JOIN outlet ON user.id_outlet = outlet.id 
        ");

        // Jika masih error, otomatis coba pakai id_outlet
        if (!$query) {
             $query = mysqli_query($conn, "
                SELECT user.*, outlet.nama AS nama_outlet 
                FROM user 
                LEFT JOIN outlet ON user.id_outlet = outlet.id_outlet
            ");
        }
        
        if (!$query) {
            echo "<tr><td colspan='6' style='color:red; text-align:center;'>Query Error: " . mysqli_error($conn) . "</td></tr>";
        } else {
            $no = 1;
            while($d = mysqli_fetch_assoc($query)) :
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><b><?= $d['nama']; ?></b></td>
                    <td><?= $d['username']; ?></td>
                    <td><?= $d['nama_outlet'] ? $d['nama_outlet'] : '<i style="color:red">Tanpa Outlet</i>'; ?></td>
                    <td><span class="role-badge"><?= strtoupper($d['role']); ?></span></td>
                    <td>
                        <a href="pengguna.php?edit=<?= $d['id_user']; ?>" class="edit-btn">Edit</a>
                        <a href="pengguna.php?hapus=<?= $d['id_user']; ?>" class="hapus-btn" onclick="return confirm('Yakin hapus user?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; 
        } ?>
        </tbody>
    </table>
</div>
</body>
</html>