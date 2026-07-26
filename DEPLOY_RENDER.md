# Panduan Deployment Laravel ke Render

Dokumen ini berisi petunjuk lengkap langkah demi langkah untuk melakukan hosting / deployment aplikasi **Global Supply Chain Risk Intelligence** ke **Render**.

---

## 📌 Mengapa Menggunakan Docker di Render?
Render tidak menyediakan *native runtime* langsung untuk PHP. Oleh karena itu, kita telah membuat konfigurasi **Docker** (`Dockerfile`, `nginx.conf`, `docker-entrypoint.sh`) yang membundel:
- **PHP 8.2 FPM** & ekstensi PHP lengkap (`pdo_mysql`, `pdo_pgsql`, `gd`, `zip`, dll.)
- **Nginx Web Server** (Konfigurasi khusus Laravel)
- **Node.js 20 & Vite** (Otomatis mem-build aset CSS & JS)
- **Automatic Migration & Config Cache** (Menjalankan `migrate --force` saat aplikasi startup)

---

## 🚀 Opsi 1: Deployment Otomatis via Render Blueprint (Sangat Direkomendasikan)

File [`render.yaml`](file:///c:/Users/MSI%20Modern/global-supply-chain-risk-intelligence/render.yaml) sudah disediakan dalam proyek ini untuk membuat **Web Service** sekaligus **PostgreSQL Database** secara otomatis.

### Langkah-langkah:
1. **Commit & Push Kode ke GitHub**:
   ```bash
   git add .
   git commit -m "Add Docker and Render deployment configuration"
   git push origin main
   ```

2. **Buka Render Dashboard**:
   - Masuk ke [dashboard.render.com](https://dashboard.render.com/).
   - Klik tombol **New +** di pojok kanan atas, lalu pilih **Blueprint**.

3. **Hubungkan Repositori GitHub**:
   - Pilih repositori `global-supply-chain-risk-intelligence`.
   - Beri nama Blueprint Instance Anda (contoh: `supply-chain-app`).
   - Klik **Apply**.

4. **Render Akan Memproses Otomatis**:
   - Render akan membuat Database PostgreSQL gratis (`supply-chain-db`).
   - Render akan membuat Web Service Docker (`global-supply-chain-risk-intelligence`).
   - `APP_KEY` akan digenerate otomatis.
   - Variabel koneksi database (`DB_HOST`, `DB_PORT`, `DB_USERNAME`, dll.) akan terhubung otomatis.

---

## 🛠️ Opsi 2: Deployment Manual via Render Dashboard

Jika Anda lebih memilih membuat layanan satu per satu secara manual:

### 1. Buat Database PostgreSQL di Render:
1. Di Dashboard Render, klik **New +** -> **PostgreSQL**.
2. Beri Nama Database: `supply-chain-db`.
3. Pilih Plan **Free**.
4. Simpan nilai **Internal Database URL** atau catat `Host`, `Database`, `User`, `Password`, `Port`.

### 2. Buat Web Service:
1. Klik **New +** -> **Web Service**.
2. Pilih repositori GitHub proyek Anda.
3. Konfigurasi dasar:
   - **Name**: `global-supply-chain-risk-intelligence`
   - **Language / Runtime**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Region**: Singapore (atau terdekat)
   - **Instance Type**: `Free`

4. Masukkan **Environment Variables** di bagian *Environment*:
   | Key | Value / Keterangan |
   |---|---|
   | `APP_NAME` | `Global Supply Chain Intelligence` |
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `APP_KEY` | *(Generate via CLI `php artisan key:generate --show` lalu paste di sini)* |
   | `APP_URL` | `https://<nama-app-anda>.onrender.com` |
   | `RUN_MIGRATIONS` | `true` |
   | `DB_CONNECTION` | `pgsql` |
   | `DB_HOST` | *(Host dari Render Database / Internal Hostname)* |
   | `DB_PORT` | `5432` |
   | `DB_DATABASE` | `supply_chain_db` |
   | `DB_USERNAME` | `supply_chain_user` |
   | `DB_PASSWORD` | *(Password dari Render Database)* |

5. Klik **Create Web Service**.

---

## ⚡ Langkah Tambahan (Seeding Data Kategori/User)

Jika aplikasi memerlukan data awal (Database Seeder):
1. Di Dashboard Render pada Web Service Anda, buka tab **Shell**.
2. Jalankan perintah:
   ```bash
   php artisan db:seed --force
   ```

---

## 📄 File Konfigurasi Terkait
- [`Dockerfile`](file:///c:/Users/MSI%20Modern/global-supply-chain-risk-intelligence/Dockerfile)
- [`nginx.conf`](file:///c:/Users/MSI%20Modern/global-supply-chain-risk-intelligence/nginx.conf)
- [`docker-entrypoint.sh`](file:///c:/Users/MSI%20Modern/global-supply-chain-risk-intelligence/docker-entrypoint.sh)
- [`render.yaml`](file:///c:/Users/MSI%20Modern/global-supply-chain-risk-intelligence/render.yaml)
