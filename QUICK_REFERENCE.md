# Quick Reference - Models, Controllers & Form Requests

## Models Overview

| Model | Table | Key Fields | Relationships |
|-------|-------|-----------|---|
| Scene | scenes | title, image_path, thumbnail, order, is_active | HasMany: hotspots |
| Hotspot | hotspots | scene_id, type, pitch, yaw, title, content | BelongsTo: scene, targetScene |
| Category | categories | name_id, name_en, slug | HasMany: artifacts |
| Artifact | artifacts | category_id, title_id, title_en, description_id, description_en, image_path, material | BelongsTo: category |

## Controllers Overview

### Public Controllers

| Controller | Methods | Routes |
|-----------|---------|--------|
| VirtualTourController | index, show, getSceneData | /, /tour/{id}, /tour/data/{id} |
| CollectionController | index, show | /collection, /collection/{id} |
| LocaleController | switchLocale | /language/{locale} |

### Admin Controllers (Protected)

| Controller | Methods | Routes |
|-----------|---------|--------|
| Admin/SceneController | index, create, store, edit, update, destroy, reorder | /admin/scenes/* |
| Admin/HotspotController | index, create, store, edit, update, destroy, byScene | /admin/hotspots/* |
| Admin/ArtifactController | index, create, store, edit, update, destroy | /admin/artifacts/* |
| Admin/CategoryController | (existing) | /admin/categories/* |

## Form Requests

| Request | Used By | Key Validations |
|---------|---------|---|
| StoreSceneRequest | SceneController@store | title, description, image_path (50MB), thumbnail (5MB), order |
| UpdateSceneRequest | SceneController@update | Same as Store but images nullable |
| StoreHotspotRequest | HotspotController@store | scene_id, type, pitch (-90 to 90), yaw (0-360), title, optional image_path (5MB) |
| UpdateHotspotRequest | HotspotController@update | Same as Store |
| StoreArtifactRequest | ArtifactController@store | category_id, title_id, title_en, description_id, description_en, image_path (10MB), material |
| UpdateArtifactRequest | ArtifactController@update | Same as Store but image nullable |

## Key Features

### Image Storage
- **Disk**: Laravel Storage 'public' disk
- **Scenes**: `storage/public/scenes/`
- **Hotspots**: `storage/public/hotspots/`
- **Artifacts**: `storage/public/artifacts/`
- **Access**: Via `asset('storage/path/to/image')`

### Localization
- All artifacts support bilingual fields (Indonesian & English)
- Session-based locale switching at `/language/{locale}`
- Localized names via model methods: `getLocalizedTitle()`, `getLocalizedName()`

### Panorama Viewer Integration
- JSON endpoint: `GET /tour/data/{sceneId}`
- Returns hotspot coordinates for Pannellum.js
- Two hotspot types:
  - `info`: Display text + optional image
  - `scene`: Navigation to another scene

### Authorization
- Admin routes require: `auth` middleware + `admin` middleware
- Checked via: `$user->isAdmin()` (returns true if role === 'admin')

## File Locations

```
app/
├── Models/
│   ├── Scene.php (✅ Updated)
│   ├── Hotspot.php (✅ Updated)
│   ├── Category.php (✅ Updated)
│   └── Artifact.php (✅ Updated)
├── Http/
│   ├── Controllers/
│   │   ├── VirtualTourController.php (✅ New)
│   │   ├── CollectionController.php (✅ New)
│   │   ├── LocaleController.php (✅ New)
│   │   └── Admin/
│   │       ├── SceneController.php (✅ Updated)
│   │       ├── HotspotController.php (✅ Updated)
│   │       ├── ArtifactController.php (✅ Updated)
│   │       └── CategoryController.php (existing)
│   └── Requests/
│       ├── StoreSceneRequest.php (✅ New)
│       ├── UpdateSceneRequest.php (✅ New)
│       ├── StoreHotspotRequest.php (✅ New)
│       ├── UpdateHotspotRequest.php (✅ New)
│       ├── StoreArtifactRequest.php (✅ New)
│       └── UpdateArtifactRequest.php (✅ New)
└── Http/
    └── Middleware/
        └── AdminMiddleware.php (⚠️ Needs creation)

routes/
└── web.php (✅ Updated - all routes defined)
```

## Common Usage Examples

### Get All Active Scenes
```php
$scenes = Scene::active()->ordered()->get();
```

### Get Scene with Hotspots
```php
$scene = Scene::with('hotspots')->findOrFail($id);
```

### Get Featured Artifacts
```php
$featured = Artifact::featured()->limit(5)->get();
```

### Get Artifacts by Category
```php
$artifacts = Artifact::byCategory($categoryId)->paginate(12);
```

### Get Hotspots by Type
```php
$infoHotspots = Hotspot::infoType()->get();
$sceneHotspots = Hotspot::sceneType()->get();
```

### Upload Image (in Controller)
```php
if ($request->hasFile('image_path')) {
    $path = $request->file('image_path')->store('scenes', 'public');
    $data['image_path'] = $path;
}
```

### Delete Image (in Controller)
```php
if ($scene->image_path) {
    Storage::disk('public')->delete($scene->image_path);
}
```

## HTTP Status Codes

| Code | Scenario |
|------|----------|
| 200 | Successful GET request |
| 201 | Successful resource creation |
| 204 | Successful DELETE |
| 400 | Invalid locale in language switch |
| 403 | Not admin or unauthorized |
| 404 | Scene/artifact not found or not active |
| 422 | Validation failure |

## Testing Checklist

- [ ] Scene CRUD operations
- [ ] Hotspot CRUD with coordinate validation
- [ ] Artifact CRUD with bilingual fields
- [ ] Image upload/delete functionality
- [ ] Pannellum JSON endpoint (`/tour/data/{id}`)
- [ ] Collection filtering and search
- [ ] Language switching stores in session
- [ ] Admin authorization blocks non-admin users
- [ ] Soft delete cascades work correctly
- [ ] File cleanup on deletion

## Deployment Notes

1. **AdminMiddleware** - Must be created and registered in `app/Http/Kernel.php`
2. **Storage Link** - Run `php artisan storage:link` after deployment
3. **Validation Messages** - Add localization keys to `resources/lang/*/validation.php`
4. **Image Max Sizes**:
   - Scenes: 50MB
   - Hotspots: 5MB
   - Artifacts: 10MB
5. **Database** - All FK constraints use cascade delete

---

**Last Updated:** May 9, 2026
**All Models & Controllers:** ✅ Complete
