
<?php


session_start();
include 'koneksi.php'; // koneksi pakai mysqli, misal $koneksi = new mysqli(...);

$role         = $_SESSION['role'] ?? 'guest';
$nama         = $_SESSION['nama'] ?? '';
$tokenWali    = $_SESSION['token_wali'] ?? null;
$tokenPeserta = $_SESSION['token_peserta'] ?? null;

$hasil = [];

// jika wali murid login
if ($role === 'walimurid') {
    $sql = "SELECT f.*
            FROM form_pendaftaran f
            WHERE f.walimurid_token_wali = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tokenWali);
    $stmt->execute();
    $hasil = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
elseif ($role === 'pesertadidik') {
    $sql = "SELECT f.*
            FROM form_pendaftaran f
            WHERE f.pesertadidik_token_peserta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tokenPeserta);
    $stmt->execute();
    $hasil = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

 else {
    // selain wali murid & peserta didik -> redirect
    header("Location: index.php");
    exit;
}
?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <!-- CSS EKSTERNAL -->
    <link rel="stylesheet" href="styles/index-style.css">

    <!-- CSS FLOWBITE -->
     <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- CSS TAILWIND -->
    <link href="styles/output.css" rel="stylesheet">

    <link rel="Website icon" type="images/png" href="images/Logo.png" style="width: auto; height: auto; border-radius: 3px;">

  </head>
  <body>
    <!-- ALERT -->
    <div id="alert-box" class="hidden fixed top-5 right-5 bg-amber-400 text-white px-10 py-10 rounded-lg shadow-lg z-50 font-bold text-xl">
      <span id="alert-msg"></span>
    </div>
  <header>
         <div id="logo" class="flex items-center gap-6">
          <img src="images/Frame 1.png" alt="">
          <div id="button-container" class="flex items-center gap-2">
            
            <?php if ($role === 'guest'): ?>
              <!-- Jika belum login -->
              <a href="masuk.html">
                <button type="button" class="flex items-center p-2 bg-blue-700 text-white rounded-xl gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" class="w-5 h-5">
                    <circle cx="12" cy="7" r="5"/>
                    <path d="M2 21c0-4 8-6 10-6s10 2 10 6v1H2v-1z"/>
                  </svg>
                  Masuk
                </button>
              </a>
              <a href="aktivasi.html">
                <button type="button" class="flex items-center p-2 bg-blue-900 text-white rounded-xl gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5">
                    <circle cx="12" cy="7" r="5"/>
                    <path d="M2 21c0-4 8-6 10-6s10 2 10 6v1H2v-1z"/>
                  </svg>
                  Aktivasi akun anda
                </button>
              </a>
            <?php else: ?>
              <!-- Jika sudah login -->
              <span class="font-bold"><?= htmlspecialchars($nama) ?> (<?= $role ?>)</span>
              <a href="logout.php">
                <button type="button" class="flex items-center p-2 bg-red-700 text-white rounded-xl gap-2">
                  Logout
                </button>
              </a>
            <?php endif; ?>
          </div>
        </div>



        <div id="running-text">
            <marquee behavior="scroll" direction="left" scrollamount="17">
                <span class="text">🚀 Masa depan cerah dimulai dari sini. Daftarkan dirimu sekarang dan wujudkan impianmu!</span>
            </marquee>
        </div>
      </header>

   <section class="container mx-auto mt-5 flex justify-center">
  <div class="flex justify-center items-center bg-linear-to-r from-white from-10% via-gray-100 via-50% to-gray-200  gap-6  h-64 w-full max-w-6xl">
      
      <div class="max-w-xs">
          <p class="text-black text-4xl font-extrabold">
              Selamat Datang di Website Pendaftaran Resmi 
              <br><span class="text-blue-800">Sekolah.com</span>
          </p>
      </div>
      <div>
        <img src="images/Logo_topi.png" alt="" class="w-60 h-auto">
      </div>              

      <img src="images/wisuda.png" alt="wisuda" class="max-w-sm h-  auto">
      
  </div>
</section>

<img src="images/pola.png" alt="pola">

<section >
      <div class="bg-red-600 text-white text-2xl w-xl h-auto top-0 rounded-r-2xl px-6 py-4 font-bold">
        <p>Hasil Siswa</p>
      </div>

      <div class="flex">
        <!-- Sidebar -->
          <aside>
          <div class="self-start border-r-black grid row-start-4 w-sm h-auto relative top-40 left-12 bg-gradient-to-r from-gray-200 via-gray-100 to-white p-2">
          

            <a href="index.php">
              <div class="w-40 h-auto p-2 mx-6">
                <img src="images/beranda.png" alt="Icon-Rumah" class="w-20">
                <p class="right-2 font-bold">BERANDA</p>
              </div>
            </a>

            <a href="jadwal.php">
              <div class="w-40 h-auto p-2 mx-6">
                <img src="images/jadwal.png" alt="Icon-Rumah" class="w-20">
                <p class="font-bold">JADWAL</p>
              </div>
            </a>

            <a href="hasil.php">
              <div class="w-50 h-auto p-2 mx-6">
                <img src="images/hasil1.png" alt="Icon-Rumah" class="">
                <p class="text-red-500 font-bold">HASIL SELEKSI</p>
              </div>
            </a>

             <a href="#" onclick="checkAccess('daftar', '<?= $role ?>')">
              <div class="absolute my-2 right-26 bg-red-500 text-white w-70 h-15 flex items-center justify-end font-bold text-xl px-14">
                <div class="flex items-center justify-end">
                  <img src="images/murid.png" class="w-auto h-auto mr-4">
                </div>
                  Daftarkan Anak Anda
               </div>
              </a>
          </div>
        </aside>

<div class="max-w-3xl mx-auto my-20 bg-gray-100 px-40 py-10 rounded-lg shadow-lg">
  <h2 class="text-center font-bold text-lg mb-6">KAMI UMUMKAN BAHWA :</h2>

  <?php if (count($hasil) > 0): ?>
    <?php foreach ($hasil as $row): ?>
      <div class="flex gap-4 mb-6">
        <img src="images/Muka/<?= htmlspecialchars($row['foto_siswa'] ?? 'default.jpg') ?>" 
     alt="<?= htmlspecialchars($row['nama_siswa'] ?? '') ?>" 
     class="w-28 h-36 object-cover rounded-md border">


        <div class="flex flex-col justify-center">
          <p><span class="font-semibold">NAMA</span> : <?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></p>
          <p><span class="font-semibold">ASAL SEKOLAH</span> : <?= htmlspecialchars($row['asal_sekolah'] ?? '-') ?></p>
          <p><span class="font-semibold">NILAI RATA RATA</span> : <?= htmlspecialchars($row['rata_rata_nilai'] ?? '-') ?></p>
          <p class="mt-1">
            <span class="font-semibold">KETERANGAN</span>
            <?php if (($row['status_seleksi'] ?? '') === 'Diterima'): ?>
              <span class="ml-2 px-2 py-1 bg-green-600 text-white text-xs font-bold rounded">LULUS</span>
            <?php elseif (($row['status_seleksi'] ?? '') === 'Ditolak'): ?>
              <span class="ml-2 px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">TIDAK LULUS</span>
            <?php elseif (($row['status_seleksi'] ?? '') === 'Cadangan'): ?>
              <span class="ml-2 px-2 py-1 bg-yellow-400 text-white text-xs font-bold rounded">CADANGAN</span>
            <?php else: ?>
            <?php endif; ?>
            <br>
 
          </p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center text-gray-600">Belum ada hasil pendaftaran.</p>
  <?php endif; ?>
</div>






        <div class="flex flex-col justify-center ml-10">
          <img src="images/banner.png" alt="Ilustrasi" class="w-72 h-auto">
        </div>
      </div>

      
    
</div>
</section>

<<footer class="bg-[#2D2D2D] text-white mt-20">
  <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-2 gap-8">

    <div>
      <h1 class="text-3xl font-bold">Sekolah.com</h1>
      <h2 class="text-lg font-semibold mt-2">Contact Center</h2>
      <p class="mt-4">Hotline: (021) 7453048</p>
      <p>WhatsApp: 62 812-8000-000</p>
      <p>Email: info@sekolah.com</p>
    </div>


    <div class="flex items-center right-10text-right">
      <p>
        Jl. Tegal Rotan Raya No.9 A, Sawah Baru, <br />
        Kec. Ciputat, Kota Tangerang Selatan, <br />
        Banten 15412
      </p>
    </div>
  </div>

  <div class="bg-[#1F1F1F] py-4">
    <div class="max-w-7xl mx-auto px-6 text-sm text-gray-400">
      Copyright © 2003 - 2024, PSB Sekolah.Com <br/>
      Real Time Online. All rights reserved.
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script>
    function showAlert(msg) {
      const box = document.getElementById("alert-box");
      const msgBox = document.getElementById("alert-msg");
      msgBox.innerText = msg;
      box.classList.remove("hidden");
      setTimeout(() => box.classList.add("hidden"), 4000);
    }
    
    function checkAccess(menu, role) {
      // rule usecase
      if (menu === "daftar" && role !== "walimurid") {
        showAlert("❌   Maaf Laman Hanya Dapat Diakses Wali Murid!");
        return;
      }
      if (menu === "hasil" && role === "guest") {
        showAlert("❌   Maaf Laman Hanya Dapat Diakses Murid & Wali Murid!");
        return;
      }
      // kalau lolos rule -> redirect
      window.location.href = menu + ".php";
    }
    </script>
  </body>
</html>