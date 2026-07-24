# 🌐 Global Supply Chain Risk Intelligence System

[![GitHub Repository](https://img.shields.io/badge/GitHub-Repository-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/shilvasilvia/project_final_uas_cargovision)
[![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Testing](https://img.shields.io/badge/Tests-34%20PASSED-10B981?style=for-the-badge&logo=phpunit&logoColor=white)](https://phpunit.de)

**Global Supply Chain Risk Intelligence System (CargoVision)** adalah aplikasi berbasis web cerdas untuk memantau, menganalisis, dan mengkalkulasi skor risiko rantai pasok (supply chain) global. Sistem ini mengintegrasikan data cuaca ekstrem, analisis sentimen berita geopolitik, indikator ekonomi makro World Bank, serta pemetaan pelabuhan interaktif secara real-time.

📌 **Repository GitHub:** [https://github.com/shilvasilvia/project_final_uas_cargovision](https://github.com/shilvasilvia/project_final_uas_cargovision)

---

## ✨ Fitur Utama Sistem

### 1. 📊 Executive Risk Dashboard & Analytics
- **Summary Cards**: Total Negara, Pelabuhan Global, Shipment Aktif, dan Active Weather Alerts.
- **Peta Interaktif (Leaflet Map)**: Pemetaan kordinat lokasi pelabuhan dan pelayaran global.
- **Grafik Distribusi (Chart.js)**: Status pengiriman (*In Transit*, *Delayed*, *Delivered*) dan tingkat keparahan cuaca (*Critical*, *High*, *Moderate*, *Low*).
- **Top 5 High-Risk Countries Card**: Ranking 5 negara dengan indeks risiko tertinggi.

### 2. 👥 Role-Based Access Control (RBAC: Admin vs User)
| Modul / Fitur | 👑 Admin (`role: admin`) | 👤 User Biasa (`role: user`) |
| --- | --- | --- |
| **Master Data & Operasional** | **Full CRUD** (Tambah, Edit, Hapus) | **Read-Only** (Hanya Lihat Data) |
| **Dashboard & Analisis** | Akses data & statistik global penuh | Akses ringkasan informasi sistem |
| **Monitoring** | Kontrol monitoring global | Mengelola **Favorite Monitoring** Pribadi |
| **Report & Export** | **Bisa** Akses Laporan, Export PDF & Excel | **403 Forbidden** (Tidak Ada Akses) |

### 3. 🗂️ Master Data & Operasional CRUD
- **Countries**: Pengelolaan data negara (Kode ISO, Ibu Kota, Region, Populasi).
- **Ports**: Pengelolaan pelabuhan global dengan kordinat geografis (Latitude, Longitude).
- **Shipments**: Monitoring pengiriman barang (*Origin*, *Destination*, *Status*, *Cargo Type*, *Risk Level*).
- **Weather Alerts**: Peringatan dini cuaca ekstrim dan badai di wilayah pelabuhan.
- **News & Sentiment Analysis**: Berita geopolitik dengan **Analisis Sentimen Otomatis** (*Positive*, *Negative*, *Neutral*).
- **Risk Score Calculator**: Service otomatis mengkalkulasi skor risiko terpadu per negara (0-100).
- **Country Comparison**: Komparasi langsung risiko dan indikator ekonomi antar 2 negara.

### 4. 📄 Reporting & Export (PDF & Excel)
- Halaman Khusus Laporan (`/reports`) dengan filter negara dan tanggal.
- **Export PDF Eksekutif** menggunakan `barryvdh/laravel-dompdf`.
- **Export Spreadsheet / CSV** untuk analisis data offline.

### 5. 🔑 REST API Complete & Security (Sanctum)
- Otentikasi API berbasis **Bearer Token** (`Laravel Sanctum`).
- Full Endpoints CRUD JSON Response untuk `countries`, `ports`, `shipments`, `weather-alerts`, `news`, dan `risk-scores`.
- File dokumentasi pengujian **Postman Collection Spec** di `public/postman_collection.json`.

---

## 🛠️ Teknologi & Library

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: SQLite / MySQL
- **Frontend**: Blade, Bootstrap 5, FontAwesome 6, Chart.js, Leaflet.js
- **API External**: World Bank API, Exchange Rate Currency API
- **Packages**: `laravel/sanctum`, `barryvdh/laravel-dompdf`, `laravel/breeze`

---

## 🚀 Panduan Instalasi & Penggunaan

### 1. Prasyarat System
- PHP >= 8.2
- Composer
- Node.js & NPM

### 2. Langkah Instalasi

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

Aplikasi dapat diakses di URL: `http://127.0.0.1:8000`

---

## 🔑 Kredensial Default Login

Sistem telah menyediakan 2 akun seeder bawaan untuk pengujian:

| Role | Email | Password | Hak Akses |
| --- | --- | --- | --- |
| **👑 Admin** | `admin@example.com` | `password` | Full Access (CRUD, Reports, PDF/Excel) |
| **👤 User Biasa** | `user@example.com` | `password` | Read-Only & Favorite Monitoring |

---

## 🧪 Pengujian Otomatis (Automated Testing)

Menjalankan seluruh 34 unit & feature test suite:

```bash
php artisan test
```

**Hasil Testing:**
```text
  Tests:    34 passed (89 assertions)
  Duration: ~2.5s
```

---

## 🌐 Dokumentasi REST API

Ekspor file Postman Collection JSON dapat di-import langsung dari lokasi:
`public/postman_collection.json`

### Key Endpoints:
- `POST /api/login` - Otentikasi User & Dapatkan Bearer Token
- `GET /api/user` - Mengambil Profil User Logged In (Sanctum)
- `GET /api/countries` - Daftar Negara (Search & Pagination)
- `GET /api/ports` - Daftar Pelabuhan
- `GET /api/shipments` - Monitoring Shipments
- `GET /api/weather-alerts` - Weather Alerts
- `GET /api/news` - Berita dengan Sentiment Analysis
- `POST /api/risk-scores/calculate` - Trigger Rekalkulasi Skor Risiko

---

## 📝 Lisensi

Aplikasi ini dikembangkan untuk kebutuhan akademik & profesional di bawah lisensi MIT.
