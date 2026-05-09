# Virtual Tour Budaya Toraja - Setup Guide

## 🚀 Quick Start

Panduan lengkap setup dan konfigurasi aplikasi Virtual Tour Budaya Toraja.

### Prerequisites
- XAMPP (PHP 8.0+, MySQL 5.7+)
- Composer (PHP package manager)
- Git (optional)
- Text Editor (Sublime Text / VS Code)

## 📋 Step-by-Step Setup

### 1. Database Setup

**Method A: Using phpMyAdmin**
```
1. Buka http://localhost/phpmyadmin
2. Klik "New" atau "Buat Database Baru"
3. Nama Database: virtual_tour_budaya_toraja
4. Collation: utf8mb4_unicode_ci
5. Klik "Create"
```

**Method B: Using MySQL Command Line**
```bash
mysql -u root
CREATE DATABASE virtual_tour_budaya_toraja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 2. Copy Environment File
```bash
cd C:\xampp\htdocs\virtual-tour
copy .env.example .env
```

### 3. Configure .env File

Edit file `.env` dengan text editor:

```env
APP_NAME="Virtual Tour Budaya Toraja"
APP_ENV=local
APP_KEY=base64:... (jangan diubah, akan dihasilkan otomatis)
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=virtual_tour_budaya_toraja
DB_USERNAME=root
DB_PASSWORD=

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_SUPPORTED_LOCALES=id,en
```

### 4. Install Dependencies

```bash
cd C:\xampp\htdocs\virtual-tour
composer install
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

Verifikasi file `.env` - pastikan `APP_KEY` sudah terisi.

### 6. Run Migrations

```bash
php artisan migrate
```

Output yang diharapkan:
```
Migrating: 2024_01_01_000001_create_categories_table
Migrated:  2024_01_01_000001_create_categories_table (X.XXs)
...
```

### 7. Seed Sample Data

```bash
php artisan db:seed
```

Output:
```
Seeding: Database\Seeders\DatabaseSeeder
Seeded:  Database\Seeders\DatabaseSeeder (X.XXs)
```

### 8. Create Storage Link

```bash
php artisan storage:link
```

Ini membuat symbolic link dari `storage/app/public` ke `public/storage`.

### 9. Start Development Server

```bash
php artisan serve
```

Output:
```
   INFO  Server running on [http://127.0.0.1:8000].
```

### 10. Access Application

**Frontend:**
- URL: http://localhost:8000
- Language Switcher: Top right navbar

**Admin Panel:**
- URL: http://localhost:8000/admin
- Email: admin@toraja.test
- Password: password

## 🎯 Workflow

### Adding Your First Scene

1. Akses Admin Panel (http://localhost:8000/admin)
2. Masuk dengan admin credentials
3. Menu → Manage Scenes
4. Klik "Add New"
5. Isi form:
   - Title: "Nama Lokasi"
   - Description: "Deskripsi lokasi"
   - Location: "Alamat spesifik"
   - Latitude: "-2.7833" (format: -X.XXXX)
   - Longitude: "119.9167" (format: XXX.XXXX)
   - Panorama Image: Upload file JPG/PNG (equirectangular)
   - Order: 1 (urutan tampilan)
   - Published: ✓ (centang)
6. Klik Save

**Note:** Untuk file panorama berkualitas tinggi, gunakan:
- Ukuran minimal: 2048x1024px
- Format: JPG (compressed) atau PNG
- Tipe: Equirectangular projection (360° photos)

### Adding Hotspots

1. Manage Hotspots → Add New
2. Pilih Scene target
3. Isi Title & Description
4. Set Pitch & Yaw (gunakan aplikasi editor hotspot atau trial-error)
   - Pitch: -90 (bawah), 0 (tengah), +90 (atas)
   - Yaw: 0 (utara), 90 (timur), 180 (selatan), 270 (barat)
5. Pilih Type:
   - Info: Tampilkan informasi saja
   - Scene: Link ke scene lain
   - Artifact: Link ke artifact
6. Save

### Adding Artifacts

1. Manage Artifacts → Add New
2. Pilih Category (atau buat baru di Manage Categories)
3. Isi semua field:
   - Name: "Nama Artefak"
   - Description: "Penjelasan detail"
   - Cultural Significance: "Nilai budaya"
   - Image: Upload foto
   - Keywords: "keyword1, keyword2, keyword3"
4. Save

## 🗂️ Important Directories

| Direktori | Fungsi |
|-----------|--------|
| `app/Models/` | Model database (Scene, Artifact, dll) |
| `app/Http/Controllers/` | Logic aplikasi |
| `resources/views/` | Template HTML (Blade) |
| `resources/lang/` | Bahasa (EN, ID) |
| `database/migrations/` | Schema database |
| `database/seeders/` | Sample data |
| `public/uploads/` | Upload gambar (scenes, artifacts) |
| `routes/web.php` | Definisi routes |

## 🔧 Useful Commands

```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Reset database (HATI-HATI!)
php artisan migrate:rollback
php artisan migrate --seed

# Create new admin user
php artisan tinker
>>> User::create(['name' => 'Admin Baru', 'email' => 'admin2@test.com', 'password' => bcrypt('password'), 'role' => 'admin'])
>>> exit

# Check routes
php artisan route:list

# Generate docs (optional)
php artisan tinker
```

## 🌐 Language Settings

### Current Locale
```php
// Di Blade template
{{ app()->getLocale() }}  // returns 'id' or 'en'

// Switch language
// URL: /language/id atau /language/en
```

### Add New Translation Key

1. Buka `resources/lang/id/messages.php`
2. Tambah key baru:
   ```php
   'new_key' => 'Nilai dalam Bahasa Indonesia',
   ```
3. Buka `resources/lang/en/messages.php`
4. Tambah key yang sama:
   ```php
   'new_key' => 'Value in English',
   ```
5. Di template, gunakan:
   ```blade
   {{ __('messages.new_key') }}
   ```

## 🚨 Troubleshooting

### Error: "No application encryption key has been specified"
**Solusi:** Jalankan `php artisan key:generate`

### Error: "SQLSTATE[HY000] [2002] No such file or directory"
**Solusi:**
- Pastikan MySQL sudah running di XAMPP
- Verifikasi DB_HOST, DB_PORT di .env

### Error: "The stream or file "/path/to/storage/logs/laravel.log" could not be opened"
**Solusi:** 
```bash
chmod -R 777 storage bootstrap/cache
```

### Error: "Class 'App\Models\Scene' not found"
**Solusi:**
```bash
composer dump-autoload
php artisan cache:clear
```

### Panorama tidak loading
**Solusi:**
- Cek file image ada di `public/uploads/scenes/`
- Cek format: harus equirectangular
- Cek ukuran file: jangan terlalu besar (>10MB)

## 📝 File Structure Summary

```
virtual-tour/
│
├── app/
│   ├── Models/              ← Database models
│   └── Http/Controllers/    ← Business logic
│
├── database/
│   ├── migrations/          ← Table schema
│   └── seeders/             ← Sample data
│
├── resources/
│   ├── views/               ← HTML templates
│   └── lang/                ← Translations (id, en)
│
├── routes/
│   └── web.php              ← URL routes
│
├── public/
│   ├── uploads/             ← User uploads
│   ├── img/                 ← Static images
│   └── storage/ → ../storage/app/public/
│
├── storage/
│   └── app/public/          ← Persistent storage
│
├── .env                     ← Configuration (local)
├── .env.example             ← Configuration template
├── composer.json            ← PHP dependencies
├── README.md                ← Project documentation
└── SETUP.md                 ← Setup guide (this file)
```

## ✅ Verification Checklist

Sebelum deploy ke production:

- [ ] Database sudah dibuat dan tersync
- [ ] File `.env` sudah dikonfigurasi
- [ ] `php artisan serve` berjalan tanpa error
- [ ] Homepage dapat diakses (http://localhost:8000)
- [ ] Admin panel dapat diakses dengan login
- [ ] Sample scenes dan artifacts ada di database
- [ ] Panorama images dapat ditampilkan
- [ ] Hotspots dapat diklik dan berfungsi
- [ ] Bilingual switching bekerja (EN/ID)
- [ ] Upload file berfungsi

## 📞 Support & Notes

**Untuk pertanyaan:**
- Dokumentasi: Baca README.md lengkap
- Laravel Docs: https://laravel.com/docs/10.x
- Pannellum Docs: https://pannellum.org/documentation/overview/
- Bootstrap Docs: https://getbootstrap.com/docs/5.3/

**Development Tips:**
- Gunakan `php artisan tinker` untuk testing command
- Buka browser DevTools (F12) untuk debug frontend
- Check Laravel logs: `storage/logs/laravel.log`
- Enable query logging untuk debug SQL

Selamat mengembangkan aplikasi! 🚀
