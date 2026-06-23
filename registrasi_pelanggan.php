<?php
session_start();
include 'koneksi.php';

// Proteksi: Owner tidak boleh mengelola data member
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'owner') {
    header("Location: dashboard.php");
    exit;
}

// ====================================
// PROSES SIMPAN & UPDATE
// ====================================
if (isset($_POST['simpan'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tlp      = mysqli_real_escape_string($conn, $_POST['tlp']);

    if (isset($_POST['id_member']) && !empty($_POST['id_member'])) {
        // UPDATE
        $id = $_POST['id_member'];
        $query = "UPDATE member SET nama='$nama', alamat='$alamat', jenis_kelamin='$jenis_kelamin', tlp='$tlp' WHERE id='$id'";
        $msg = "Data pelanggan berhasil diupdate";
    } else {
        // INSERT
        $query = "INSERT INTO member (nama, alamat, jenis_kelamin, tlp) VALUES ('$nama', '$alamat', '$jenis_kelamin', '$tlp')";
        $msg = "Pelanggan baru berhasil didaftarkan";
    }

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('$msg'); window.location='member.php';</script> ";
    }
}

// ====================================
// PROSES HAPUS
// ====================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM member WHERE id='$id'")) {
        header("Location: member.php");
    }
}

// ====================================
// AMBIL DATA UNTUK EDIT
// ====================================
$edit = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $q_edit = mysqli_query($conn, "SELECT * FROM member WHERE id='$id_edit'");
    $edit = mysqli_fetch_assoc($q_edit);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pelanggan</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, #667eea, #764ba2); 
            min-height: 100vh; margin: 0; padding: 20px;
        }
        .container { 
            max-width: 1000px; margin: auto; background: white; 
            padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h2 { color: #4b33a8; margin-top: 0; text-align: center; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: bold; color: #555; margin-bottom: 5px; }
        input, select, textarea { 
            width: 100%; padding: 10px; border: 1px solid #ddd; 
            border-radius: 8px; box-sizing: border-box; background: #f9f9ff;
        }
        .btn { 
            padding: 12px 20px; border: none; border-radius: 8px; 
            cursor: pointer; font-weight: bold; transition: 0.3s; text-decoration: none;
        }
        .btn-save { background: #6c5ce7; color: white; width: 100%; font-size: 16px; }
        .btn-save:hover { background: #5f27cd; }
        .btn-edit { background: #fab1a0; color: #d63031; padding: 5px 10px; font-size: 12px; }
        .btn-delete { background: #ff7675; color: white; padding: 5px 10px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th { background: #6c5ce7; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        .back-link { display: inline-block; margin-bottom: 15px; color: #764ba2; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>
    <h2>Registrasi Pelanggan Baru</h2>

    <form method="POST">
        <input type="hidden" name="id_member" value="<?= $edit ? $edit['id'] : ''; ?>">
        
        <div class="form-grid">
            <div>
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required value="<?= $edit ? $edit['nama'] : ''; ?>" placeholder="Masukkan nama pelanggan">
            </div>
            <div>
                <label>Nomor Telepon</label>
                <input type="text" name="tlp" required value="<?= $edit ? $edit['tlp'] : ''; ?>" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div class="form-grid">
            <div>
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" required>
                    <option value="L" <?= ($edit && $edit['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="P" <?= ($edit && $edit['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div>
                <label>Alamat</label>
                <textarea name="alamat" rows="2" required placeholder="Alamat lengkap..."><?= $edit ? $edit['alamat'] : ''; ?></textarea>
            </div>
        </div>

        <button type="submit" name="simpan" class="btn btn-save">
            <?= $edit ? 'UPDATE DATA PELANGGAN' : 'DAFTARKAN PELANGGAN' ?>
        </button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>L/P</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $query = mysqli_query($conn, "SELECT * FROM member ORDER BY id_member DESC");
            while($d = mysqli_fetch_array($query)) :
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><b><?= $d['nama']; ?></b></td>
                <td><?= $d['tlp']; ?></td>
                <td><?= $d['jenis_kelamin']; ?></td>
                <td><?= $d['alamat']; ?></td>
                <td>
                    <a href="member.php?edit=<?= $d['id_member']; ?>" class="btn btn-edit">Edit</a>
                    <a href="member.php?hapus=<?= $d['id_member']; ?>" class="btn btn-delete" onclick="return confirm('Hapus data pelanggan ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>