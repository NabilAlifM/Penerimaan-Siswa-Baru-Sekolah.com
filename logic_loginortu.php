<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token_wali']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT token_wali, nama_wali, password FROM walimurid WHERE token_wali = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password === $row['password']) {
            $_SESSION['role'] = 'walimurid';
            $_SESSION['token_wali'] = $row['token_wali'];
            $_SESSION['nama'] = $row['nama_wali'];

            header("Location: index.php?login=success");
            exit;
        } else {
            header("Location: loginortu.html?error=wrongpass");
            exit;
        }
    } else {
        header("Location: loginortu.html?error=notfound");
        exit;
    }
}

?>
