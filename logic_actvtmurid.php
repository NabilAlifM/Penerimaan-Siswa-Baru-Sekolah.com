<?php
session_start();
include "koneksi.php";

// Ambil data dari form
$nama_peserta   = $_POST['nama_peserta'];
$nisn           = $_POST['nisn'];
$tempat_lahir   = $_POST['tempat_lahir'];
$tanggal_lahir  = $_POST['tahun'] . "-" . $_POST['bulan'] . "-" . $_POST['tanggal'];
$jenis_kelamin  = $_POST['jenis_kelamin'];
$no_telp        = $_POST['no_telp'];
$asal_sekolah   = $_POST['asal_sekolah'];
$password       = $_POST['password'];
$alamat         = $_POST['alamat'];
$walimurid_token_wali = $_POST['walimurid_token_wali'];

// Cari next id_seq
$res = mysqli_query($conn, "SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM pesertadidik");
$row = mysqli_fetch_assoc($res);
$next_id = $row['next_id'];

// Generate token
$token_peserta = "SISWA" . (1000 + $next_id);

// Insert
$sql = "INSERT INTO pesertadidik 
        (token_peserta, walimurid_token_wali, nama_peserta, password, alamat, no_telp, nisn, tempat_lahir, tanggal_lahir, jenis_kelamin, asal_sekolah, waktu_registrasi)
        VALUES (
            '$token_peserta',
            '$walimurid_token_wali',
            '$nama_peserta',
            '$password',
            '$alamat',
            '$no_telp',
            '$nisn',
            '$tempat_lahir',
            '$tanggal_lahir',
            '$jenis_kelamin',
            '$asal_sekolah',
            NOW()
        )";

if (mysqli_query($conn, $sql)) {
    $_SESSION['token_aktivasi'] = $token_peserta;
    header("Location: aktivasi.php");
    exit;
} else {
    echo "Gagal menyimpan data: " . mysqli_error($conn);
}
