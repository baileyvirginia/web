<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['role'])) {
    header("location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Laundry</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            padding: 40px 20px; 
            /* Background gradasi serasi dengan Dashboard & Login */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh;
            margin: 0;
        }

        .container { 
            background: rgba(255, 255, 255, 0.95); /* Putih bersih sedikit transparan */
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            max-width: 1000px;
            margin: auto;
        }

        h2 { 
            color: #4834d4; 
            margin-top: 0;
            text-align: center;
            letter-spacing: 1px;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 25px; 
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th { 
            background-color: #6c5ce7; 
            color: white; 
            padding: 15px;
            text-align: left;
            font-size: 14px;
            text-transform: uppercase;
        }

        td { 
            padding: 12px 15px; 
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #2d3436;
        }

        tr:hover { background-color: #f9f9ff; }

        .no-print { 
            margin-bottom: 30px; 
            display: flex;
            justify-content: space-between;
        }

        .btn { 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 10px; 
            display: inline-block; 
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-print { 
            background: #27ae60; 
            color: white; 
            border: none; 
            cursor: pointer; 
            box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3);
        }

        .btn-print:hover { background: #219150; transform: translateY(-2px); }

        .btn-back { 
            background: #ffffff; 
            color: #764ba2; 
            border: 2px solid #764ba2;
        }

        .btn-back:hover { background: #764ba2; color: white; transform: translateY(-2px); }

        /* Badge Status */
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            background: #f1f2f6;
            color: #2f3542;
            font-weight: bold;
            font-size: 11px;
        }

        /* Gaya untuk Print */
        @media print { 
            .no-print { display: none; } 
            body { background: white; padding: 0; } 
            .container { box-shadow: none; border: none; width: 100%; max-width: 100%; }
            th { background-color: #eee !important; color: black !important; border: 1px solid #ddd; }
            td { border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Laporan Transaksi Laundry</h2>

    <div class="no-print">
        <a href="dashboard.php" class="btn btn-back">← Kembali ke Dashboard</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Laporan</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Invoice</th>
                <th>Pelanggan</th>
                <th>Tgl Masuk</th>
                <th>Batas Waktu</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            
            // Query sesuai dengan struktur database Anda (member/tb_member)
            $query = "SELECT transaksi.*, member.nama AS nama_pelanggan 
                      FROM transaksi 
                      INNER JOIN member ON transaksi.id_member = member.id";
            
            $result = mysqli_query($conn, $query);

            if (!$result) {
                $query_alt = "SELECT transaksi.*, member.nama AS nama_pelanggan 
                              FROM transaksi 
                              INNER JOIN member ON transaksi.id_member = member.id_member";
                $result = mysqli_query($conn, $query_alt);
            }

            if (!$result) {
                echo "<tr><td colspan='7' style='color:red; text-align:center;'>
                        <b>Gagal memuat data:</b> " . mysqli_error($conn) . "
                      </td></tr>";
            } elseif (mysqli_num_rows($result) == 0) {
                echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data transaksi.</td></tr>";
            } else {
                while($d = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><b style="color: #6c5ce7;"><?= $d['kode_invoice']; ?></b></td>
                    <td><?= $d['nama_pelanggan']; ?></td>
                    <td><?= $d['tgl']; ?></td>
                    <td><?= $d['batas_waktu']; ?></td>
                    <td>
                        <span class="badge-status">
                            <?= strtoupper($d['status']); ?>
                        </span>
                    </td>
                    <td>
    <?php if($d['dibayar'] == 'belum_dibayar'){ ?>
        <a href="bayar.php?id=<?= $d['id_transaksi']; ?>"
           onclick="return confirm('Yakin pembayaran sudah diterima?')"
           class="btn btn-print"
           style="padding:5px 10px; font-size:12px;">
            Bayar
        </a>
    <?php } else { ?>
        <b style="color:#27ae60;">
            SUDAH DIBAYAR
        </b>
    <?php } ?>
</td>
                </tr>
                <?php 
                }
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>