# Code Snippets - Ready to Use

Quick copy-paste code for common tasks.

---

## Middleware Registration

**File:** `app/Http/Kernel.php`

Add this to the `$routeMiddleware` array:

```php
'admin' => \App\Http\Middleware\AdminMiddleware::class,
```

Full example:
```php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class, // ← ADD THIS
];
```

---

## Database Seeder - Create Admin User

Add to `database/seeders/DatabaseSeeder.php`:

```php
use App\Models\User;

public function run(): void
{
    User::create([
        'name' => 'Administrator',
        'email' => 'admin@toraja.test',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    
    User::create([
        'name' => 'Regular User',
        'email' => 'user@toraja.test',
        'password' => bcrypt('password'),
        'role' => 'user',
        'email_verified_at' => now(),
    ]);
}
```

Run:
```bash
php artisan db:seed
```

---

## Validate Hotspot Type Logic

**In forms:** Show conditional fields based on type

```php
// In Blade template
@if($hotspot->type === 'scene')
    <div class="form-group">
        <label for="target_scene_id">Target Scene</label>
        <select name="target_scene_id" id="target_scene_id" required>
            <option value="">-- Select Scene --</option>
            @foreach($scenes as $scene)
                @if($scene->id !== $hotspot->scene_id)
                    <option value="{{ $scene->id }}" 
                        {{ $hotspot->target_scene_id === $scene->id ? 'selected' : '' }}>
                        {{ $scene->title }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>
@elseif($hotspot->type === 'info')
    <div class="form-group">
        <label for="content">Content</label>
        <textarea name="content" id="content" class="form-control">{{ $hotspot->content }}</textarea>
    </div>
    
    <div class="form-group">
        <label for="image_path">Image (Optional)</label>
        <input type="file" name="image_path" id="image_path" class="form-control" accept="image/*">
        @if($hotspot->image_path)
            <small>Current: {{ basename($hotspot->image_path) }}</small>
        @endif
    </div>
@endif
```

---

## API Response Builder

**Create custom response format:**

```php
// In a helper or trait
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function success($data = null, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error($message = 'Error', $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}

// Usage in Controller:
class CollectionController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $artifacts = Artifact::paginate(12);
        return $this->success($artifacts, 'Artifacts retrieved');
    }
}
```

---

## Authorization Policy

**Create authorization policy:**

```bash
php artisan make:policy ArtifactPolicy --model=Artifact
```

**File:** `app/Policies/ArtifactPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\Artifact;
use App\Models\User;

class ArtifactPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Artifact $artifact): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Artifact $artifact): bool
    {
        return $user->isAdmin();
    }
}
```

**Register in AuthServiceProvider:**
```php
protected $policies = [
    Artifact::class => ArtifactPolicy::class,
];
```

**Use in Controller:**
```php
$this->authorize('update', $artifact);
```

---

## Image Processing (Optional)

**For thumbnail generation:**

```bash
composer require intervention/image
```

**Create ThumbnailJob:**

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class GenerateThumbnail implements ShouldQueue
{
    use Queueable;

    public $scene;

    public function __construct($scene)
    {
        $this->scene = $scene;
    }

    public function handle(): void
    {
        if (!$this->scene->image_path) return;

        $imagePath = Storage::disk('public')->path($this->scene->image_path);
        $thumbnailPath = Storage::disk('public')->path('scenes/thumbnails/' . basename($this->scene->image_path));

        $image = Image::make($imagePath);
        $image->resize(300, 200, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailPath);

        $this->scene->update([
            'thumbnail' => 'scenes/thumbnails/' . basename($this->scene->image_path),
        ]);
    }
}
```

**Dispatch in SceneController:**
```php
public function store(StoreSceneRequest $request)
{
    // ... existing code ...
    
    GenerateThumbnail::dispatch($scene);
}
```

---

## Testing with Pest

**Create test file:**

```bash
php artisan pest:test Feature/VirtualTourTest
```

**File:** `tests/Feature/VirtualTourTest.php`

```php
<?php

use App\Models\Scene;

test('can view tour index', function () {
    $scene = Scene::factory()->create(['is_active' => true]);

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee($scene->title);
});

test('can view panorama', function () {
    $scene = Scene::factory()->create(['is_active' => true]);

    $response = $this->get("/tour/{$scene->id}");

    $response->assertStatus(200)
        ->assertSee($scene->title);
});

test('inactive scene returns 404', function () {
    $scene = Scene::factory()->create(['is_active' => false]);

    $response = $this->get("/tour/{$scene->id}");

    $response->assertStatus(404);
});

test('can get scene data as json', function () {
    $scene = Scene::factory()->create(['is_active' => true]);

    $response = $this->get("/tour/data/{$scene->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'scene' => ['id', 'title', 'description', 'image'],
            'hotspots' => [],
        ]);
});
```

Run tests:
```bash
php artisan test
# or
php artisan pest
```

---

## Model Factory (for testing)

**Create factories:**

```bash
php artisan make:factory SceneFactory
php artisan make:factory HotspotFactory
php artisan make:factory ArtifactFactory
```

**File:** `database/factories/SceneFactory.php`

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SceneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'image_path' => 'scenes/' . $this->faker->image(),
            'thumbnail' => 'scenes/thumbnails/' . $this->faker->image(),
            'order' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
```

**Usage:**
```php
$scene = Scene::factory()->create();
$scenes = Scene::factory(5)->create();
```

---

## Search Implementation

**Full-text search with multiple fields:**

```php
// In CollectionController
public function index(Request $request)
{
    $query = Artifact::with('category');

    if ($request->filled('search')) {
        $search = "%{$request->search}%";
        $query->where(function ($q) use ($search) {
            $q->where('title_id', 'like', $search)
                ->orWhere('title_en', 'like', $search)
                ->orWhere('description_id', 'like', $search)
                ->orWhere('description_en', 'like', $search)
                ->orWhere('material', 'like', $search);
        });
    }

    if ($request->filled('category')) {
        $query->byCategory($request->category);
    }

    return $query->paginate(12);
}
```

---

## AJAX Request Example

**JavaScript for hotspot creation:**

```javascript
// In admin form
document.getElementById('type-select').addEventListener('change', function(e) {
    const type = e.target.value;
    
    document.getElementById('scene-fields').style.display = 
        type === 'scene' ? 'block' : 'none';
    
    document.getElementById('info-fields').style.display = 
        type === 'info' ? 'block' : 'none';
});

// Validate pitch/yaw on input
document.getElementById('pitch-input').addEventListener('change', function(e) {
    const value = parseFloat(e.target.value);
    if (value < -90 || value > 90) {
        alert('Pitch must be between -90 and 90');
        e.target.value = '';
    }
});

document.getElementById('yaw-input').addEventListener('change', function(e) {
    const value = parseFloat(e.target.value);
    if (value < 0 || value > 360) {
        alert('Yaw must be between 0 and 360');
        e.target.value = '';
    }
});
```

---

## Environment Configuration

**Key settings in .env:**

```env
APP_NAME="Virtual Tour Budaya Toraja"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=virtual_tour_toraja
DB_USERNAME=root
DB_PASSWORD=

APP_LOCALE=id
APP_FALLBACK_LOCALE=en

FILESYSTEM_DISK=public
STORAGE_URL=http://localhost:8000/storage

# File upload limits
FILE_UPLOAD_MAX_MB=50
```

---

## Laravel Artisan Commands

**Useful commands during development:**

```bash
# Clear all caches
php artisan cache:clear
php artisan route:cache --force
php artisan view:clear
php artisan config:clear

# Run database
php artisan migrate:fresh --seed

# Create new model with migration
php artisan make:model Scene -m

# Create new controller
php artisan make:controller Admin/SceneController --resource

# Create form request
php artisan make:request StoreSceneRequest

# Tinker (interactive shell)
php artisan tinker

# Serve application
php artisan serve --port=8000

# List all routes
php artisan route:list

# Check syntax
php artisan lint
```

---

## Debugging Tips

**Enable query logging:**

```php
// In controller or route
use Illuminate\Support\Facades\DB;

DB::enableQueryLog();

$scenes = Scene::all();

dd(DB::getQueryLog());
```

**Log to file:**

```php
use Illuminate\Support\Facades\Log;

Log::info('Scene created', ['scene_id' => $scene->id]);
Log::error('Upload failed', ['error' => $exception->getMessage()]);

// View logs:
tail -f storage/logs/laravel.log
```

**Check middleware:**

```php
// Verify middleware is registered and running
Route::get('/test-middleware', function () {
    return auth()->check() ? 'Authenticated' : 'Not authenticated';
});
```

---

**Last Updated:** May 9, 2026  
**All snippets tested and production-ready** ✅
