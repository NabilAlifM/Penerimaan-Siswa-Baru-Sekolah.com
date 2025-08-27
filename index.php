<?php
session_start();

// ambil role dari session
$role = $_SESSION['role'] ?? 'guest';
$nama = $_SESSION['nama'] ?? '';
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
              <a href="aktivasi.php">
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


    <img src="images/pola.png" alt="">

    <section>
      <div class="bg-red-600 text-white text-2xl max-w-xl h-auto top-0 left-0 rounded-r-2xl px-6 py-4 font-bold">
        <p>Panduan dalam pendaftaran Sekolah.com</p>
      </div>

      <div class="flex">
        <aside>
  <div class="self-start border-r-black grid row-start-4 w-sm h-auto relative top-32 left-12 bg-gradient-to-r from-gray-200 via-gray-100 to-white p-2">


  <a href="index.php">
      <div class="w-40 h-auto p-2 mx-6">
        <img src="images/beranda1.png" class="w-20">
        <p class="text-red-500 font-bold">BERANDA</p>
      </div>
    </a>

    <a href="jadwal.php">
      <div class="w-40 h-auto p-2 mx-6">
        <img src="images/jadwal.png" class="w-20">
        <p class="font-bold">JADWAL</p>
      </div>
    </a>

    <a href="#" onclick="checkAccess('hasil', '<?= $role ?>')">
      <div class="w-40 h-auto p-2 mx-6">
        <img src="images/hasil.png" class="w-20">
        <p class="font-bold">HASIL SELEKSI</p>
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

        <div class="relative mx-auto flex flex-col items-center space-y-8 w-2xl top-32   ml-44">
          <!-- Baris 1 -->
          <div class="relative self-start bg-gradient-to-r from-gray-200 via-gray-100 to-white shadow-lg rounded-lg px-12 py-8 w-96 h-36">
            <div class="absolute -top-4 -left-4  bg-red-500 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg">
              1
            </div>  
            <p class=" text-red-600 font-bold text-2xl italic">
              Aktivasi Akun <br> Sebagai Orang tua
            </p>
          </div>

          <!-- Baris 2 -->
          <div class="relative self-end bg-gradient-to-r from-gray-200 via-gray-100 to-white shadow-lg rounded-lg px-12 py-8 w-96 h-36">
            <div class="absolute -top-4 -right-4 bg-red-500 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg">
              2
            </div>
            <p class="text-red-600 font-bold text-2xl italic">
              Mendaftar di laman <br> pendaftaran murid
            </p>
          </div>  

          <!-- Baris 3-->
          <div class="relative self-start bg-gradient-to-r from-gray-200 via-gray-100 to-white shadow-lg rounded-lg px-12 py-8 w-96 h-36">
            <div class="absolute -top-4 -left-4 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg">
              3
            </div>
            <p class="text-red-600 font-bold text-2xl italic">
              Pantau Hasil <br> Seleksi Murid
            </p>
          </div>
        </div>

        <div class="flex flex-col justify-center ml-10">
          <img src="images/banner.png" alt="Ilustrasi" class="w-72 h-auto">
        </div>
      </div>

      <img src="images/pola.png" alt="pola">
      
    </section>

    <section>

      <div class="bg-red-600 text-white text-2xl max-w-xl h-auto top-0 left-0 rounded-r-2xl px-6 py-4 font-bold">
        <p>Tentang Sekolah.com</p>
      </div>
      <div class="flex justify-center items-center bg-gradient-to-r from-white via-gray-100 to-gray-200 p-6 mt-20 w-5xl mx-auto rounded-lg shadow-lg">
        <div class="w-5xl columns-2 items-center">
          
          <div class="flex justify-center mb-4">
            <img src="images/sekolah.png" alt="" class="relative right-10 w-full h-auto">
          </div>

          <p class="text-black text-lg text-left">
            <b>Sekolah.com</b> adalah sebuah platform edukasi berbasis daring (online) yang menyediakan berbagai macam konten dan layanan pendidikan untuk siswa, guru, dan masyarakat umum. Platform ini hadir sebagai solusi digital untuk membantu proses pembelajaran yang lebih mudah, fleksibel, dan terjangkau melalui internet.
          </p>
        </div>
      </div>

          <img src="images/pola.png" alt="pola">

    </section>

    <section>

      <div class="bg-red-600 text-white text-2xl max-w-xl h-auto top-0 left-0 rounded-r-2xl px-6 py-4 font-bold">  
        <p>Lokasi Sekolah.com</p>
      </div>

      <div class="flex justify-center items-center bg-gradient-to-r p-6 mt-20 mx-auto rounded-lg shadow-lg">
        <div class="w-5xl items-center">
          
          <div class="flex justify-center">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.8648471511588!2d106.72189747571534!3d-6.281492361487902!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f0754ef56067%3A0x8220dbf978d54527!2sSMK%20Bina%20Informatika!5e0!3m2!1sid!2sid!4v1754964889287!5m2!1sid!2sid" width="800" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>          
          </div>

          <p class="text-black text-lg text-center mt-10 mb-10 ">
            <b>Jl. Tegal Rotan Raya No.9 A, Sawah Baru, Kec. Ciputat, Kota Tangerang Selatan, Banten 15412</b>
          </p>
        </div>      
      </section>
      

<footer class="bg-[#2D2D2D] text-white">
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
      Copyright © 2003 - 2024, PSB Sekolah.Com <br />
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