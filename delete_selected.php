<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "disposisiuam";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

// Dapatkan ID yang dipilih dari parameter GET
$selectedIds = $_GET['ids'];

// Pisahkan ID yang dipilih menjadi array
$idArray = explode(",", $selectedIds);

// Loop melalui setiap ID yang dipilih dan hapus entri dari database
foreach ($idArray as $id) {
    $sql = "DELETE FROM mahasiswa WHERE id = '$id'";
    $q = mysqli_query($koneksi, $sql);
}

// Redirect kembali ke halaman index.php setelah menghapus
header("Location: index.php");
exit();
?>