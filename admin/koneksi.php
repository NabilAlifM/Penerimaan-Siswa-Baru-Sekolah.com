<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Mulai session di sini

$host = 'localhost';
$username = 'root';
$password = ''; 
$dbname   = 'db_pendaftaran_siswa'; 


$koneksi = new mysqli($host, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

function query($sql) {
    global $koneksi;
    $result = $koneksi->query($sql);
    if (!$result) die("Query error: " . $koneksi->error);
    return $result;
}

function loginAdmin($username, $password) {
    if ($username === 'admin321' && $password === 'admin321') {
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: index.php");
        exit;
    }
}
?>