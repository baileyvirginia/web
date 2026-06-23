<?php
session_start();
include 'koneksi.php';

// ====================================
// PROTEKSI HALAMAN
// Hanya admin yang boleh kelola petugas
// ====================================
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

// ====================================
// PASSWORD DEFAULT PETUGAS
// ====================================
$password_default = md5('petugas123');

// ====================================
// PROSES SIMPAN & UPDATE
// ====================================
if (isset($_POST['simpan'])) {

    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role     = 'petugas';

    // ====================================
    // UPDATE DATA
    // ====================================
    if (isset($_POST['id_petugas']) && !empty($_POST['id_petugas'])) {

        $id = $_POST['id_petugas'];

        $query = "UPDATE petugas 
                  SET nama_petugas='$nama',
                      username='$username'
                  WHERE id_petugas='$id'";

        $msg = "Data petugas berhasil diupdate";

    } else {

        // ====================================
        // TAMBAH PETUGAS BARU
        // Password otomatis: petugas123
        // ====================================
        $query = "INSERT INTO petugas 
                  (nama_petugas, username, password, role)
                  VALUES
                  ('$nama', '$username', '$password_default', '$role')";

        $msg = "Petugas baru berhasil ditambahkan";
    }

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('$msg'); window.location='petugas.php';</script>";
    }
}

// ====================================
// PROSES HAPUS
// ====================================
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    if (mysqli_query($conn, "DELETE FROM petugas WHERE id_petugas='$id'")) {
        header("Location: petugas.php");
    }
}

// ====================================
// AMBIL DATA UNTUK EDIT
// ====================================
$edit = null;

if (isset($_GET['edit'])) {

    $id_edit = $_GET['edit'];

    $q_edit = mysqli_query($conn, "SELECT * FROM petugas WHERE id_petugas='$id_edit'");

    $edit = mysqli_fetch_assoc($q_edit);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Petugas</title>

    <style>

        body{
            font-family:'Segoe UI',sans-serif;
            background:linear-gradient(135deg,#667eea,#764ba2);
            margin:0;
            padding:20px;
            min-height:100vh;
        }

        .container{
            max-width:1000px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
        }

        h2{
            text-align:center;
            color:#4b33a8;
            margin-top:0;
        }

        .back-link{
            text-decoration:none;
            color:#764ba2;
            font-weight:bold;
            display:inline-block;
            margin-bottom:20px;
        }

        .form-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:15px;
            margin-bottom:20px;
        }

        label{
            display:block;
            font-size:13px;
            font-weight:bold;
            margin-bottom:5px;
            color:#555;
        }

        input{
            width:100%;
            padding:10px;
            border:1px solid #ddd;
            border-radius:8px;
            box-sizing:border-box;
            background:#f9f9ff;
        }

        .btn{
            padding:12px 20px;
            border:none;
            border-radius:8px;
            cursor:pointer;
            font-weight:bold;
            transition:0.3s;
            text-decoration:none;
        }

        .btn-save{
            background:#6c5ce7;
            color:white;
            width:100%;
            font-size:16px;
        }

        .btn-save:hover{
            background:#5f27cd;
        }

        .btn-edit{
            background:#ffeaa7;
            color:#d35400;
            padding:5px 10px;
            font-size:12px;
        }

        .btn-delete{
            background:#ff7675;
            color:white;
            padding:5px 10px;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:30px;
        }

        th{
            background:#6c5ce7;
            color:white;
            padding:12px;
            text-align:left;
        }

        td{
            padding:12px;
            border-bottom:1px solid #eee;
            font-size:14px;
        }

        .info-password{
            background:#f1f2ff;
            border-left:5px solid #6c5ce7;
            padding:12px;
            margin-bottom:20px;
            border-radius:8px;
            color:#444;
        }

    </style>
</head>
<body>

<div class="container">

    <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>

    <h2>Manajemen Petugas</h2>

    <div class="info-password">
        Password default petugas adalah:
        <b>petugas123</b>
    </div>

    <form method="POST">

        <input type="hidden" name="id_petugas"
               value="<?= $edit ? $edit['id_petugas'] : ''; ?>">

        <div class="form-grid">

            <div>
                <label>Nama Petugas</label>

                <input type="text"
                       name="nama"
                       required
                       value="<?= $edit ? $edit['nama_petugas'] : ''; ?>"
                       placeholder="Masukkan nama petugas">
            </div>

            <div>
                <label>Username</label>

                <input type="text"
                       name="username"
                       required
                       value="<?= $edit ? $edit['username'] : ''; ?>"
                       placeholder="Masukkan username">
            </div>

        </div>

        <button type="submit"
                name="simpan"
                class="btn btn-save">

            <?= $edit ? 'UPDATE DATA PETUGAS' : 'TAMBAH PETUGAS' ?>

        </button>

    </form>

    <table>

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Petugas</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $no = 1;

        $query = mysqli_query($conn,
                 "SELECT * FROM petugas
                  WHERE role='petugas'
                  ORDER BY id_petugas DESC");

        while($d = mysqli_fetch_array($query)) :
        ?>

            <tr>

                <td><?= $no++; ?></td>

                <td>
                    <b><?= $d['nama_petugas']; ?></b>
                </td>

                <td><?= $d['username']; ?></td>

                <td><?= $d['role']; ?></td>

                <td>

                    <a href="petugas.php?edit=<?= $d['id_petugas']; ?>"
                       class="btn btn-edit">
                       Edit
                    </a>

                    <a href="petugas.php?hapus=<?= $d['id_petugas']; ?>"
                       class="btn btn-delete"
                       onclick="return confirm('Hapus petugas ini?')">

                       Hapus
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

</body>
</html>