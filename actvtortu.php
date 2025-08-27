
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$token_aktivasi_ortu = $_SESSION['token_aktivasi_ortu'] ?? null;
unset($_SESSION['token_aktivasi_ortu']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AKTIVASI AKUN ORTU</title>
     <link rel="stylesheet" href="styles/actvtortu_styles.css"/>
    <link rel="Website icon" type="images/png" href="images/Logo.png" style="width: auto; height: auto; border-radius: 3px;">

</head>
<body>
    <div class="container">
        <div class="rightSide">
            <img src="images/Group 82.png" alt="background" />
        </div>
        <div class="leftSide">
            <div class="header">
                <a href="index.php" class="backBtn">
            <button class="backBtn">
              <span class="backIcon"><img href="index.php" src="images/ep_back.png" /></span>
              Kembali
            </button>
          </a>
                <div class="logoContainer">
                    <img src="images/Frame 2.png" alt="Sekolah.com" class="logo" />
                </div>
                </div>
                <div class="tabContainer">
                    <button class="tabM" onclick="window.location.href='aktivasi.php'">
                    <span class="icon"><img src="images/uis_schedule.png"></span> 
                    MURID
                    </button>
                    <button class="tabO" onclick="window.location.href='actvtortu.html'">
                    <span class="icon"><img src="images/ri_parent-fill.png"></span> 
                    ORANG TUA
                    </button>
                </div>
                <div class="formContainer">
                    <form method="POST" id="actvMB" action="logic_actvtortu.php" class="actvMB">
                    <div class="row">
                    <div class="col">
                        <label for="nama_wali">Nama Lengkap</label>
                        <input type="text" name="nama_wali" placeholder="Masukan Nama.." />
                    </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="hubungan">Hubungan</label>
                            <select name="hubungan">
                                <option value="" disabled selected>Pilih Hubungan</option>
                                <option value="Ayah">Ayah</option>
                                <option value="Ibu">Ibu</option>
                                <option value="Wali">Wali</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="no_telp_wali">No. HP</label>
                            <input type="text" name="no_telp_wali" />
                        </div>
                    </div>
                    <div class="row">
                    <div class="col">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" placeholder="Masukan Alamat.."/>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="password">Password</label>
                            <input type="password" id="coba" name="password" />
                        </div>
                    </div>
                    <button type="submit" class="submitBtn">Aktivasi</button> <!-- Changed button text -->
                    </form>
                </div>
        </div>
    </div>
</div>
<?php if ($token_aktivasi_ortu): ?>
    <div class="popup-overlay">
      <div class="popup">
        <h2>Aktivasi Ortu Berhasil!</h2>
        <p>Token Wali Anda:</p>
        <div class="token-box"><?= htmlspecialchars($token_aktivasi_ortu) ?></div>
        <a href="masuk.html" class="popup-btn">Ke Halaman Login</a>
      </div>
    </div>
  <?php endif; ?>
</body>
</html>