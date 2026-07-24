# 🌍 Global Supply Chain Risk Intelligence System (CargoVision)

[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Testing](https://img.shields.io/badge/Tests-34%20PASSED-10B981?style=for-the-badge&logo=phpunit&logoColor=white)](https://phpunit.de)

📌 **Repository GitHub:** [https://github.com/shilvasilvia/project_final_uas_cargovision](https://github.com/shilvasilvia/project_final_uas_cargovision)

---

## 📋 Status Modul & Spesifikasi Fitur

| No | Modul / Fitur | Status | Deskripsi |
|:---:|:---|:---:|:---|
| 1 | 🌍 **Country Intelligence Center** | 🟡 Terimplementasi | Pemantauan 15 negara utama dengan detail indikator makro. |
| 2 | 📊 **Economic Data — GDP, Inflasi, Populasi** | 🟡 Terimplementasi | Integrasi API World Bank & tabel indikator ekonomi. |
| 3 | 💱 **Currency Impact Dashboard** | 🟡 Terimplementasi | Monitoring dampak nilai tukar mata uang terhadap risiko rantai pasok. |
| 4 | 🌦️ **Global Weather Monitoring** | 🟡 Terimplementasi | Monitoring peringatan dini badai & cuaca ekstrem di kawasan pelabuhan. |
| 5 | ⚠️ **Risk Scoring Engine** | 🟡 Terimplementasi | Algoritma perhitungan skor risiko terpadu per negara (skala 0–100). |
| 6 | 📰 **News Intelligence & Sentiment** | 🟡 Terimplementasi | Analisis sentimen berita geopolitik (*Positive, Negative, Neutral*). |
| 7 | ⚓ **Port Location Dashboard** | 🟡 Terimplementasi | Peta interaktif Leaflet.js pemetaan lokasi pelabuhan global. |
| 8 | 📈 **Data Visualization Dashboard** | 🟡 Terimplementasi | Visualisasi grafik interaktif distribusi pengiriman & skor risiko. |
| 9 | 🔍 **Country Comparison Engine** | 🟡 Terimplementasi | Komparasi *side-by-side* indikator & risiko antar 2 negara. |
| 10 | ⭐ **Favorite Monitoring List** | 🟡 Terimplementasi | Fitur simpan/bookmark negara favorit bagi pengguna. |
| 11 | 🛠️ **Admin Dashboard & RBAC** | 🟡 Terimplementasi | Manajemen Master Data, User, Pelabuhan, Laporan & PDF/Excel Export. |

---

## 🎯 Spesifikasi Kebutuhan Teknis (Requirement Prompt)

> **Role & Prompt Specification:**
> Anda adalah Senior Full-Stack Laravel Developer dengan keahlian dalam Data Visualization dan GIS (Geographic Information Systems).
>
> **Task:** Membangun aplikasi "Global Supply Chain Intelligence" menggunakan Laravel 12 untuk menampilkan data rantai pasok global 15 negara.

### 🛠️ Requirement Teknis & Arsitektur:
- **Framework & UI**: Laravel 12 & Bootstrap 5 dengan aksen Deep Dark Mode modern.
- **Peta & Pelabuhan**: Leaflet.js interaktif dengan kordinat geografis pelabuhan global.
- **Data Real-time**: Fetching Data (Kurs, Risiko, Cuaca) dan pembaruan informasi berkala.
- **Visualisasi Data**: Chart interaktif (Chart.js) & Compare Mode untuk membandingkan statistik antar negara secara *side-by-side*.
- **Waktu**: Penanganan waktu lokal (UTC) dan timestamp yang akurat.
- **Database & Model**: Skema database modular untuk `countries`, `ports` (latitude/longitude), `shipments`, `weather_alerts`, `economic_data`, `news`, `audit_logs`, dan `favorites`.

---

## 👥 Hak Akses & Aktivitas Pembagian Role

### 1. 👤 User (Pengguna Biasa / Analis Risiko)
User adalah pihak yang memanfaatkan sistem untuk melakukan pemantauan (*monitoring*), analisis, dan membantu pengambilan keputusan bisnis logistik.
- **Memantau Risiko & Cuaca**: Mengakses *Global Country Dashboard* dan *Global Weather Monitoring* untuk melihat *Risk Score*, indikator ekonomi (GDP, inflasi, populasi, mata uang), serta kondisi cuaca ekstrem.
- **Analisis & Komparasi**: Menggunakan *Country Comparison Engine* untuk membandingkan dua negara (misal: *Germany vs Australia*) serta melihat *Currency Impact Dashboard* dan *Data Visualization Dashboard* (tren GDP, inflasi, kurs, dan risiko).
- **Pencarian Informasi**: Mencari lokasi pelabuhan dunia melalui *Port Location Dashboard* dan membaca berita logistik/ekonomi di *News Intelligence*.
- **Manajemen Personal**: Menyimpan dan mengelola daftar negara yang ingin dipantau khusus melalui fitur *Favorite Monitoring List / watchlists*.

### 2. 👑 Admin (Administrator / Pengelola Sistem)
Admin bertanggung jawab penuh atas pengelolaan data utama (*master data*), manajemen pengguna, dan konten pendukung sistem agar platform dapat beroperasi dengan baik.
- **Kelola User**: Mengatur, menambah, memproses, atau menghapus hak akses akun pengguna dalam sistem (`users`).
- **Kelola Dataset Pelabuhan**: Mengunggah, memperbarui, atau mengelola basis data pelabuhan dunia (`ports` / *World Port Index Dataset*) yang ditampilkan di peta.
- **Kelola Artikel Analisis**: Mengunggah, mengubah, atau menghapus artikel analisis risiko internal (`articles` / `news`) yang dipublikasikan ke dalam platform.
- **Pengelolaan Konten & Sistem**: Memastikan ketersediaan dataset internal, seperti kamus kata *sentiment analysis* (`positive_words` & `negative_words`) agar kalkulasi *scoring* dan prediksi berjalan optimal.

---

## 🚀 Panduan Instalasi & Penggunaan

```bash
# 1. Clone repository
git clone https://github.com/shilvasilvia/project_final_uas_cargovision.git
cd project_final_uas_cargovision

# 2. Install dependency PHP & Node
composer install
npm install

# 3. Konfigurasi Environment & Key
cp .env.example .env
php artisan key:generate

# 4. Migrasi Database & Seeding Data Awal
php artisan migrate:fresh --seed

# 5. Build Asset Frontend
npm run build

# 6. Menjalankan Server Lokal
php artisan serve
```

### 🔑 Kredensial Default Login:
- **👑 Admin**: `admin@example.com` / `password` (Full Access: CRUD, Reports, PDF/Excel)
- **👤 User**: `user@example.com` / `password` (Read-Only & Favorites)
