<?php
session_start();
include "koneksi.php";

// Ambil data dari form
$nama_wali     = $_POST['nama_wali'];
$hubungan      = $_POST['hubungan'];
$no_telp_wali  = $_POST['no_telp_wali'];
$alamat        = $_POST['alamat'];
$password      = $_POST['password'];

// Cari next id_seq
$res = mysqli_query($conn, "SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM walimurid");
$row = mysqli_fetch_assoc($res);
$next_id = $row['next_id'];

// Generate token wali
$token_wali = "WALI" . (1000 + $next_id);

// Insert ke tabel walimurid
$sql = "INSERT INTO walimurid 
        (token_wali, nama_wali, hubungan, no_telp_wali, alamat, password, waktu_registrasi) 
        VALUES (?,?,?,?,?,?,NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $token_wali, $nama_wali, $hubungan, $no_telp_wali, $alamat, $password);

if ($stmt->execute()) {
    // Simpan token ke session
    $_SESSION['token_aktivasi_ortu'] = $token_wali;

    // Redirect ke halaman aktivasi ortu untuk munculkan popup
    header("Location: actvtortu.php");
    exit;
} else {
    echo "Gagal menyimpan data: " . $conn->error;
}
