<?php
include 'koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

function runQuery($sql) {
    global $koneksi;
    $res = $koneksi->query($sql);
    if (!$res) die("Query error: " . $koneksi->error);
    return $res;
}

function valid_image($file) {
    $allowed = ['jpg','jpeg','png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return false;
    if ($file['size'] > 2 * 1024 * 1024) return false;
    return true;
}

// Default token
$res = runQuery("SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM form_pendaftaran");
$row = $res->fetch_assoc();
$next_id = $row['next_id'];
$default_token = "CALON" . (19460 + $next_id);

// Hapus
if (isset($_GET['delete'])) {
    $t = $koneksi->real_escape_string($_GET['delete']);
    $row = runQuery("SELECT foto_siswa FROM form_pendaftaran WHERE token_pendaftaran='$t'")->fetch_assoc();
    if ($row && $row['foto_siswa']) {
        $path = __DIR__ . "/../images/Muka/" . $row['foto_siswa'];
        if (file_exists($path)) @unlink($path);
    }
    runQuery("DELETE FROM form_pendaftaran WHERE token_pendaftaran='$t'");
    header("Location: form_pendaftaran.php"); exit;
}

// Simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_edit = isset($_POST['edit_mode']) && $_POST['edit_mode'];
    $token = $koneksi->real_escape_string(trim($_POST['token_pendaftaran'] ?? ''));
    $tp = $koneksi->real_escape_string(trim($_POST['pesertadidik_token_peserta'] ?? ''));
    $tw = $koneksi->real_escape_string(trim($_POST['walimurid_token_wali'] ?? ''));
    $st = $koneksi->real_escape_string(trim($_POST['status_seleksi'] ?? 'Cadangan'));
    
if (!$is_edit && $token === '') {
    $res = runQuery("SELECT COALESCE(MAX(id_seq),0)+1 AS next_id FROM form_pendaftaran");
    $row = $res->fetch_assoc();
    $next_id = $row['next_id'];
    $token = "FORM" . (1000 + $next_id);
}

    $foto_file = null;
    if (isset($_FILES['foto_siswa']) && $_FILES['foto_siswa']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto_siswa']['error'] === UPLOAD_ERR_OK) {
            if (!valid_image($_FILES['foto_siswa'])) die("Upload gagal: hanya JPG/PNG max 2MB.");
            $ext = pathinfo($_FILES['foto_siswa']['name'], PATHINFO_EXTENSION);
            $foto_file = $token . "_" . time() . "." . $ext;
            $target = __DIR__ . "/../images/Muka" . $foto_file;
            move_uploaded_file($_FILES['foto_siswa']['tmp_name'], $target);
        }
    }

    if ($is_edit) {
        if ($foto_file) {
            $old = runQuery("SELECT foto_siswa FROM form_pendaftaran WHERE token_pendaftaran='$token'")->fetch_assoc();
            if ($old && $old['foto_siswa']) {
                $oldpath = __DIR__ . "/../images/Muka" . $old['foto_siswa'];
                if (file_exists($oldpath)) @unlink($oldpath);
            }
            runQuery("UPDATE form_pendaftaran 
                SET pesertadidik_token_peserta='$tp', walimurid_token_wali='$tw', status_seleksi='$st', foto_siswa='$foto_file' 
                WHERE token_pendaftaran='$token'");
        } else {
            runQuery("UPDATE form_pendaftaran 
                SET pesertadidik_token_peserta='$tp', walimurid_token_wali='$tw', status_seleksi='$st' 
                WHERE token_pendaftaran='$token'");
        }
    } else {
        $foto_val = $foto_file ? "'$foto_file'" : "NULL";
        runQuery("INSERT INTO form_pendaftaran 
            (token_pendaftaran, pesertadidik_token_peserta, walimurid_token_wali, status_seleksi, tanggal_daftar, foto_siswa) 
            VALUES ('$token', '$tp', '$tw', '$st', NOW(), $foto_val)");
    }
    header("Location: form_pendaftaran.php"); exit;
}

// Edit
$edit = null;
if (isset($_GET['edit'])) {
    $t = $koneksi->real_escape_string($_GET['edit']);
    $edit = runQuery("SELECT * FROM form_pendaftaran WHERE token_pendaftaran='$t'")->fetch_assoc();
}

// Data
$data = runQuery("SELECT f.*, p.nama_peserta, w.nama_wali 
    FROM form_pendaftaran f 
    LEFT JOIN pesertadidik p ON f.pesertadidik_token_peserta=p.token_peserta 
    LEFT JOIN walimurid w ON f.walimurid_token_wali=w.token_wali 
    ORDER BY f.id_seq ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Form Pendaftaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboard-style.css">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar collapse d-md-block">
      <div class="pt-3">
        <div class="text-center mb-4">
          <img src="../images/Frame 1.png" alt="Logo" class="img-fluid" style="max-width:120px;">
        </div>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="pesertadidik.php"><i class="bi bi-people-fill"></i>Kelola Peserta Didik</a></li>
          <li class="nav-item"><a class="nav-link" href="walimurid.php"><i class="bi bi-person-badge"></i>Kelola Orang Tua/Wali</a></li>
          <li class="nav-item"><a class="nav-link active" href="form_pendaftaran.php"><i class="bi bi-file-earmark-text"></i> Kelola Pendaftaran</a></li>
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
        <h1 class="page-title">Kelola Form Pendaftaran</h1>

        <!-- Form -->
        <form method="post" enctype="multipart/form-data" class="card p-3 mb-4">
          <div class="row g-2">
            <div class="col-md-3">
              <label class="form-label small">Token Pendaftaran</label>
              <input type="text" name="token_pendaftaran" class="form-control" 
                value="<?= htmlspecialchars($edit['token_pendaftaran'] ?? $default_token) ?>" <?= $edit ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
              <label class="form-label small">Token Peserta</label>
              <input type="text" name="pesertadidik_token_peserta" class="form-control"
                value="<?= htmlspecialchars($edit['pesertadidik_token_peserta'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label small">Token Wali</label>
              <input type="text" name="walimurid_token_wali" class="form-control"
                value="<?= htmlspecialchars($edit['walimurid_token_wali'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label small">Status Seleksi</label>
              <select name="status_seleksi" class="form-control">
                <option value="Cadangan" <?= (($edit['status_seleksi'] ?? '') == 'Cadangan') ? 'selected' : '' ?>>Cadangan</option>
                <option value="Diterima" <?= (($edit['status_seleksi'] ?? '') == 'Diterima') ? 'selected' : '' ?>>Diterima</option>
                <option value="Ditolak" <?= (($edit['status_seleksi'] ?? '') == 'Ditolak') ? 'selected' : '' ?>>Ditolak</option>
              </select>
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-md-6">
              <label class="form-label small">Foto Siswa (jpg/png, ≤2MB)</label>
              <input type="file" name="foto_siswa" class="form-control">
              <?php if ($edit && $edit['foto_siswa']): ?>
                <small class="form-text">Foto saat ini:</small><br>
                <img src="../images/Muka<?= htmlspecialchars($edit['foto_siswa']) ?>" width="120" class="img-thumbnail mt-1">
              <?php endif; ?>
            </div>
          </div>

          <input type="hidden" name="edit_mode" value="<?= $edit ? 1 : 0 ?>">
          <button class="btn btn-primary mt-3"><?= $edit ? 'Update' : 'Tambah' ?></button>
          <?php if ($edit): ?><a href="form_pendaftaran.php" class="btn btn-outline-secondary mt-3 ms-2">Batal</a><?php endif; ?>
        </form>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-fixed">
<thead>
<tr>
  <th>ID</th>
  <th>Token</th>
  <th>Nama Calon</th>
  <th>Nama Wali</th>
  <th>Status</th>
  <th>Tanggal</th>
  <th>Foto</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while ($r = $data->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($r['id_seq']) ?></td>
  <td><?= htmlspecialchars($r['token_pendaftaran']) ?></td>
  <td><?= htmlspecialchars($r['nama_peserta'] ?? '-') ?></td>
  <td><?= htmlspecialchars($r['nama_wali'] ?? '-') ?></td>
  <td><?= htmlspecialchars($r['status_seleksi']) ?></td>
  <td><?= htmlspecialchars($r['tanggal_daftar']) ?></td>
  <td>
    <?php if ($r['foto_siswa']): ?>
      <img src="../images/Muka/<?= htmlspecialchars($r['foto_siswa']) ?>" alt="Foto">
    <?php endif; ?>
  </td>
  <td>
    <a href="?edit=<?= urlencode($r['token_pendaftaran']) ?>" class="btn btn-sm btn-warning">Edit</a>
    <a href="?delete=<?= urlencode($r['token_pendaftaran']) ?>" onclick="return confirm('Hapus?')" class="btn btn-sm btn-danger">Hapus</a>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
