# Panduan Hosting Aplikasi Laravel ke Render.com

Seluruh konfigurasi Docker dan Render Blueprint telah disiapkan di repository ini. Ikuti langkah-langkah berikut untuk me-host aplikasi ke **Render.com**.

---

## Cara 1: Menggunakan Render Blueprint (Sangat Direkomendasikan / Otomatis)

1. **Push Kodingan ke GitHub**
   Pastikan file-file berikut sudah di-commit dan di-push ke repository GitHub Anda:
   - `Dockerfile`
   - `render.yaml`
   - `.dockerignore`
   - `docker/` (seluruh isi folder `docker`)

2. **Buka Render.com**
   - Login / Register di [dashboard.render.com](https://dashboard.render.com).

3. **Buat New Blueprint**
   - Klik tombol **New +** di pojok kanan atas dashboard.
   - Pilih **Blueprint**.
   - Hubungkan (*Connect*) akun GitHub Anda dan pilih repository project ini.

4. **Deploy Application**
   - Berikan nama Service (misal: `cargo-vision-app`).
   - Render akan otomatis membaca file `render.yaml` dan mengkonfigurasi Web Service beserta Environment Variables secara otomatis (termasuk generate `APP_KEY`).
   - Klik **Apply**.
   - Tunggu proses Build & Deploy selesai.

5. **Set APP_URL**
   - Setelah deploy selesai, Render akan memberikan URL (misalnya `https://cargo-vision-app.onrender.com`).
   - Masuk ke tab **Environment** pada Web Service Anda di Render, edit variable `APP_URL` dan isikan URL tersebut.

---

## Cara 2: Membuat Web Service Manual di Render

Jika ingin membuat Web Service secara manual tanpa Blueprint:

1. **New Web Service**
   - Klik **New +** -> **Web Service**.
   - Hubungkan ke repository GitHub Anda.

2. **Konfigurasi Web Service**:
   - **Name**: `cargo-vision-app` (atau nama lain pilihan Anda)
   - **Region**: Singapore (atau terdekat)
   - **Language**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Instance Type**: Free / Starter

3. **Tambahkan Environment Variables** (Di menu Environment):
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
   - `APP_KEY`: *(Jalankan `php artisan key:generate --show` di lokal lalu paste nilainya ke sini)*
   - `APP_URL`: `https://<nama-app-anda>.onrender.com`
   - `LOG_CHANNEL`: `stderr`
   - `DB_CONNECTION`: `sqlite`
   - `DB_DATABASE`: `/var/www/html/database/database.sqlite`
   - `SESSION_DRIVER`: `database`
   - `CACHE_STORE`: `database`

4. Klik **Create Web Service** dan tunggu hingga deployment berhasil!

---

## Opsional: Menggunakan PostgreSQL (Render Managed Database)

Jika ingin menggunakan PostgreSQL gratis dari Render alih-alih SQLite:
1. Di Render Dashboard, buat **New +** -> **PostgreSQL**.
2. Setelah PostgreSQL aktif, salin kredensialnya.
3. Di Web Service aplikasi Anda, ubah Environment Variables berikut:
   - `DB_CONNECTION`: `pgsql`
   - `DB_HOST`: *(Host dari Render PostgreSQL)*
   - `DB_PORT`: `5432`
   - `DB_DATABASE`: *(Database Name)*
   - `DB_USERNAME`: *(User Name)*
   - `DB_PASSWORD`: *(Password)*

---

### Catatan Penting
- Karena ini menggunakan Docker multi-stage, assets Vite (`npm run build`) dan dependency PHP (`composer install`) sudah otomatis diproses saat Build stage di Render.
- Migrasi database (`php artisan migrate --force`) akan otomatis berjalan saat kontainer menyala.
