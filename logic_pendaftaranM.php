<?php
session_start();
include "koneksi.php";

$peserta_token = $_POST['pesertadidik_token_peserta'];
$wali_token    = $_POST['walimurid_token_wali'];
$nama_siswa    = $_POST['nama_siswa'];
$asal_sekolah  = $_POST['asal_sekolah'];
$rata2         = $_POST['rata_rata_nilai'];
$status        = $_POST['status_seleksi'] ?? 'Cadangan';

// next_id form
$res = mysqli_query($conn, "SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM form_pendaftaran");
$row = mysqli_fetch_assoc($res);
$next_id = $row['next_id'];

$token_daftar = "CALON" . (1000 + $next_id);

// Foto upload
$foto_file = null;
if (isset($_FILES['foto_siswa']) && $_FILES['foto_siswa']['error'] == UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['foto_siswa']['name'], PATHINFO_EXTENSION);
    $foto_file = "foto_" . time() . "." . $ext;
    move_uploaded_file($_FILES['foto_siswa']['tmp_name'], __DIR__."/../images/Muka/".$foto_file);
}

$foto_val = $foto_file ? "'$foto_file'" : "NULL";

$sql = "INSERT INTO form_pendaftaran 
        (token_pendaftaran, pesertadidik_token_peserta, walimurid_token_wali, nama_siswa, foto_siswa, asal_sekolah, rata_rata_nilai, status_seleksi, tanggal_daftar)
        VALUES (
            '$token_daftar',
            '$peserta_token',
            '$wali_token',
            '$nama_siswa',
            $foto_val,
            '$asal_sekolah',
            '$rata2',
            '$status',
            CURDATE()
        )";

if (mysqli_query($conn, $sql)) {
    $_SESSION['token_daftar'] = $token_daftar;
    header("Location: sukses_daftar.php");
    exit;
} else {
    echo "Gagal daftar: " . mysqli_error($conn);
}
