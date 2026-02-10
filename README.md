# 🎓 Sistem Informasi Pembayaran SPP (SIP-SPP)

Sistem administrasi berbasis web yang dirancang untuk mendigitalisasi proses pencatatan, pengelolaan, dan pelaporan pembayaran SPP di lingkungan sekolah. Aplikasi ini bertujuan untuk meningkatkan efisiensi kerja petugas tata usaha dan transparansi data bagi siswa.

---

## 🏗️ Arsitektur & Logika Sistem

Aplikasi ini dibangun menggunakan arsitektur **MVC (Model-View-Controller)** sederhana untuk memastikan kode terorganisir dengan baik:

1.  **Authentication Layer**: Mengamankan akses sistem berdasarkan level user (Admin, Petugas, Siswa).
2.  **Validation Logic**: Sistem akan memvalidasi pembayaran berdasarkan tahun ajaran dan nominal yang terikat pada data SPP masing-masing siswa.
3.  **Relational Database**: Menggunakan database MySQL dengan relasi antar tabel (Siswa, Kelas, SPP, Petugas, dan Pembayaran) untuk menjaga integritas data.

---

## 🌟 Fitur Utama

### 1. Manajemen Data Master (Admin Only)
* **Data Siswa**: Pengelolaan profil siswa (NISN, NIS, Nama, Alamat).
* **Data Kelas**: Pengaturan ruang kelas dan kompetensi keahlian.
* **Data SPP**: Penentuan nominal tagihan berdasarkan tahun ajaran.
* **Data Petugas**: Pengelolaan akun untuk hak akses sistem.

### 2. Modul Transaksi
* **Pembayaran Real-time**: Input pembayaran cepat dengan referensi NISN.
* **Cek Riwayat**: Menampilkan histori pembayaran yang telah dilakukan secara kronologis.
* **Validasi Petugas**: Mencatat siapa petugas yang melayani transaksi untuk akuntabilitas.

### 3. Pelaporan & Output
* **Generate Laporan**: Fitur untuk mencetak laporan transaksi dalam format dokumen.
* **History Siswa**: Memberikan akses kepada siswa untuk memantau status pembayaran mereka secara mandiri.

---

## 👥 Hak Akses User

| Role | Deskripsi Hak Akses |
| :--- | :--- |
| **Admin** | Memegang kendali penuh atas data master, transaksi, dan laporan. |
| **Petugas** | Fokus pada modul transaksi dan melihat riwayat pembayaran. |
| **Siswa** | Hanya diizinkan melihat riwayat pembayaran miliknya sendiri. |

---

## 🛠️ Stack Teknologi

* **Bahasa Pemrograman**: PHP
* **Database**: MySQL
* **Frontend**: HTML, CSS, JavaScript (Bootstrap Framework)
* **Server**: Apache (via XAMPP/Laragon)

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/NabilAlifM/Pembayaran-SPP.git](https://github.com/NabilAlifM/Pembayaran-SPP.git)
    ```

2.  **Persiapkan Database**
    * Nyalakan MySQL di XAMPP/Laragon.
    * Buka `phpMyAdmin` dan buat database baru dengan nama `db_spp`.
    * Import file `.sql` yang terdapat di dalam folder project (biasanya folder `database/` atau `sql/`).

3.  **Konfigurasi Koneksi**
    * Buka file koneksi database (misal: `koneksi.php` atau `config.php`).
    * Sesuaikan `DB_USER`, `DB_PASS`, dan `DB_NAME` dengan pengaturan lokal Anda.

4.  **Jalankan Aplikasi**
    * Pindahkan folder project ke direktori `htdocs` atau `www`.
    * Akses di browser melalui: `http://localhost/Pembayaran-SPP`

---

## 📁 Struktur Folder Utama
* `inc/` atau `config/` : Berisi file konfigurasi database.
* `view/` : Berisi template tampilan (Layouting).
* `controller/` : Logika pemrosesan data.
* `assets/` : File pendukung seperti CSS, JS, dan Gambar.

---

## 📝 Kontribusi
Kontribusi selalu terbuka! Jika Anda menemukan bug atau ingin menambahkan fitur:
1. Fork repository ini.
2. Buat branch baru untuk fitur Anda.
3. Lakukan Pull Request.

---
Developed with ❤️ by [Nabil Alif M](https://github.com/NabilAlifM)
