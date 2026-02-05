## Tentang Aplikasi Kepegawaian

aplikasi manajemen kepegawaian yang dirancang buat membantu HRD atau bagian kepegawaian dalam mengelola data karyawan dengan lebih mudah dan efisien.

fitur : sistem autentikasi, role management, hak ases menu, maste pegawai, master 

## How to install

**Prerequisites:**
- PHP >= 8.4
- Composer
- Node.js & NPM
- PostgreSQL

**Langkah instalasi:**

1. **Clone repository ini**
   ```bash
   git clone [repository-url]
   cd kepegawaian
   ```

2. **Install dependencies PHP**
   ```bash
   composer install
   ```

3. **Install dependencies JavaScript**
   ```bash
   npm install
   ```

4. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi database**
   - Edit file `.env`
   - Sesuaikan konfigurasi database (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

6. **Jalankan migrasi & seeder**
   ```bash
   php artisan migrate --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   # atau untuk development:
   npm run dev
   ```

**Default login:**
- Email: aldi
- Password: password


## Database Structure
- baca : DATABASE.md

## Rules
- variable wajib camelcase
- query > easy wajib menggunakan raw query
- wajib menggunakan migrasi
- jika ada penggunaan kode berulang wajib menggunakan helper
- jika query select dirasa terlalu panjang gunakan view