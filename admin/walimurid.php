<?php
include 'koneksi.php';

// Session check
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: index.php"); 
    exit; 
}

// Helper query
function runQuery($sql){ 
    global $koneksi; 
    $r=$koneksi->query($sql); 
    if(!$r) die("Query error: ".$koneksi->error); 
    return $r; 
}

// Hapus
if(isset($_GET['delete'])){ 
    $t=$koneksi->real_escape_string($_GET['delete']); 
    runQuery("DELETE FROM walimurid WHERE token_wali='$t'"); 
    header("Location: walimurid.php"); 
    exit; 
}

// Simpan (Tambah / Edit)
if($_SERVER['REQUEST_METHOD']==='POST'){
    $t = $koneksi->real_escape_string(trim($_POST['token_wali'] ?? ''));
    $n = $koneksi->real_escape_string(trim($_POST['nama_wali'] ?? ''));
    $password = $koneksi->real_escape_string(trim($_POST['password'] ?? ''));
    $h = $koneksi->real_escape_string(trim($_POST['hubungan'] ?? ''));
    $is_edit = isset($_POST['edit_mode']) && $_POST['edit_mode'];

   if (!$is_edit && $t === '') {
    $res = runQuery("SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM walimurid");
    $row = $res->fetch_assoc();
    $next_id = $row['next_id'];
    $t = "WALI" . (5000 + $next_id);
}


    if($is_edit){
        runQuery("UPDATE walimurid SET 
            nama_wali='$n',
            password='$password',
            hubungan='$h'
            WHERE token_wali='$t'");
    } else {
        runQuery("INSERT INTO walimurid (token_wali,nama_wali,password,hubungan,waktu_registrasi) 
        VALUES('$t','$n','$password','$h',NOW())");
    }
    header("Location: walimurid.php"); 
    exit;
}

// Edit mode
$edit_data=null; 
if(isset($_GET['edit'])){ 
    $t=$koneksi->real_escape_string($_GET['edit']); 
    $edit_data=runQuery("SELECT * FROM walimurid WHERE token_wali='$t'")->fetch_assoc(); 
}

// Data all
$data=runQuery("SELECT * FROM walimurid ORDER BY id_seq ASC");

// Default token
$res = runQuery("SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM walimurid");
$row = $res->fetch_assoc();
$next_id = $row['next_id'];
$default_token = "WALI" . (1000 + $next_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Wali Murid</title>
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
          <img src="../images/Frame 1.png" alt="Logo" class="img-fluid" style="max-width: 120px;">
        </div>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pesertadidik.php"><i class="bi bi-people-fill"></i> Kelola Peserta Didik</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="walimurid.php"><i class="bi bi-person-badge"></i> Kelola Orang Tua/Wali</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="form_pendaftaran.php"><i class="bi bi-file-earmark-text"></i> Kelola Pendaftaran</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 ms-sm-auto">
      <!-- Header -->
      <div class="admin-header d-flex justify-content-between align-items-center py-3">
        <div class="admin-brand">Admin Panel - Kelola Wali Murid</div>
        <div class="d-flex align-items-center">
          <span class="me-3">Admin User</span>
          <div class="dropdown">
            <button class="btn btn-outline dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Konten utama -->
      <div class="main-content p-3">
        <h1 class="page-title">Kelola Orang Tua / Wali</h1>

        <!-- FORM -->
        <form method="post" class="card p-3 mb-4">
          <div class="row g-2">
            <div class="col-md-3">
              <label class="form-label small">Token Wali</label>
              <input type="text" name="token_wali" class="form-control"
                value="<?= htmlspecialchars($edit_data['token_wali'] ?? $default_token) ?>" 
                <?= $edit_data ? 'readonly' : '' ?>>
              <div class="form-text">Boleh dikosongkan untuk generate otomatis.</div>
            </div>
            <div class="col-md-3">
              <label class="form-label small">Nama Wali</label>
              <input type="text" name="nama_wali" class="form-control" required
                value="<?= htmlspecialchars($edit_data['nama_wali'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label small">Password</label>
              <input type="text" name="password" class="form-control"
                value="<?= htmlspecialchars($edit_data['password'] ?? '') ?>">
            </div>
            <div class="col-md-3">
  <label class="form-label small">Hubungan</label>
  <select name="hubungan" class="form-select" required>
      <option value="" disabled <?= !isset($edit_data['hubungan']) ? 'selected' : '' ?>>Pilih Hubungan</option>
      <option value="Ayah" <?= (isset($edit_data['hubungan']) && $edit_data['hubungan'] === 'Ayah') ? 'selected' : '' ?>>Ayah</option>
      <option value="Ibu" <?= (isset($edit_data['hubungan']) && $edit_data['hubungan'] === 'Ibu') ? 'selected' : '' ?>>Ibu</option>
      <option value="Wali" <?= (isset($edit_data['hubungan']) && $edit_data['hubungan'] === 'Wali') ? 'selected' : '' ?>>Wali</option>
  </select>
</div>

          </div>
          <input type="hidden" name="edit_mode" value="<?= $edit_data ? 1 : 0 ?>">
          <button class="btn btn-primary mt-3 ms-2"><?= $edit_data ? 'Update' : 'Tambah' ?></button>
          <?php if ($edit_data): ?>
            <a href="walimurid.php" class="btn btn-outline-secondary mt-3 ms-2">Batal</a>
          <?php endif; ?>
        </form>

        <!-- TABEL -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th><th>Token</th><th>Nama</th><th>Password</th><th>Hubungan</th><th>Registrasi</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php while($r=$data->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($r['id_seq']) ?></td>
                <td><?= htmlspecialchars($r['token_wali']) ?></td>
                <td><?= htmlspecialchars($r['nama_wali']) ?></td>
                <td><?= htmlspecialchars($r['password']) ?></td>
                <td><?= htmlspecialchars($r['hubungan']) ?></td>
                <td><?= htmlspecialchars($r['waktu_registrasi']) ?></td>
                <td>
                  <a href="?edit=<?= urlencode($r['token_wali']) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                  <a href="?delete=<?= urlencode($r['token_wali']) ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
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
