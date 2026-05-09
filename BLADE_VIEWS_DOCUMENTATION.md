📌 BLADE VIEWS DOCUMENTATION - Virtual Tour 360° Feature
═══════════════════════════════════════════════════════════════════════════════

## Overview

Three interconnected Blade views comprise the Virtual Tour user interface:

1. **layouts/app.blade.php** — Main layout template with navbar, footer, and styling
2. **tour/index.blade.php** — Landing page with hero section and scene grid
3. **tour/show.blade.php** — Main panorama viewer with Pannellum.js integration

═══════════════════════════════════════════════════════════════════════════════

## 1. layouts/app.blade.php — Main Application Layout

### Purpose
Master layout template providing consistent styling, navigation, and footer across all pages.

### Key Features

**Navbar**
- Sticky navbar with dark theme (var(--bg-surface))
- Brand logo with icon (gopuram = traditional temple)
- Navigation links: Beranda | Koleksi | Admin (if authenticated)
- Language switcher: ID | EN toggle buttons
- Mobile-responsive hamburger menu

**Styling**
- CSS Variables for consistent dark theme:
  - --bg-dark: #0f1412 (darkest background)
  - --bg-surface: #1a2320 (card/surface background)
  - --text-light: #e8f0ea (main text color)
  - --primary-green: #2d9b5e (accent color)
  - --primary-teal: #1a7f6f (secondary accent)
  - --accent-cyan: #4db8a0 (highlight color)

**Footer**
- Four-column layout:
  - About Virtual Tour (description)
  - Menu Utama (links to main pages)
  - Budaya Toraja (category links)
  - Kontak & Sosial (social media icons)
- Footer bottom with copyright and attribution

**Responsive Breakpoints**
- Mobile: Hides active link underlines, collapses navbar
- Tablet/Desktop: Full navbar with active link indicators

### Usage
```blade
@extends('layouts.app')

@section('title', 'Your Page Title')

@section('content')
  <!-- Your content here -->
@endsection
```

### Stack Examples
```blade
@push('styles')
  <!-- Add custom CSS -->
@endpush

@push('scripts')
  <!-- Add custom JavaScript -->
@endpush
```

═══════════════════════════════════════════════════════════════════════════════

## 2. tour/index.blade.php — Landing Page

### Purpose
Homepage featuring hero section, featured scenes grid, cultural information about Toraja, and Ma'nene ritual.

### Page Sections

**1. Hero Section**
- Large gradient banner (green to teal)
- Title: "🏔️ Virtual Tour Budaya Toraja"
- Subtitle with description
- Responsive sizing (3rem on desktop, 2rem on mobile)

**2. Featured Section**
- "⭐ Recommended" label
- Grid of all active scenes from database
- Each scene card displays:
  - Thumbnail image (200px height)
  - Play icon overlay (appears on hover)
  - Title and description (truncated to 80 chars)
  - "Mulai Tur" button with icon

**3. Info Sections (Two-column layout)**
- **About Toraja**
  - Description of Toraja region
  - Bullet points highlighting unique features
- **Ritual Ma'nene**
  - Explanation of traditional ritual
  - Details about celebration frequency and significance

**4. Call-to-Action Section**
- Gradient background (green to cyan)
- Heading and description
- Large button linking to first scene

**5. All Locations Section** (if > 3 scenes)
- Shows all available scenes in grid
- Same card layout as featured section

### Data Requirements

```php
// Controller should pass:
$scenes = Scene::active()->ordered()->get();
```

### Styling Features

**Scene Card Hover Effects**
- Translate up (-8px)
- Shadow enhancement
- Border color change to cyan
- Play icon fades in

**Responsive Grid**
```css
grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
/* Mobile: 1 column, Tablet: 2-3 columns, Desktop: 3+ columns */
```

### Key Classes
- `.hero` — Hero section styling
- `.scene-card` — Individual scene card container
- `.scene-thumbnail` — Image wrapper with 200px height
- `.scene-info` — Card content area
- `.section-title` — Section headers with icon
- `.cta-section` — Call-to-action gradient banner

═══════════════════════════════════════════════════════════════════════════════

## 3. tour/show.blade.php — Panorama Viewer

### Purpose
Main virtual tour viewer displaying 360° panorama with interactive hotspots, scene navigation, and Pannellum.js integration.

### Architecture

**Pannellum.js Integration**
- CDN: pannellum.org (v2.5.6)
- Full-width panorama container (#panorama)
- Height: calc(100vh - 64px) — full viewport minus navbar
- Fetches scene data from `/tour/data/{sceneId}` API endpoint

### Page Components

**1. Panorama Viewer Container**
```html
<div id="panorama"></div>
<!-- 100% width, calc(100vh - 64px) height -->
```

**2. Left Sidebar (Scene Navigation)**
- Fixed position, collapsible on mobile
- Scene list showing all available scenes
- Active scene highlighted with green indicator
- Click to navigate to different scenes
- Shows scene thumbnail and title
- Hidden on mobile by default (toggle button reveals)

**3. Bottom Control Bar**
- Fixed at bottom with gradient background
- Left side: Scene title and description
- Right side: Control buttons
  - Fullscreen button
  - Scene list toggle button
- Height: 80px, responsive on mobile

**4. Hotspot Info Popup**
- Appears when info-type hotspots are clicked
- Shows title, content, and optional image
- Positioned near center of viewer
- Close button (X) and ESC key to dismiss
- Hidden by default

**5. Sidebar Toggle Button**
- Appears when sidebar is hidden
- Fixed on left edge
- Shows/hides sidebar with animation
- Reveals when sidebar is collapsed (mobile)

### Data Flow

**API Endpoint Format**: `GET /tour/data/{sceneId}`

**Response Format**:
```json
{
  "scene": {
    "id": 1,
    "title": "Lembang Baruppu'",
    "description": "Main village overview",
    "image": "http://localhost:8000/storage/scenes/panorama.jpg"
  },
  "hotspots": [
    {
      "id": 1,
      "type": "info",
      "pitch": 10.5,
      "yaw": 90.0,
      "title": "Info Point",
      "content": "This is a traditional house",
      "image": "http://localhost:8000/storage/hotspots/info.jpg"
    },
    {
      "id": 2,
      "type": "scene",
      "pitch": -15.5,
      "yaw": 180.0,
      "title": "Go to Liang Alang",
      "targetSceneId": 2
    }
  ]
}
```

### JavaScript API

**Global Variables**
```javascript
let viewer = null;              // Pannellum viewer instance
let allScenes = [];             // Array of scene IDs
let currentSceneId = {{ $scene->id }}; // Current scene being viewed
```

**Key Functions**

1. **initializePannellum()**
   - Fetches scene data from API
   - Builds Pannellum configuration
   - Initializes viewer
   - Attaches event listeners

2. **buildPannellumConfig(data)**
   - Converts API response to Pannellum format
   - Creates scene configuration with hotspots
   - Returns config object for viewer initialization

3. **buildHotspots(hotspots)**
   - Transforms hotspot data for Pannellum
   - Handles "info" and "scene" types
   - Attaches click handlers

4. **navigateToScene(sceneId)**
   - Fetches new scene data
   - Loads panorama with new hotspots
   - Updates UI (title, active scene indicator)

5. **showHotspotPopup(hotspot)**
   - Displays info popup with title, content, image
   - Positions near viewer center

6. **toggleSidebar()**
   - Shows/hides left sidebar
   - Toggles button visibility
   - Animated transition

7. **enterFullscreen()**
   - Requests fullscreen for panorama container
   - Fallbacks for different browsers

### Hotspot Types

**Info Hotspot**
- Displays popup on click
- Shows title, content text, and optional image
- Visible when overlaid on panorama
- Cursor indicates interactivity

**Scene Hotspot**
- Navigates to another scene
- Shows target scene ID
- Loads new panorama automatically
- Updates sidebar active indicator
- Smooth transition between scenes

### Keyboard Shortcuts
```
S (or s) — Toggle sidebar
F (or f) — Enter fullscreen
ESC      — Close hotspot popup
```

### Responsive Behavior

**Desktop (> 768px)**
- Sidebar always visible on left
- Scene list shows thumbnails and titles
- Popup positioned at center

**Mobile (≤ 768px)**
- Sidebar hidden by default
- Toggle button reveals sidebar
- Scene thumbnails smaller (50px)
- Control bar stacks vertically
- Hotspot popup max-width 260px
- Close button on sidebar header

### Styling Features

**Dark Theme Integration**
- Uses CSS variables from app.blade.php
- Semi-transparent overlays with backdrop blur
- Smooth transitions and animations

**Custom Scrollbar** (webkit browsers)
- Primary color track
- Green thumb with cyan hover

**Hotspot Styling**
- Info hotspots: Custom CSS class
- Scene hotspots: Different styling
- Hover effects and animations

═══════════════════════════════════════════════════════════════════════════════

## Implementation Checklist

- [x] Main layout (app.blade.php)
  - [x] Navbar with branding
  - [x] Navigation links
  - [x] Language switcher
  - [x] Footer with multi-column layout
  - [x] Dark theme CSS variables
  - [x] Responsive mobile menu

- [x] Landing page (index.blade.php)
  - [x] Hero section with gradient
  - [x] Featured scenes grid
  - [x] Scene cards with hover effects
  - [x] Cultural information sections
  - [x] Call-to-action banner
  - [x] Responsive grid layout

- [x] Viewer page (show.blade.php)
  - [x] Pannellum.js integration
  - [x] Full-width panorama container
  - [x] API data fetching
  - [x] Hotspot rendering (info & scene types)
  - [x] Scene navigation
  - [x] Left sidebar with scene list
  - [x] Bottom control bar
  - [x] Hotspot info popup
  - [x] Fullscreen button
  - [x] Mobile sidebar toggle
  - [x] Keyboard shortcuts
  - [x] Responsive layout

═══════════════════════════════════════════════════════════════════════════════

## Controller Integration

**VirtualTourController.php**

```php
// Show landing page with featured scenes
public function index()
{
    $scenes = Scene::active()->ordered()->get();
    return view('tour.index', compact('scenes'));
}

// Show specific panorama viewer
public function show(Scene $scene)
{
    // Aborts 404 if scene is not active
    abort_unless($scene->is_active, 404);
    return view('tour.show', compact('scene'));
}

// API endpoint for panorama data
public function getSceneData(Scene $scene)
{
    return response()->json([
        'scene' => [
            'id' => $scene->id,
            'title' => $scene->title,
            'description' => $scene->description,
            'image' => asset('storage/' . $scene->image_path),
        ],
        'hotspots' => $scene->hotspots->map(function($hotspot) {
            return [
                'id' => $hotspot->id,
                'type' => $hotspot->type,
                'pitch' => $hotspot->pitch,
                'yaw' => $hotspot->yaw,
                'title' => $hotspot->title,
                'content' => $hotspot->content,
                'image' => $hotspot->image_path ? asset('storage/' . $hotspot->image_path) : null,
                'targetSceneId' => $hotspot->target_scene_id,
            ];
        })->toArray(),
    ]);
}
```

**Routes**

```php
// Public routes
Route::get('/', [VirtualTourController::class, 'index'])->name('tour.index');
Route::get('/tour/{scene}', [VirtualTourController::class, 'show'])->name('tour.show');
Route::get('/tour/data/{scene}', [VirtualTourController::class, 'getSceneData']);
```

═══════════════════════════════════════════════════════════════════════════════

## Browser Compatibility

✅ Chrome/Edge 90+ — Full support
✅ Firefox 88+ — Full support
✅ Safari 14+ — Full support
✅ Mobile Safari iOS 14+ — Full support
✅ Chrome Mobile — Full support

═══════════════════════════════════════════════════════════════════════════════

## Performance Optimization

1. **Image Optimization**
   - Thumbnails: 5MB max (stored separately)
   - Panoramas: 50MB max (equirectangular format)
   - Use modern formats (WebP when possible)

2. **Pannellum Configuration**
   - autoLoad: true (preload panorama)
   - Controls are disabled (custom controls used)
   - Viewport constraints can limit pan speed

3. **Lazy Loading**
   - Scene thumbnails load on demand
   - Hotspot images only loaded when popup shown
   - Sidebar items rendered only once

═══════════════════════════════════════════════════════════════════════════════

## File Locations

```
resources/views/
├── layouts/
│   └── app.blade.php ........... Main layout template
└── tour/
    ├── index.blade.php ........ Landing page
    └── show.blade.php ......... Panorama viewer
```

═══════════════════════════════════════════════════════════════════════════════

## Customization

### Colors
Edit CSS variables in layouts/app.blade.php:
```css
:root {
    --bg-dark: #0f1412;
    --bg-surface: #1a2320;
    --text-light: #e8f0ea;
    --primary-green: #2d9b5e;
    --primary-teal: #1a7f6f;
    --accent-cyan: #4db8a0;
}
```

### Pannellum Options
Modify in tour/show.blade.php buildPannellumConfig():
```javascript
config.default = {
    firstScene: 'scene_1',
    autoLoad: true,
    showFullscreenCtrl: false,
    pitch: 0,           // Initial pitch
    yaw: 0,             // Initial yaw
    hfov: 100,          // Horizontal field of view
    minHfov: 50,        // Minimum FOV
    maxHfov: 150,       // Maximum FOV
}
```

### Sidebar Appearance
Modify sidebar width and layout in tour/show.blade.php:
```css
#sidebar {
    width: 280px;  /* Sidebar width */
}
```

═══════════════════════════════════════════════════════════════════════════════

## Troubleshooting

**Panorama not loading?**
- Check `/tour/data/{id}` endpoint returns correct JSON
- Verify image paths are correct and accessible
- Check browser console for fetch errors

**Hotspots not appearing?**
- Verify hotspot coordinates are within valid ranges:
  - pitch: -90 to 90
  - yaw: 0 to 360
- Check hotspot images are accessible

**Sidebar not showing on mobile?**
- Sidebar starts hidden on mobile
- Click scene list button to reveal it
- Check localStorage for sidebar state (not persisted by default)

**Pannellum js not loading?**
- Verify CDN URL is accessible
- Check internet connection
- Use local copy if CDN fails

═══════════════════════════════════════════════════════════════════════════════

## Next Steps

1. Ensure VirtualTourController has getSceneData() method
2. Create admin views for scene/hotspot management
3. Test with sample panorama images
4. Optimize image sizes for production
5. Add analytics/tracking (optional)
6. Consider adding annotations or guided tours (future enhancement)

═══════════════════════════════════════════════════════════════════════════════

Generated: May 10, 2026
Updated: Blade views generation
Status: ✅ Complete and ready for testing
