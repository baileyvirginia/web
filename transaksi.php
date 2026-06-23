<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] == 'owner') { header("location:dashboard.php"); exit; }

if (isset($_POST['simpan'])) {
    $id_outlet = $_SESSION['id_outlet'];
    $kode_invoice = "INV" . date("YmdHis");
    $id_member = $_POST['id_member'];
    $tgl = date("Y-m-d H:i:s");
    $batas_waktu = $_POST['batas_waktu'];
    $biaya_tambahan = $_POST['biaya_tambahan'];
    $diskon = $_POST['diskon'];
    $pajak = $_POST['pajak'];
    $status = "baru";
    $dibayar = "belum_dibayar";
    $id_user = $_SESSION['id_user'];

    $sql = "INSERT INTO transaksi (id_outlet, kode_invoice, id_member, tgl, batas_waktu, biaya_tambahan, diskon, pajak, status, dibayar, id_user) 
            VALUES ('$id_outlet', '$kode_invoice', '$id_member', '$tgl', '$batas_waktu', '$biaya_tambahan', '$diskon', '$pajak', '$status', '$dibayar', '$id_user')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Transaksi Berhasil'); window.location='laporan.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Baru</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea, #764ba2); min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 20px; width: 100%; max-width: 500px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
        h2 { color: #4b33a8; text-align: center; margin-bottom: 20px; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #666; font-size: 14px; }
        input, select { width: 100%; padding: 12px; margin-top: 5px; border-radius: 10px; border: 1px solid #ddd; box-sizing: border-box; background: #f9f9ff; }
        .btn-submit { width: 100%; padding: 15px; background: #6c5ce7; color: white; border: none; border-radius: 10px; margin-top: 25px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-submit:hover { background: #5f27cd; transform: translateY(-2px); }
    </style>
</head>
<body>
<div class="card">
    <a href="dashboard.php" style="text-decoration:none; color:#764ba2; font-weight:bold;">← Kembali</a>
    <h2>Entri Transaksi</h2>
    <form method="POST">
        <label>Pilih Pelanggan (Member)</label>
        <select name="id_member" required>
            <option value="">-- Pilih Member --</option>
            <?php 
            // Ambil data dari tabel member
            $members = mysqli_query($conn, "SELECT * FROM member");
            while($m = mysqli_fetch_array($members)) {
                // SESUAIKAN: Ganti $m[0] dengan $m['id_member'] jika kolomnya bernama id_member
                echo "<option value='".$m[0]."'>".$m['nama']."</option>";
            }
            ?>
        </select>

        <label>Batas Waktu</label>
        <input type="datetime-local" name="batas_waktu" required>

        <label>Biaya Tambahan (Rp)</label>
        <input type="number" name="biaya_tambahan" value="0">

        <label>Diskon (%)</label>
        <input type="number" name="diskon" value="0">

        <label>Pajak (Rp)</label>
        <input type="number" name="pajak" value="0">

        <button type="submit" name="simpan" class="btn-submit">BUAT TRANSAKSI</button>
    </form>
</div>
</body>
</html>