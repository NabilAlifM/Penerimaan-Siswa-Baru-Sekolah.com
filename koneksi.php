<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Kosongkan jika tidak ada password
$db   = 'db_pendaftaran_siswa'; // Ganti sesuai nama database kamu di localhost

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>