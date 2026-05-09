# Virtual Tour Budaya Toraja - Laravel 10 Project

**A comprehensive web-based 360° virtual tour application for Toraja cultural heritage**

## Project Overview

Virtual Tour Budaya Toraja adalah aplikasi web interaktif yang memungkinkan pengguna menjelajahi keindahan alam dan kekayaan budaya Kecamatan Baruppu', Toraja Utara melalui pengalaman 360° yang imersif.

### Features

✨ **360° Panorama Viewer**
- Interactive panorama menggunakan Pannellum.js
- Navigasi smooth dengan mouse/keyboard
- Compass dan zoom controls

🎯 **Hotspot System**
- Info points untuk penjelasan detail
- Scene navigation untuk berpindah lokasi
- Artifact links untuk informasi artefak budaya

🗺️ **Multi-Scene Virtual Tour**
- Multiple locations linked together
- Progressive navigation through scenes
- Location metadata (coordinates, descriptions)

📦 **Cultural Artifact Catalog**
- CRUD management interface
- Category filtering
- Keyword search functionality
- Detailed artifact information & cultural significance

🌐 **Bilingual Support**
- Bahasa Indonesia (default)
- English
- Easy language switching

🔐 **Admin Panel**
- Secure authentication (Laravel Breeze)
- Complete CRUD for all content
- Dashboard with statistics

## Tech Stack

### Backend
- **Laravel 10** - PHP web framework
- **MySQL** - Database (via XAMPP)
- **Composer** - PHP package manager

### Frontend
- **Blade Templates** - Laravel templating engine
- **Bootstrap 5** - Responsive CSS framework
- **Pannellum.js** - 360° viewer (CDN)
- **Font Awesome** - Icons

### Tools
- **XAMPP** - Local development environment
- **Sublime Text/VS Code** - Code editor
- **Git** - Version control

## Project Structure

```
virtual-tour/
├── app/
│   ├── Models/
│   │   ├── Scene.php
│   │   ├── Hotspot.php
│   │   ├── Artifact.php
│   │   ├── Category.php
│   │   └── User.php
│   └── Http/
│       ├── Controllers/
│       │   ├── TourController.php
│       │   ├── ArtifactController.php
│       │   └── Admin/
│       │       ├── AdminDashboardController.php
│       │       ├── SceneController.php
│       │       ├── HotspotController.php
│       │       ├── ArtifactController.php
│       │       └── CategoryController.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   ├── 2024_01_01_000002_create_scenes_table.php
│   │   ├── 2024_01_01_000003_create_hotspots_table.php
│   │   ├── 2024_01_01_000004_create_artifacts_table.php
│   │   └── 2024_01_01_000005_create_users_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php (frontend)
│   │   │   └── admin.blade.php (admin)
│   │   ├── frontend/
│   │   │   ├── index.blade.php (home)
│   │   │   └── tour.blade.php (panorama viewer)
│   │   ├── artifact/
│   │   │   ├── index.blade.php (catalog)
│   │   │   └── show.blade.php (detail)
│   │   └── admin/ (CRUD views)
│   ├── lang/
│   │   ├── en/messages.php
│   │   └── id/messages.php
├── routes/
│   └── web.php
├── public/
│   ├── uploads/
│   │   ├── scenes/
│   │   └── artifacts/
│   └── img/
├── storage/app/public/
├── .env.example
└── README.md
```

## Database Schema

### Categories Table
```sql
- id (PK)
- name (unique)
- slug (unique)
- description
- timestamps
```

### Scenes Table
```sql
- id (PK)
- title
- description
- panorama_image
- location
- latitude (decimal)
- longitude (decimal)
- is_published (boolean)
- order (integer)
- timestamps
```

### Hotspots Table
```sql
- id (PK)
- scene_id (FK → Scenes)
- title
- description
- pitch (float: -90 to 90)
- yaw (float: 0 to 360)
- type (enum: info, scene, artifact)
- linked_scene_id (FK → Scenes, nullable)
- artifact_id (FK → Artifacts, nullable)
- timestamps
```

### Artifacts Table
```sql
- id (PK)
- category_id (FK → Categories)
- name
- description
- cultural_significance
- image
- keywords
- is_published (boolean)
- timestamps
```

### Users Table
```sql
- id (PK)
- name
- email (unique)
- password (hashed)
- role (enum: admin, user)
- email_verified_at
- remember_token
- timestamps
```

## Installation & Setup

### Prerequisites
- XAMPP (PHP 8.0+, MySQL 5.7+)
- Composer
- Node.js (optional, for asset building)
- Git

### Step-by-Step Installation

1. **Navigate to XAMPP Directory**
   ```bash
   cd C:\xampp\htdocs
   ```

2. **Copy .env Configuration**
   ```bash
   cp virtual-tour\.env.example virtual-tour\.env
   ```

3. **Update .env File**
   ```
   APP_KEY=base64:YOUR_BASE64_KEY_HERE
   DB_DATABASE=virtual_tour_budaya_toraja
   DB_USERNAME=root
   DB_PASSWORD=
   APP_LOCALE=id
   ```

4. **Install PHP Dependencies**
   ```bash
   cd virtual-tour
   composer install
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Create Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create new database: `virtual_tour_budaya_toraja`
   - Collation: `utf8mb4_unicode_ci`

7. **Run Migrations**
   ```bash
   php artisan migrate
   ```

8. **Seed Sample Data**
   ```bash
   php artisan db:seed
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   ```
   - Access at: http://localhost:8000

## Usage

### Frontend Routes
- `/` - Homepage dengan daftar scenes
- `/tour/{scene}` - 360° panorama viewer
- `/artifacts` - Artifact catalog dengan filter & search
- `/artifacts/{artifact}` - Artifact detail page

### Admin Panel Routes
- `/admin` - Dashboard (auth required)
- `/admin/scenes` - Manage scenes
- `/admin/hotspots` - Manage hotspots
- `/admin/artifacts` - Manage artifacts
- `/admin/categories` - Manage categories

### Admin Login
- **Email:** admin@toraja.test
- **Password:** password

## Adding Content

### Adding a New Scene

1. Go to Admin → Manage Scenes
2. Click "Add New"
3. Fill in:
   - Title (e.g., "Lembang Baruppu'")
   - Description
   - Location
   - Latitude & Longitude
   - Panorama image (equirectangular format recommended)
   - Order (for sorting)
4. Click Save

### Adding Hotspots

1. Go to Admin → Manage Hotspots
2. Click "Add New"
3. Select target Scene
4. Enter Title & Description
5. Set Pitch (vertical angle: -90 down, 0 horizontal, 90 up)
6. Set Yaw (horizontal angle: 0 north, 90 east, 180 south, 270 west)
7. Choose Type:
   - **Info**: Display information
   - **Scene**: Link to another scene
   - **Artifact**: Link to artifact
8. Click Save

### Adding Artifacts

1. Go to Admin → Manage Artifacts
2. Click "Add New"
3. Select Category
4. Fill in Name, Description, Cultural Significance
5. Upload image
6. Add keywords (comma-separated)
7. Click Save

## Key Middleware & Auth

- **Auth Guard**: Uses Laravel's default session-based authentication
- **Admin Middleware**: Custom middleware checks `role === 'admin'`
- **Localization**: URL-based locale switching stored in session

## Language Localization

Language files located in `resources/lang/{locale}/messages.php`:

```php
// Accessing translations in Blade
{{ __('messages.home') }}
{{ __('messages.virtual_tour') }}

// Switching language
// GET /language/{locale}
```

## File Upload Handling

- **Scenes**: Max 10MB, JPG/PNG → stored in `public/uploads/scenes/`
- **Artifacts**: Max 5MB, JPG/PNG → stored in `public/uploads/artifacts/`

## Security Features

✓ CSRF Protection (Laravel token validation)
✓ Password Hashing (bcrypt)
✓ SQL Injection Prevention (Eloquent ORM)
✓ XSS Protection (Blade escaping)
✓ Rate Limiting (optional, can be added)

## Performance Optimization

- Database indexes on foreign keys
- Pagination for large datasets
- Image optimization recommended
- CDN for Pannellum.js & Bootstrap

## Troubleshooting

### Database Connection Error
```bash
# Check .env DB_* values
# Ensure MySQL is running in XAMPP
# Verify database exists
```

### Storage/Cache Permission Errors
```bash
chmod -R 777 storage bootstrap/cache
```

### Migrations Failed
```bash
# Rollback and retry
php artisan migrate:rollback
php artisan migrate --seed
```

### Blade Compilation Errors
```bash
php artisan cache:clear
php artisan view:clear
```

## Future Enhancements

- [ ] 360° video support
- [ ] User comments & ratings
- [ ] Social sharing integration
- [ ] Multi-language admin panel
- [ ] AR/VR compatibility
- [ ] Mobile app (React Native)
- [ ] API documentation (Swagger)
- [ ] Analytics dashboard
- [ ] Email notifications
- [ ] Backup & restore functionality

## Sample Data

The `DatabaseSeeder` includes sample data for:
- **Locations**: Lembang Baruppu', Liang Alang, Makam Ma'nene
- **Artifacts**: Tongkonan, Ma'nene ritual, Keris Toraja, Li'pa', Si Galagala
- **Categories**: Tradisi & Budaya, Bangunan Tradisional, Upacara & Ritual
- **Admin Account**: admin@toraja.test / password

## Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## License

This project is open source and available under the MIT License.

## Contact

**Project:** Virtual Tour Budaya Toraja  
**Location:** Kecamatan Baruppu', Toraja Utara, Sulawesi Selatan, Indonesia  
**Email:** info@toraja-tour.id  
**Website:** www.toraja-tour.id (coming soon)

---

**Created:** 2024  
**Version:** 1.0.0  
**Status:** Development

Selamat Datang ke Budaya Toraja! 🇮🇩
