<?php
session_start();
include 'koneksi.php';

// Cek parameter id
if (!isset($_GET['id'])) {
    header("Location: laporan.php");
    exit();
}

$id = intval($_GET['id']);

// Update status pembayaran
$query = "UPDATE transaksi 
          SET dibayar='dibayar' 
          WHERE id_transaksi=$id";

$update = mysqli_query($conn, $query);

if ($update) {
    header("Location: laporan.php");
    exit();
} else {
    die("Gagal update pembayaran: " . mysqli_error($conn));
}
?>