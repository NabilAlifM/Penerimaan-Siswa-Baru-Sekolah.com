<?php

session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token_peserta']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT token_peserta, nama_peserta, password FROM pesertadidik WHERE token_peserta = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password === $row['password']) {
            $_SESSION['role'] = 'pesertadidik';
            $_SESSION['token_peserta'] = $row['token_peserta'];
            $_SESSION['nama'] = $row['nama_peserta'];

            header("Location: index.php?login=success");
            exit;
        } else {
            header("Location: masuk.html?error=wrongpass");
            exit;
        }
    } else {
        header("Location: masuk.html?error=notfound");
        exit;
    }
}
?>