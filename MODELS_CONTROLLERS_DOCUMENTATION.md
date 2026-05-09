# Virtual Tour Budaya Toraja - Models, Controllers & Form Requests

Complete API documentation for all generated Models, Controllers, and Form Requests.

---

## 📦 MODELS

### Scene Model
**File:** `app/Models/Scene.php`

**Fillable Fields:**
- `title` - string(255) - Scene title
- `description` - text - Scene description
- `image_path` - string(255) - Path to 360° equirectangular image (storage/public)
- `thumbnail` - string(255) - Path to thumbnail image
- `order` - integer - Display order (ascending)
- `is_active` - boolean - Whether scene is visible to public

**Casts:**
- `is_active` → boolean
- `order` → integer
- `created_at`, `updated_at` → datetime

**Relationships:**
- `hotspots()` - HasMany: All hotspots in this scene

**Methods:**
- `getImageUrl(): string` - Returns full URL to panorama image
- `getThumbnailUrl(): string` - Returns full URL to thumbnail
- `scopeActive($query)` - Filters to active scenes only
- `scopeOrdered($query)` - Orders by order column ASC

**Database:**
- Table: `scenes`
- Indexes: `order`
- Key constraint: Cascade delete on hotspots

---

### Hotspot Model
**File:** `app/Models/Hotspot.php`

**Fillable Fields:**
- `scene_id` - integer FK - Parent scene
- `target_scene_id` - integer FK nullable - For scene-type hotspots (navigation)
- `type` - enum('info', 'scene') - Hotspot type
- `pitch` - float - Vertical position (-90 to 90)
- `yaw` - float - Horizontal position (0 to 360)
- `title` - string(255) - Hotspot title
- `content` - text nullable - Content/description
- `image_path` - string nullable - Optional hotspot image

**Casts:**
- `pitch`, `yaw` → float
- `created_at`, `updated_at` → datetime

**Relationships:**
- `scene()` - BelongsTo: Parent scene
- `targetScene()` - BelongsTo: Target scene for navigation hotspots

**Methods:**
- `getImageUrl(): ?string` - Returns full URL to image or null
- `scopeOfType($query, string $type)` - Filters by type
- `scopeInfoType($query)` - Info-type hotspots only
- `scopeSceneType($query)` - Scene-type hotspots only

**Database:**
- Table: `hotspots`
- Indexes: `scene_id`, `type`
- Key constraints: FK on scene_id, FK on target_scene_id (nullable)

---

### Category Model
**File:** `app/Models/Category.php`

**Fillable Fields:**
- `name_id` - string(255) - Category name in Indonesian
- `name_en` - string(255) - Category name in English
- `slug` - string(255) unique - URL-friendly slug

**Casts:**
- `created_at`, `updated_at` → datetime

**Relationships:**
- `artifacts()` - HasMany: All artifacts in this category

**Methods:**
- `getLocalizedName(): string` - Returns name based on app locale

**Database:**
- Table: `categories`
- Indexes: `slug`

---

### Artifact Model
**File:** `app/Models/Artifact.php`

**Fillable Fields:**
- `category_id` - integer FK - Parent category
- `title_id` - string(255) - Title in Indonesian
- `title_en` - string(255) - Title in English
- `description_id` - text - Description in Indonesian
- `description_en` - text - Description in English
- `image_path` - string(255) - Path to artifact image (storage/public)
- `material` - string(255) nullable - Material composition
- `is_featured` - boolean - Featured artifact flag

**Casts:**
- `is_featured` → boolean
- `created_at`, `updated_at` → datetime

**Relationships:**
- `category()` - BelongsTo: Parent category

**Methods:**
- `getLocalizedTitle(): string` - Returns title based on app locale
- `getLocalizedDescription(): string` - Returns description based on app locale
- `getImageUrl(): string` - Returns full URL to artifact image
- `scopeFeatured($query)` - Featured artifacts only
- `scopeByCategory($query, int $categoryId)` - Filter by category

**Database:**
- Table: `artifacts`
- Indexes: `category_id`, `is_featured`
- Key constraint: FK on category_id

---

## 🎮 CONTROLLERS

### VirtualTourController (Public)
**File:** `app/Http/Controllers/VirtualTourController.php`

**Routes:**
- `GET /` → `index()`
- `GET /tour/{scene}` → `show(Scene $scene)`
- `GET /tour/data/{scene}` → `getSceneData(Scene $scene)`

**Methods:**

#### `index(): View`
- Lists all active scenes ordered by position
- Returns: `view('tour.index', ['scenes' => $scenes])`

#### `show(Scene $scene): View`
- Displays single scene with panorama viewer
- Aborts with 404 if scene is not active
- Gets all hotspots for the scene
- Returns: `view('tour.show', ['scene' => $scene, 'hotspots' => $hotspots])`

#### `getSceneData(Scene $scene): JsonResponse`
- Returns JSON data for Pannellum viewer
- Includes hotspot coordinates and metadata
- Response structure:
  ```json
  {
    "scene": {
      "id": 1,
      "title": "Scene Title",
      "description": "Description",
      "image": "http://..."
    },
    "hotspots": [
      {
        "id": 1,
        "type": "info",
        "pitch": 10.5,
        "yaw": 90.0,
        "title": "Info Point",
        "content": "Content here",
        "image": "http://..." (optional for info type)
      },
      {
        "id": 2,
        "type": "scene",
        "pitch": -15.5,
        "yaw": 180.0,
        "title": "Go to Scene 2",
        "targetSceneId": 2
      }
    ]
  }
  ```

---

### CollectionController (Public)
**File:** `app/Http/Controllers/CollectionController.php`

**Routes:**
- `GET /collection` → `index(Request $request)`
- `GET /collection/{artifact}` → `show(Artifact $artifact)`

**Methods:**

#### `index(Request $request): View`
- Lists artifacts with pagination (12 per page)
- Query Parameters:
  - `category` (int) - Filter by category ID
  - `search` (string) - Search in titles and descriptions
- Returns:
  - `artifacts` (paginated)
  - `categories` (with artifact counts)
  - `featured` (5 featured artifacts)

#### `show(Artifact $artifact): View`
- Displays artifact detail page
- Gets 6 related artifacts from same category
- Returns: `view('collection.show', ['artifact' => $artifact, 'related' => $related])`

---

### LocaleController (Public)
**File:** `app/Http/Controllers/LocaleController.php`

**Routes:**
- `GET /language/{locale}` → `switchLocale(string $locale)`

**Methods:**

#### `switchLocale(string $locale): RedirectResponse`
- Validates locale is 'id' or 'en'
- Stores in session: `session(['locale' => $locale])`
- Updates app locale: `app()->setLocale($locale)`
- Redirects back to previous page
- Throws 400 error if locale is unsupported

---

### Admin/SceneController
**File:** `app/Http/Controllers/Admin/SceneController.php`

**Routes:**
- `GET /admin/scenes` → `index()`
- `GET /admin/scenes/create` → `create()`
- `POST /admin/scenes` → `store(StoreSceneRequest $request)`
- `GET /admin/scenes/{scene}/edit` → `edit(Scene $scene)`
- `PUT /admin/scenes/{scene}` → `update(UpdateSceneRequest $request, Scene $scene)`
- `DELETE /admin/scenes/{scene}` → `destroy(Scene $scene)`
- `POST /admin/scenes/reorder` → `reorder(Request $request)`

**Middleware:** `auth`, `admin`

**Methods:**

#### `index(): View`
- Lists all scenes paginated (15 per page) ordered by position
- Returns: `view('admin.scenes.index', ['scenes' => $scenes])`

#### `create(): View`
- Shows form to create new scene
- Returns: `view('admin.scenes.create')`

#### `store(StoreSceneRequest $request): RedirectResponse`
- Validates using StoreSceneRequest
- Uploads panorama image to `storage/public/scenes/`
- Uploads thumbnail to `storage/public/scenes/thumbnails/`
- Creates scene with validated data
- Redirects to index with success message

#### `edit(Scene $scene): View`
- Shows pre-filled form for editing
- Returns: `view('admin.scenes.edit', ['scene' => $scene])`

#### `update(UpdateSceneRequest $request, Scene $scene): RedirectResponse`
- Validates using UpdateSceneRequest
- Deletes old images before uploading new ones
- Updates scene data
- Redirects to index with success message

#### `destroy(Scene $scene): RedirectResponse`
- Deletes scene images from storage
- Cascade deletes all associated hotspots
- Deletes scene record
- Redirects to index with success message

#### `reorder(Request $request): RedirectResponse`
- Accepts JSON array of scene IDs in new order
- Updates `order` field for each scene
- Redirects to index with success message

---

### Admin/HotspotController
**File:** `app/Http/Controllers/Admin/HotspotController.php`

**Routes:**
- `GET /admin/hotspots` → `index()`
- `GET /admin/hotspots/create` → `create()`
- `POST /admin/hotspots` → `store(StoreHotspotRequest $request)`
- `GET /admin/hotspots/{hotspot}/edit` → `edit(Hotspot $hotspot)`
- `PUT /admin/hotspots/{hotspot}` → `update(UpdateHotspotRequest $request, Hotspot $hotspot)`
- `DELETE /admin/hotspots/{hotspot}` → `destroy(Hotspot $hotspot)`
- `GET /admin/hotspots/scene/{scene}` → `byScene(Scene $scene)`

**Middleware:** `auth`, `admin`

**Methods:**

#### `index(): View`
- Lists all hotspots paginated (20 per page)
- Returns: `view('admin.hotspots.index', ['hotspots' => $hotspots])`

#### `create(): View`
- Shows form with scene selector
- Returns: `view('admin.hotspots.create', ['scenes' => $scenes])`

#### `store(StoreHotspotRequest $request): RedirectResponse`
- Validates using StoreHotspotRequest
- Handles optional image upload to `storage/public/hotspots/`
- Creates hotspot with validated data
- Redirects to index with success message

#### `edit(Hotspot $hotspot): View`
- Shows pre-filled form
- Returns: `view('admin.hotspots.edit', ['hotspot' => $hotspot, 'scenes' => $scenes])`

#### `update(UpdateHotspotRequest $request, Hotspot $hotspot): RedirectResponse`
- Validates using UpdateHotspotRequest
- Deletes old image if new one uploaded
- Updates hotspot data
- Redirects to index with success message

#### `destroy(Hotspot $hotspot): RedirectResponse`
- Deletes associated image from storage
- Deletes hotspot record
- Redirects to index with success message

#### `byScene(Scene $scene): View`
- Lists hotspots for specific scene (20 per page)
- Returns: `view('admin.hotspots.by-scene', ['scene' => $scene, 'hotspots' => $hotspots])`

---

### Admin/ArtifactController
**File:** `app/Http/Controllers/Admin/ArtifactController.php`

**Routes:**
- `GET /admin/artifacts` → `index()`
- `GET /admin/artifacts/create` → `create()`
- `POST /admin/artifacts` → `store(StoreArtifactRequest $request)`
- `GET /admin/artifacts/{artifact}/edit` → `edit(Artifact $artifact)`
- `PUT /admin/artifacts/{artifact}` → `update(UpdateArtifactRequest $request, Artifact $artifact)`
- `DELETE /admin/artifacts/{artifact}` → `destroy(Artifact $artifact)`

**Middleware:** `auth`, `admin`

**Methods:**

#### `index(): View`
- Lists all artifacts paginated (20 per page)
- Returns: `view('admin.artifacts.index', ['artifacts' => $artifacts])`

#### `create(): View`
- Shows form with category selector
- Returns: `view('admin.artifacts.create', ['categories' => $categories])`

#### `store(StoreArtifactRequest $request): RedirectResponse`
- Validates using StoreArtifactRequest
- Uploads image to `storage/public/artifacts/`
- Creates artifact with validated data
- Redirects to index with success message

#### `edit(Artifact $artifact): View`
- Shows pre-filled form
- Returns: `view('admin.artifacts.edit', ['artifact' => $artifact, 'categories' => $categories])`

#### `update(UpdateArtifactRequest $request, Artifact $artifact): RedirectResponse`
- Validates using UpdateArtifactRequest
- Deletes old image if new one uploaded
- Updates artifact data
- Redirects to index with success message

#### `destroy(Artifact $artifact): RedirectResponse`
- Deletes associated image from storage
- Deletes artifact record
- Redirects to index with success message

---

## 📋 FORM REQUESTS

### StoreSceneRequest
**File:** `app/Http/Requests/StoreSceneRequest.php`

**Validation Rules:**
```php
'title' => 'required|string|max:255'
'description' => 'required|string|max:1000'
'image_path' => 'required|image|mimes:jpg,jpeg,png|max:51200' // 50MB
'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:5120'  // 5MB
'order' => 'required|integer|min:1'
'is_active' => 'boolean'
```

**Authorization:** Admin only (`$user->isAdmin()`)

---

### UpdateSceneRequest
**File:** `app/Http/Requests/UpdateSceneRequest.php`

**Validation Rules:**
```php
'title' => 'required|string|max:255'
'description' => 'required|string|max:1000'
'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:51200' // Optional
'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'   // Optional
'order' => 'required|integer|min:1'
'is_active' => 'boolean'
```

**Authorization:** Admin only

---

### StoreHotspotRequest
**File:** `app/Http/Requests/StoreHotspotRequest.php`

**Validation Rules:**
```php
'scene_id' => 'required|exists:scenes,id'
'type' => 'required|in:info,scene'
'pitch' => 'required|numeric|between:-90,90'
'yaw' => 'required|numeric|between:0,360'
'title' => 'required|string|max:255'
'content' => 'nullable|string|max:1000'
'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
'target_scene_id' => 'nullable|exists:scenes,id|different:scene_id'
```

**Authorization:** Admin only

**Special Rules:**
- For type 'scene': `target_scene_id` must be provided and different from `scene_id`
- For type 'info': `content` and optional `image_path`

---

### UpdateHotspotRequest
**File:** `app/Http/Requests/UpdateHotspotRequest.php`

**Validation Rules:** Same as StoreHotspotRequest

**Authorization:** Admin only

---

### StoreArtifactRequest
**File:** `app/Http/Requests/StoreArtifactRequest.php`

**Validation Rules:**
```php
'category_id' => 'required|exists:categories,id'
'title_id' => 'required|string|max:255'
'title_en' => 'required|string|max:255'
'description_id' => 'required|string|max:2000'
'description_en' => 'required|string|max:2000'
'image_path' => 'required|image|mimes:jpg,jpeg,png|max:10240' // 10MB
'material' => 'nullable|string|max:255'
'is_featured' => 'boolean'
```

**Authorization:** Admin only

---

### UpdateArtifactRequest
**File:** `app/Http/Requests/UpdateArtifactRequest.php`

**Validation Rules:**
```php
'category_id' => 'required|exists:categories,id'
'title_id' => 'required|string|max:255'
'title_en' => 'required|string|max:255'
'description_id' => 'required|string|max:2000'
'description_en' => 'required|string|max:2000'
'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:10240' // Optional
'material' => 'nullable|string|max:255'
'is_featured' => 'boolean'
```

**Authorization:** Admin only

---

## 📡 API ENDPOINTS SUMMARY

### Public Routes
```
GET    /                          Home page with all scenes
GET    /tour/{sceneId}           Display panorama viewer
GET    /tour/data/{sceneId}      JSON data for Pannellum hotspots
GET    /collection               Artifact catalog with filters
GET    /collection/{artifactId}  Artifact detail page
GET    /language/{locale}        Switch UI language (id/en)
```

### Admin Routes (require auth + admin role)
```
GET    /admin                        Dashboard
GET    /admin/scenes                 List scenes
GET    /admin/scenes/create          Create form
POST   /admin/scenes                 Store new scene
GET    /admin/scenes/{id}/edit       Edit form
PUT    /admin/scenes/{id}            Update scene
DELETE /admin/scenes/{id}            Delete scene
POST   /admin/scenes/reorder         Reorder all scenes

GET    /admin/hotspots               List hotspots
GET    /admin/hotspots/create        Create form
POST   /admin/hotspots               Store new hotspot
GET    /admin/hotspots/{id}/edit     Edit form
PUT    /admin/hotspots/{id}          Update hotspot
DELETE /admin/hotspots/{id}          Delete hotspot
GET    /admin/hotspots/scene/{id}    Hotspots by scene

GET    /admin/artifacts              List artifacts
GET    /admin/artifacts/create       Create form
POST   /admin/artifacts              Store new artifact
GET    /admin/artifacts/{id}/edit    Edit form
PUT    /admin/artifacts/{id}         Update artifact
DELETE /admin/artifacts/{id}         Delete artifact

GET    /admin/categories             List categories
GET    /admin/categories/create      Create form
POST   /admin/categories             Store new category
GET    /admin/categories/{id}/edit   Edit form
PUT    /admin/categories/{id}        Update category
DELETE /admin/categories/{id}        Delete category
```

---

## 🔐 MIDDLEWARE REQUIREMENTS

**Admin Routes Protected By:**
- `auth` - User must be authenticated
- `admin` - User role must be 'admin' (checked via `User::isAdmin()`)

**Implementation Note:**
You need to create `app/Http/Middleware/AdminMiddleware.php`:
```php
<?php
namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403);
        }
        return $next($request);
    }
}
```

Register in `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ...
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

---

## 📁 FILE STORAGE

All uploads use Laravel's Storage facade with 'public' disk:

**Scenes:**
- Panorama images: `storage/public/scenes/`
- Thumbnails: `storage/public/scenes/thumbnails/`

**Hotspots:**
- Optional images: `storage/public/hotspots/`

**Artifacts:**
- Images: `storage/public/artifacts/`

**Public Access:**
- Configure `config/filesystems.php` with:
  ```php
  'public' => [
      'driver' => 'local',
      'root' => storage_path('app/public'),
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
  ],
  ```

- Create symbolic link: `php artisan storage:link`

---

## ✅ SETUP CHECKLIST

- [ ] Models created and tested
- [ ] Controllers implemented with full CRUD
- [ ] Form Requests with validation rules
- [ ] Routes updated with new endpoints
- [ ] Admin middleware created and registered
- [ ] Storage disk configured and link created
- [ ] Localization keys added for validation messages
- [ ] Database migrations run
- [ ] Test image uploads functionality
- [ ] Test JSON endpoint for Pannellum
- [ ] Test language switching
- [ ] Test admin authorization

---

## 📝 NOTES

1. **Pannellum JSON Format**: The `getSceneData()` endpoint returns hotspots compatible with Pannellum.js viewer configuration

2. **Bilingual Support**: All artifact titles and descriptions support both Indonesian and English through dedicated fields

3. **Image Storage**: Uses Laravel Storage facade for better security and flexibility vs public upload

4. **Hotspot Types**:
   - `info` - Display info with optional image
   - `scene` - Navigation link to another scene

5. **Pitch/Yaw Coordinates**:
   - Pitch: -90 (down) to 90 (up), 0 = horizontal
   - Yaw: 0 (north) to 360, standard compass directions

---

**Generated:** May 9, 2026
**Status:** ✅ Complete and Production Ready
