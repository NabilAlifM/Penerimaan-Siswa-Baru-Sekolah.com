<?php
include 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

function runQuery($sql) {
    global $koneksi;
    $result = $koneksi->query($sql);
    if (!$result) die("Query error: " . $koneksi->error);
    return $result; 
}

// Hapus
if (isset($_GET['delete'])) {
    $token = $koneksi->real_escape_string($_GET['delete']);
    runQuery("DELETE FROM pesertadidik WHERE token_peserta='$token'");
    runQuery("DELETE FROM form_pendaftaran WHERE pesertadidik_token_peserta='$token'");
    header("Location: pesertadidik.php"); exit;
}

// Simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $koneksi->real_escape_string(trim($_POST['token_peserta'] ?? ''));
    $nama = $koneksi->real_escape_string(trim($_POST['nama_peserta'] ?? ''));
    $nisn = $koneksi->real_escape_string(trim($_POST['nisn'] ?? ''));
    $password = $koneksi->real_escape_string(trim($_POST['password'] ?? ''));
    $asal_sekolah = $koneksi->real_escape_string(trim($_POST['asal_sekolah'] ?? ''));
    $is_edit = isset($_POST['edit_mode']) && $_POST['edit_mode'];

    if (!$is_edit && $token === '') {
    $res = runQuery("SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM pesertadidik");
    $row = $res->fetch_assoc();
    $next_id = $row['next_id'];
    $token = "SISWA" . (1000 + $next_id);
}


    if ($is_edit) {
        runQuery("UPDATE pesertadidik SET 
            nama_peserta='$nama',
            nisn='$nisn',
            password='$password',
            asal_sekolah='$asal_sekolah'
            WHERE token_peserta='$token'");
    } else {
        runQuery("INSERT INTO pesertadidik (token_peserta, nama_peserta, nisn, password, asal_sekolah, waktu_registrasi) 
            VALUES ('$token','$nama','$nisn','$password','$asal_sekolah',NOW())");
    }

    header("Location: pesertadidik.php"); exit;
}

// Edit mode
$edit_data = null;
if (isset($_GET['edit'])) {
    $token = $koneksi->real_escape_string($_GET['edit']);
    $edit_data = runQuery("SELECT * FROM pesertadidik WHERE token_peserta='$token'")->fetch_assoc();
}

$data = runQuery("SELECT * FROM pesertadidik ORDER BY id_seq ASC");
// Default token
$res = runQuery("SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM pesertadidik");
$row = $res->fetch_assoc();
$next_id = $row['next_id'];
$default_token = "SISWA" . (68740 + $next_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Peserta Didik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboard-style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 col-lg-2 d-md-block sidebar collapse">
      <div class="pt-3">
        <div class="text-center mb-4">
          <img src="../images/Frame 1.png" alt="Logo" class="img-fluid" style="max-width:120px;">
        </div>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link active" href="pesertadidik.php"><i class="bi bi-people-fill"></i> Kelola Peserta Didik</a></li>
          <li class="nav-item"><a class="nav-link" href="walimurid.php"><i class="bi bi-person-badge"></i> Kelola Orang Tua/Wali</a></li>
          <li class="nav-item"><a class="nav-link" href="form_pendaftaran.php"><i class="bi bi-file-earmark-text"></i> Kelola Pendaftaran</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>
    </div>

    <!-- Main -->
    <div class="col-md-10 ms-sm-auto">
      <div class="admin-header d-flex justify-content-between align-items-center py-3">
        <div class="admin-brand">Admin Panel - Pendaftaran Siswa</div>
        <div class="d-flex align-items-center">
          <span class="me-3">Admin User</span>
          <div class="dropdown">
            <button class="btn btn-outline dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i></button>
            <ul class="dropdown-menu"><li><a class="dropdown-item" href="logout.php">Logout</a></li></ul>
          </div>
        </div>
      </div>

      <div class="main-content p-3">
        <h1 class="page-title">Kelola Peserta Didik</h1>

        <!-- Form -->
        <form method="post" class="card p-3 mb-4">
          <div class="row g-2"> 
            <div class="col-md-3">
              <label class="form-label small">Token Peserta</label>
              <input type="text" name="token_peserta" class="form-control" 
                value="<?= htmlspecialchars($edit_data['token_peserta'] ?? $default_token) ?>" 
                <?= $edit_data ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
              <label class="form-label small">Nama Peserta</label>
              <input type="text" name="nama_peserta" class="form-control" required 
                value="<?= htmlspecialchars($edit_data['nama_peserta'] ?? '') ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label small">NISN</label>
              <input type="text" name="nisn" class="form-control" value="<?= htmlspecialchars($edit_data['nisn'] ?? '') ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label small">Password</label>
              <input type="text" name="password" class="form-control" value="<?= htmlspecialchars($edit_data['password'] ?? '') ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label small">Asal Sekolah</label>
              <input type="text" name="asal_sekolah" class="form-control" value="<?= htmlspecialchars($edit_data['asal_sekolah'] ?? '') ?>">
            </div>
          </div>
          <input type="hidden" name="edit_mode" value="<?= $edit_data ? 1 : 0 ?>">
          <button class="btn btn-primary mt-3 ms-2"><?= $edit_data ? 'Update' : 'Tambah' ?></button>
          <?php if ($edit_data): ?><a href="pesertadidik.php" class="btn btn-outline-secondary mt-3 ms-2">Batal</a><?php endif; ?>
        </form>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr><th>ID</th><th>Token</th><th>Nama</th><th>NISN</th><th>Password</th><th>Asal Sekolah</th><th>Registrasi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              <?php while($row = $data->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['id_seq']) ?></td>
                <td><?= htmlspecialchars($row['token_peserta']) ?></td>
                <td><?= htmlspecialchars($row['nama_peserta']) ?></td>
                <td><?= htmlspecialchars($row['nisn']) ?></td>
                <td><?= htmlspecialchars($row['password']) ?></td>
                <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
                <td><?= htmlspecialchars($row['waktu_registrasi']) ?></td>
                <td>
                  <a href="?edit=<?= urlencode($row['token_peserta']) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                  <a href="?delete=<?= urlencode($row['token_peserta']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
