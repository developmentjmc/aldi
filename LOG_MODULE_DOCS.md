# Modul Log Aktivitas

Modul Log Aktivitas adalah sistem monitoring untuk mencatat semua aktivitas pengguna dalam aplikasi kepegawaian.

## Fitur

- Mencatat aktivitas login/logout pengguna
- Melacak aktivitas CRUD (Create, Read, Update, Delete) pada setiap modul
- Monitoring akses pengguna ke berbagai halaman
- Filter dan pencarian berdasarkan tanggal, pengguna, modul, dan aksi

## Cara Penggunaan

### 1. Menggunakan Model Log

```php
use App\Models\Log;

// Mencatat log aktivitas
Log::record('Menambah data pegawai baru: John Doe', 'Pegawai', 'create');
Log::record('Mengupdate data pegawai: Jane Smith', 'Pegawai', 'update');
Log::record('Melihat detail pegawai: Bob Johnson', 'Pegawai', 'read');
Log::record('Menghapus data pegawai: Alice Brown', 'Pegawai', 'delete');
```

