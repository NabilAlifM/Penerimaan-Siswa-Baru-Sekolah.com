<?php
include 'koneksi.php'; // koneksi mysqli Anda
// pastikan isAdminLoggedIn() tersedia di koneksi atau auth file Anda
if (!function_exists('isAdminLoggedIn')) {
    function isAdminLoggedIn() {
        // fallback sederhana: sesuaikan dengan implementasi session Anda
        session_start();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
}

if (!isAdminLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Fungsi untuk menjalankan query
function runQuery($sql) {
    global $koneksi;
    $result = $koneksi->query($sql);
    if (!$result) {
        die("Query error: " . $koneksi->error);
    }
    return $result;
}

// Statistik berdasarkan tabel baru
$result = runQuery("SELECT COUNT(*) AS total FROM pesertadidik");
$total_peserta = $result->fetch_assoc()['total'];

$result = runQuery("SELECT COUNT(*) AS total FROM walimurid");
$total_wali = $result->fetch_assoc()['total'];

$result = runQuery("SELECT COUNT(*) AS total FROM form_pendaftaran");
$total_pendaftaran = $result->fetch_assoc()['total'];

$result = runQuery("SELECT COUNT(*) AS total FROM form_pendaftaran WHERE status_seleksi = 'Diterima'");
$total_diterima = $result->fetch_assoc()['total'];

// Ambil data terbaru (10) dari tiap tabel
$peserta = runQuery("SELECT * FROM pesertadidik ORDER BY id_seq ASC LIMIT 10");
$wali = runQuery("SELECT * FROM walimurid ORDER BY id_seq ASC LIMIT 10");
$pendaftaran = runQuery("SELECT f.*, p.nama_peserta, w.nama_wali 
    FROM form_pendaftaran f
    LEFT JOIN pesertadidik p ON f.pesertadidik_token_peserta = p.token_peserta
    LEFT JOIN walimurid w ON f.walimurid_token_wali = w.token_wali
    ORDER BY f.id_seq ASC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pendaftaran Siswa</title>

    <!-- CSS BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
                            <a class="nav-link active" href="dashboard.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pesertadidik.php">
                                <i class="bi bi-people-fill"></i> Kelola Peserta Didik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="walimurid.php">
                                <i class="bi bi-person-badge"></i> Kelola Orang Tua/Wali
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="form_pendaftaran.php">
                                <i class="bi bi-file-earmark-text"></i> Kelola Pendaftaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 ms-sm-auto">
                <!-- Header -->
                <div class="admin-header d-flex justify-content-between align-items-center py-3">
                    <div class="admin-brand">Admin Panel - Pendaftaran Siswa</div>
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

                <div class="main-content p-3">
                    <h1 class="page-title">Dashboard</h1>
                    
                    <!-- Statistik -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="bi bi-people-fill" style="font-size:32px"></i>
                                    <h5 class="card-title mt-2">Total Peserta</h5>
                                    <h2 class="display-5"><?php echo $total_peserta; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="bi bi-person-badge" style="font-size:32px"></i>
                                    <h5 class="card-title mt-2">Total Orang Tua/Wali</h5>
                                    <h2 class="display-5"><?php echo $total_wali; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-earmark-text" style="font-size:32px"></i>
                                    <h5 class="card-title mt-2">Total Pendaftaran</h5>
                                    <h2 class="display-5"><?php echo $total_pendaftaran; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body text-center">
                                    <i class="bi bi-check2-circle" style="font-size:32px"></i>
                                    <h5 class="card-title mt-2">Diterima</h5>
                                    <h2 class="display-5"><?php echo $total_diterima; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Peserta Terbaru -->
                    <div class="table-container mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Peserta Didik Terbaru</h3>
                            <a href="pesertadidik.php" class="btn btn-sm btn-primary">Kelola Peserta</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Seq</th>
                                        <th>Token</th>
                                        <th>Nama</th>
                                        <th>NISN</th>
                                        <th>No. Telp</th>
                                        <th>Asal Sekolah</th>
                                        <th>Waktu Registrasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($p = $peserta->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $p['id_seq']; ?></td>
                                        <td><?php echo $p['token_peserta']; ?></td>
                                        <td><?php echo $p['nama_peserta']; ?></td>
                                        <td><?php echo $p['nisn']; ?></td>
                                        <td><?php echo $p['no_telp']; ?></td>
                                        <td><?php echo $p['asal_sekolah']; ?></td>
                                        <td><?php echo $p['waktu_registrasi']; ?></td>
                                        <td>
                                            <a href="pesertadidik.php?edit=<?php echo $p['token_peserta']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                            <a href="pesertadidik.php?delete=<?php echo $p['token_peserta']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus peserta ini?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Wali Terbaru -->
                    <div class="table-container mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Orang Tua / Wali Terbaru</h3>
                            <a href="walimurid.php" class="btn btn-sm btn-primary">Kelola Wali</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Seq</th>
                                        <th>Token</th>
                                        <th>Nama Wali</th>
                                        <th>No. Telp</th>
                                        <th>Hubungan</th>
                                        <th>Waktu Registrasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($w = $wali->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $w['id_seq']; ?></td>
                                        <td><?php echo $w['token_wali']; ?></td>
                                        <td><?php echo $w['nama_wali']; ?></td>
                                        <td><?php echo $w['no_telp_wali']; ?></td>
                                        <td><?php echo $w['hubungan']; ?></td>
                                        <td><?php echo $w['waktu_registrasi']; ?></td>
                                        <td>
                                            <a href="walimurid.php?edit=<?php echo $w['token_wali']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                            <a href="walimurid.php?delete=<?php echo $w['token_wali']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data wali?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Pendaftaran Terbaru -->
                    <div class="table-container mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>Form Pendaftaran Terbaru</h3>
                            <a href="form_pendaftaran.php" class="btn btn-sm btn-primary">Kelola Pendaftaran</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Seq</th>
                                        <th>Token Pendaftaran</th>
                                        <th>Nama Calon</th>
                                        <th>Token Peserta</th>
                                        <th>Token Wali</th>
                                        <th>Status Seleksi</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($f = $pendaftaran->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $f['id_seq']; ?></td>
                                        <td><?php echo $f['token_pendaftaran']; ?></td>
                                        <td><?php echo $f['nama_siswa'] ?? $f['nama_peserta']; ?></td>
                                        <td><?php echo $f['pesertadidik_token_peserta']; ?></td>
                                        <td><?php echo $f['walimurid_token_wali']; ?></td>
                                        <td>
                                            <?php 
                                                $st = $f['status_seleksi'];
                                                if ($st == 'Diterima') echo '<span class="badge bg-success">Diterima</span>';
                                                elseif ($st == 'Ditolak') echo '<span class="badge bg-danger">Ditolak</span>';
                                                else echo '<span class="badge bg-secondary">Cadangan</span>';
                                            ?>
                                        </td>
                                        <td><?php echo $f['tanggal_daftar']; ?></td>
                                        <td>
                                            <a href="form_pendaftaran.php?edit=<?php echo $f['token_pendaftaran']; ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                            <a href="form_pendaftaran.php?delete=<?php echo $f['token_pendaftaran']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus pendaftaran ini?')"><i class="bi bi-trash"></i></a>
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
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
