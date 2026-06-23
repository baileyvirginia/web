<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("location:dashboard.php");
    exit;
}

// Tambah Paket
if (isset($_POST['simpan'])) {

    $id_outlet  = $_SESSION['id_outlet'];
    $jenis      = $_POST['jenis'];
    $nama_paket = $_POST['nama_paket'];
    $harga      = $_POST['harga'];

    mysqli_query($conn, "INSERT INTO paket (id_outlet, jenis, nama_paket, harga) 
    VALUES ('$id_outlet', '$jenis', '$nama_paket', '$harga')");

    header("location:paket.php");
    exit;
}

// Hapus Paket
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM paket WHERE id_paket='$id'");

    header("location:paket.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Paket Laundry</title>

    <style>

        body{
            font-family:'Segoe UI',sans-serif;
            padding:40px 20px;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            min-height:100vh;
            margin:0;
            color:#2d3436;
        }

        .card{
            background:rgba(255,255,255,0.95);
            padding:30px;
            border-radius:20px;
            box-shadow:0 15px 35px rgba(0,0,0,0.2);
            max-width:900px;
            margin:auto;
        }

        h2{
            color:#4834d4;
            margin-top:10px;
            text-align:center;
        }

        .btn-back{
            text-decoration:none;
            color:#764ba2;
            font-weight:bold;
            display:inline-block;
            margin-bottom:20px;
            transition:0.3s;
        }

        .btn-back:hover{
            transform:translateX(-5px);
        }

        label{
            font-weight:bold;
            font-size:14px;
            color:#636e72;
        }

        input,select{
            width:100%;
            padding:12px;
            margin:8px 0 15px 0;
            border:1px solid #ddd;
            border-radius:10px;
            box-sizing:border-box;
            background:#f9f9ff;
            font-size:14px;
            transition:0.3s;
        }

        input:focus,
        select:focus{
            outline:none;
            border-color:#6c5ce7;
            box-shadow:0 0 8px rgba(108,92,231,0.2);
        }

        .btn-simpan{
            background:#6c5ce7;
            color:white;
            border:none;
            padding:14px;
            border-radius:10px;
            cursor:pointer;
            width:100%;
            font-weight:bold;
            font-size:16px;
            transition:0.3s;
            margin-top:10px;
        }

        .btn-simpan:hover{
            background:#5f27cd;
            box-shadow:0 5px 15px rgba(108,92,231,0.4);
            transform:translateY(-2px);
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
            font-size:14px;
            text-transform:uppercase;
        }

        td{
            border-bottom:1px solid #eee;
            padding:15px;
            font-size:14px;
        }

        tr:hover{
            background-color:#f8f9ff;
        }

        .btn-hapus{
            color:#ff7675;
            text-decoration:none;
            font-weight:bold;
            padding:5px 10px;
            border:1px solid #ff7675;
            border-radius:6px;
            transition:0.3s;
        }

        .btn-hapus:hover{
            background:#ff7675;
            color:white;
        }

        .harga-teks{
            font-weight:bold;
            color:#27ae60;
        }

    </style>

</head>

<body>

<div class="card">

    <a href="dashboard.php" class="btn-back">
        ← Kembali ke Dashboard
    </a>

    <h2>Manajemen Paket Cucian</h2>

    <form method="POST">

        <label>Jenis Paket</label>

        <select name="jenis" required>
            <option value="kiloan">Kiloan</option>
            <option value="selimut">Selimut</option>
            <option value="bed_cover">Bed Cover</option>
            <option value="kaos">Kaos</option>
            <option value="lain">Lainnya</option>
        </select>

        <label>Nama Paket</label>

        <input 
            type="text" 
            name="nama_paket" 
            placeholder="Contoh: Cuci Kering Express"
            required
        >

        <label>Harga (Rp)</label>

        <input 
            type="number" 
            name="harga" 
            placeholder="Contoh: 10000"
            required
        >

        <button type="submit" name="simpan" class="btn-simpan">
            💾 Simpan Paket Baru
        </button>

    </form>

    <table>

        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nama Paket</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php

        $no = 1;

        $id_outlet = $_SESSION['id_outlet'];

        $q = mysqli_query($conn, "SELECT * FROM paket WHERE id_outlet='$id_outlet'");

        while($d = mysqli_fetch_array($q)){

            echo "
            <tr>

                <td>$no</td>

                <td>
                    <span style='text-transform:capitalize;'>
                        {$d['jenis']}
                    </span>
                </td>

                <td>
                    <b>{$d['nama_paket']}</b>
                </td>

                <td class='harga-teks'>
                    Rp " . number_format($d['harga'],0,',','.') . "
                </td>

                <td>

                    <a 
                        href='paket.php?hapus={$d['id_paket']}'
                        class='btn-hapus'
                        onclick='return confirm(\"Hapus paket ini?\")'
                    >
                        Hapus
                    </a>

                </td>

            </tr>
            ";

            $no++;
        }

        ?>

        </tbody>

    </table>

</div>

</body>
</html>