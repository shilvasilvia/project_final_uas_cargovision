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

## 👥 Hak Akses & Pembagian Role User

### 1. 👤 Bagian User (Pengguna)
User adalah pengguna aplikasi yang memantau rantai pasok global:
- **Global Country Dashboard**: Memilih negara spesifik (seperti Jerman, China, Indonesia, Australia) untuk menampilkan data GDP, inflasi, populasi, mata uang, dan cuaca.
- **Favorite Monitoring List**: Menyimpan negara-negara tertentu yang ingin dipantau secara khusus ke dalam daftar favorit.
- **Analisis & Komparasi**: Memanfaatkan visualisasi grafik, peta cuaca global, dashboard kurs mata uang, analisis sentimen berita, dan *Country Comparison Engine*.

### 2. 👑 Bagian Admin (Pengelola)
Admin memiliki peran sebagai pengelola sistem di back-office:
- **Kelola User**: Mengelola data pengguna yang terdaftar di dalam platform.
- **Kelola Master Data & Dataset Pelabuhan**: Mengakses & memperbarui data pelabuhan, negara, dan operasional pengiriman.
- **Kelola Laporan & Artikel Analisis**: Mengakses modul laporan, rekalkulasi skor risiko, serta ekspor laporan ke format **PDF & Excel**.

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
