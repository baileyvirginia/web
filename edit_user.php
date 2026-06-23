<?php
session_start();
include 'koneksi.php';

// cek id
if (!isset($_GET['id'])) {

    header("location:tambah_user.php");
    exit;
}

$id = $_GET['id'];

// ambil data user
$query = mysqli_query($conn,"
    SELECT * FROM user
    WHERE id_user='$id'
");

$data = mysqli_fetch_assoc($query);

// update data
if (isset($_POST['update'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $update = mysqli_query($conn,"
        UPDATE user SET
        nama='$nama',
        username='$username',
        role='$role'
        WHERE id_user='$id'
    ");

    if($update){

        echo "
        <script>
            alert('Data berhasil diupdate');
            window.location='tambah_user.php';
        </script>
        ";

    } else {

        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit User</title>

<style>

body{
    font-family:Segoe UI;
    background:#1a1c2d;
    color:white;
    padding:40px;
}

.box{
    max-width:500px;
    margin:auto;
    background:#242745;
    padding:30px;
    border-radius:15px;
}

input,
select{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:20px;
    border:none;
    border-radius:8px;
    background:#2f335d;
    color:white;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#f1c40f;
    font-weight:bold;
    cursor:pointer;
}

a{
    color:white;
    text-decoration:none;
}

</style>

</head>

<body>

<div class="box">

    <h2>Edit User</h2>

    <form method="POST">

        <label>Nama</label>

        <input
            type="text"
            name="nama"
            value="<?= $data['nama']; ?>"
            required
        >

        <label>Username</label>

        <input
            type="text"
            name="username"
            value="<?= $data['username']; ?>"
            required
        >

        <label>Role</label>

        <select name="role">

            <option value="admin"
            <?= $data['role']=='admin' ? 'selected' : ''; ?>>
                Admin
            </option>

            <option value="kasir"
            <?= $data['role']=='kasir' ? 'selected' : ''; ?>>
                Kasir
            </option>

            <option value="owner"
            <?= $data['role']=='owner' ? 'selected' : ''; ?>>
                Owner
            </option>

        </select>

        <button type="submit" name="update">
            Update User
        </button>

    </form>

    <br>

    <a href="tambah_user.php">
        ← Kembali
    </a>

</div>

</body>
</html>