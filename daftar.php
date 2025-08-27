<?php
session_start();
$tokenWali = $_SESSION['token_wali'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PENDAFTARAN</title>
    <link rel="stylesheet" href="styles/styles.css" />
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
            <img src="images/Frame 2.png" alt="Sekolah.com" class="logo" />
          </div>
        </div>
        <div class="formContainer">
          <id class="pmHeader">
            <span class="icon"><img src="images/uis_schedule.png" /></span>
            PENDAFTARAN MURID
          </id>
          <form
            id="pmb"
            class="pmb"
            action="logic_pendaftaranM.php"
            method="POST"
            enctype="multipart/form-data"
          >
            <input type="hidden" name="tokenWali" value="<?= htmlspecialchars($tokenWali) ?>">

            <div class="row">
              <div class="col">
                <label for="tokenMurid">Token Murid</label>
                <input
                  type="text"
                  id="tokenMurid"
                  name="tokenMurid"
                  placeholder="Masukkan Token.."
                />
              </div>
              <div class="col">
                <label for="rataRataNilai">Rata-Rata Nilai</label>
                <input
                  type="text"
                  id="rataRataNilai"
                  name="rataRataNilai"
                  placeholder="Masukkan Nilai.."
                />
              </div>
            </div>
            <label for="fotoMurid">Foto Murid</label>
            <div class="fileUpload">
              <input
                type="file"
                id="fotoMurid"
                name="fotoMurid"
                accept="image/*"
              />
              <div class="fileUploadPlaceholder">
                <span class="file-upload-icon"
                  ><img src="images/fluent-mdl2_photo-2-add.png"
                /></span>
                <span>Unggah File</span>
              </div>
            </div>

            


            
            <button type="submit" class="submitBtn">Daftar</button>
          </form>
        </div>
      </div>

      <div class="rightSide">
        <img src="images/Group 82.png" alt="background" />
      </div>
    </div>
  </body> 
</html>
