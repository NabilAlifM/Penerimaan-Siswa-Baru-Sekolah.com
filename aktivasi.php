<?php
session_start();
$token_aktivasi = $_SESSION['token_aktivasi'] ?? null;
unset($_SESSION['token_aktivasi']); // biar ga muncul lagi kalau reload
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AKTIVASI AKUN MURID</title>
    <link rel="stylesheet" href="styles/actvt_styles.css">
    <link rel="Website icon" type="images/png" href="images/Logo.png" style="width: auto; height: auto; border-radius: 3px;">

</head>
<body>
    <div class="container">
        <div class="leftSide">
            <div class="header">
                <a href="index.php" class="backBtn">
            <button class="backBtn">
              <span class="backIcon"><img href="index.html" src="images/ep_back.png" /></span>
              Kembali
            </button>
          </a>
                <div class="logoContainer">
                <img src="images/Frame 2.png" alt="Sekolah.com" class="logo">
                </div>
            </div>
            
            <div class="tabContainer">
                <button class="tabM" onclick="window.location.href='#'">
                <span class="icon">
                <img src="images/uis_schedule.png" alt="Murid">
                </span>
                MURID
                </button>
                <button class="tabO" onclick="window.location.href='actvtortu.php'">
                <span class="icon">
                <img src="images/ri_parent-fill.png" alt="Orang Tua">
                </span>
                ORANG TUA
                </button>
            </div>
            
            <div class="formContainer">
                <form id="actvMB" method="POST" action="logic_actvtmurid.php" class="actvMB">
                <div class="row">
                <div class="col">
                <label for="namaMurid">Masukan Nama</label>
                    <input type="text" name="nama_peserta" placeholder="Masukan Nama..">
                </div>
                </div>
                    
                <div class="row">
                <div class="col">
                    <label for="nisn">NISN</label>
                    <input type="text" name="nisn">
                </div>
                <div class="col">
                    <label for="tempatLahir">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir">
                </div>
                </div>
                    
                <label for="tanggalLahir">Tanggal Lahir</label>
                <div class="row">
                    <input type="number" name="tanggal" min="1" max="31">
                    <input type="number" name="bulan" min="1" max="12">
                    <input type="number" name="tahun" min="2009" max="2012">
                    <img src="images/bx_calendar.png" alt="Calendar">
                </div>
                    
                <div class="row">
                <div class="col">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                    <option value="" disabled selected></option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col">
                    <label for="no_telp">No. HP</label>
                    <input type="text" name="no_telp">
                </div>
                </div>
                    
                <div class="row">
                <div class="col">
                    <label for="asal_sekolah">Asal Sekolah</label>
                    <input type="text" id="asalSekolah" name="asal_sekolah">
                </div>
                <div class="col">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                </div>

                <div class="row">
                <div class="col">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat">
                </div>
                <div class="col">
                    <label for="walimurid_token_wali">Token Wali Anda</label>
                    <input type="text" name="walimurid_token_wali">
                </div>
                </div>
                    
                <button type="submit" class="submitBtn">Aktivasi</button>
                </form>
            </div>
        </div>
        
        <div class="rightSide">
            <img src="images/Group 82.png" alt="Background">
        </div>
    </div>
    <?php if ($token_aktivasi): ?>
    <div class="popup-overlay">
      <div class="popup">
        <h2>Aktivasi Berhasil!</h2>
        <p>Token Anda:</p>
        <div class="token-box"><?= htmlspecialchars($token_aktivasi) ?></div>
        <a href="masuk.html" class="popup-btn">Ke Halaman Login</a>
      </div>
    </div>
  <?php endif; ?>

</body>
</html>
