<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'owner') {
    header("Location: dashboard.php");
    exit;
}

// ====================================
// SIMPAN & UPDATE DATA
// ====================================
if (isset($_POST['simpan'])) {

    $nama           = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat         = mysqli_real_escape_string($conn, $_POST['alamat']);
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $tlp            = mysqli_real_escape_string($conn, $_POST['tlp']);

    // UPDATE
    if (!empty($_POST['id_member_hidden'])) {

        $id = $_POST['id_member_hidden'];

        $query = mysqli_query($conn, "
            UPDATE member 
            SET 
                nama='$nama',
                alamat='$alamat',
                jenis_kelamin='$jenis_kelamin',
                tlp='$tlp'
            WHERE id_member='$id'
        ");

    } else {

        // INSERT
        $query = mysqli_query($conn, "
            INSERT INTO member (nama, alamat, jenis_kelamin, tlp)
            VALUES ('$nama', '$alamat', '$jenis_kelamin', '$tlp')
        ");
    }

    if ($query) {

        echo "
        <script>
            alert('Data berhasil disimpan');
            window.location='member.php';
        </script>
        ";

    } else {

        echo "Error : " . mysqli_error($conn);
    }
}

// ====================================
// HAPUS DATA
// ====================================
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM member 
        WHERE id_member='$id'
    ");

    header("Location: member.php");
    exit;
}

// ====================================
// EDIT DATA
// ====================================
$edit = null;

if (isset($_GET['edit'])) {

    $id_edit = $_GET['edit'];

    $q_edit = mysqli_query($conn, "
        SELECT * FROM member 
        WHERE id_member='$id_edit'
    ");

    $edit = mysqli_fetch_assoc($q_edit);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Data Member Laundry</title>

    <style>

        body{
            font-family:'Segoe UI',sans-serif;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            min-height:100vh;
            margin:0;
            padding:40px 20px;
        }

        .container{
            background:rgba(255,255,255,0.95);
            max-width:950px;
            margin:auto;
            padding:30px;
            border-radius:20px;
            box-shadow:0 15px 35px rgba(0,0,0,0.2);
        }

        h2{
            color:#4834d4;
            text-align:center;
            margin-top:0;
        }

        .btn-back{
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
        }

        label{
            display:block;
            font-size:13px;
            font-weight:bold;
            color:#636e72;
            margin-top:10px;
        }

        input,select{
            width:100%;
            padding:12px;
            margin-top:5px;
            border:1px solid #ddd;
            border-radius:10px;
            box-sizing:border-box;
            background:#f9f9ff;
        }

        .btn-save{
            background:#6c5ce7;
            color:white;
            border:none;
            padding:14px;
            border-radius:10px;
            cursor:pointer;
            width:100%;
            font-weight:bold;
            font-size:15px;
            margin-top:20px;
        }

        .btn-save:hover{
            background:#5f27cd;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:30px;
            background:white;
            border-radius:12px;
            overflow:hidden;
        }

        th{
            background:#6c5ce7;
            color:white;
            padding:15px;
            text-align:left;
            font-size:13px;
        }

        td{
            padding:15px;
            border-bottom:1px solid #eee;
        }

        tr:hover{
            background:#f8f9ff;
        }

        .btn-action{
            text-decoration:none;
            font-weight:bold;
            padding:6px 12px;
            border-radius:6px;
            font-size:12px;
        }

        .edit{
            color:#4834d4;
            border:1px solid #4834d4;
        }

        .edit:hover{
            background:#4834d4;
            color:white;
        }

        .hapus{
            color:#ff7675;
            border:1px solid #ff7675;
        }

        .hapus:hover{
            background:#ff7675;
            color:white;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="dashboard.php" class="btn-back">
        ← Kembali ke Dashboard
    </a>

    <h2>
        <?= $edit ? 'Edit Data Member' : 'Registrasi Member Laundry'; ?>
    </h2>

    <form method="POST">

        <input 
            type="hidden"
            name="id_member_hidden"
            value="<?= $edit ? $edit['id_member'] : ''; ?>"
        >

        <div class="form-grid">

            <div>
                <label>Nama Lengkap</label>

                <input
                    type="text"
                    name="nama"
                    required
                    value="<?= $edit ? $edit['nama'] : ''; ?>"
                >
            </div>

            <div>
                <label>Nomor Telepon</label>

                <input
                    type="text"
                    name="tlp"
                    required
                    value="<?= $edit ? $edit['tlp'] : ''; ?>"
                >
            </div>

        </div>

        <div class="form-grid">

            <div>

                <label>Jenis Kelamin</label>

                <select name="jenis_kelamin" required>

                    <option value="L"
                        <?= ($edit && $edit['jenis_kelamin']=='L') ? 'selected' : ''; ?>>
                        Laki-laki
                    </option>

                    <option value="P"
                        <?= ($edit && $edit['jenis_kelamin']=='P') ? 'selected' : ''; ?>>
                        Perempuan
                    </option>

                </select>

            </div>

            <div>

                <label>Alamat</label>

                <input
                    type="text"
                    name="alamat"
                    required
                    value="<?= $edit ? $edit['alamat'] : ''; ?>"
                >

            </div>

        </div>

        <button type="submit" name="simpan" class="btn-save">

            <?= $edit ? '🔄 UPDATE MEMBER' : '➕ TAMBAH MEMBER'; ?>

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

        $query = mysqli_query($conn, "
            SELECT * FROM member
            ORDER BY id_member DESC
        ");

        while($d = mysqli_fetch_array($query)){

        ?>

            <tr>

                <td><?= $no++; ?></td>

                <td>
                    <b><?= $d['nama']; ?></b>
                </td>

                <td><?= $d['tlp']; ?></td>

                <td><?= $d['jenis_kelamin']; ?></td>

                <td><?= $d['alamat']; ?></td>

                <td>

                    <a
                        href="member.php?edit=<?= $d['id_member']; ?>"
                        class="btn-action edit"
                    >
                        Edit
                    </a>

                    <a
                        href="member.php?hapus=<?= $d['id_member']; ?>"
                        class="btn-action hapus"
                        onclick="return confirm('Hapus data member ini?')"
                    >
                        Hapus
                    </a>

                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

</body>
</html>