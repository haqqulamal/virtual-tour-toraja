

<?php $__env->startSection('title', $scene->title . ' - Virtual Tour'); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('styles'); ?>
<!-- Pannellum CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">

<style>
    /* Override main layout for full panorama */
    main {
        padding: 0 !important;
        min-height: 100vh !important;
    }

    body {
        overflow: hidden;
    }

    /* Panorama Viewer */
    #panorama {
        width: 100%;
        height: calc(100vh - 64px);
    }

    /* Controls Container */
    .viewer-controls {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: linear-gradient(to top, rgba(15, 20, 18, 0.95), transparent);
        border-top: 2px solid var(--primary-green);
        display: flex;
        align-items: flex-end;
        padding: 1rem;
        z-index: 1000;
        gap: 2rem;
    }

    .scene-info-bar {
        flex-grow: 1;
    }

    .scene-info-bar h4 {
        color: var(--accent-cyan);
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
    }

    .scene-info-bar p {
        color: #999;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.3;
    }

    .controls-right {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .control-btn {
        width: 40px;
        height: 40px;
        border-radius: 4px;
        background-color: var(--bg-surface);
        border: 2px solid var(--primary-teal);
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .control-btn:hover {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
        color: var(--bg-dark);
    }

    /* Sidebar */
    #sidebar {
        position: fixed;
        left: 0;
        top: 64px;
        width: 280px;
        height: calc(100vh - 144px);
        background-color: rgba(26, 35, 32, 0.95);
        border-right: 2px solid var(--primary-green);
        overflow-y: auto;
        z-index: 900;
        backdrop-filter: blur(10px);
        transform: translateX(0);
        transition: transform 0.3s ease;
    }

    #sidebar.hidden {
        transform: translateX(-100%);
    }

    .sidebar-header {
        padding: 1rem;
        border-bottom: 1px solid var(--primary-teal);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: var(--bg-surface);
    }

    .sidebar-header h5 {
        color: var(--accent-cyan);
        font-weight: 700;
        margin: 0;
        font-size: 1rem;
    }

    .sidebar-close {
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        font-size: 1.2rem;
        display: none;
    }

    .scenes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .scene-item {
        padding: 0.75rem;
        border-bottom: 1px solid rgba(45, 155, 94, 0.2);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .scene-item:hover {
        background-color: rgba(45, 155, 94, 0.1);
        padding-left: 1.25rem;
    }

    .scene-item.active {
        background-color: rgba(45, 155, 94, 0.2);
        border-left: 4px solid var(--primary-green);
        padding-left: 0.75rem;
    }

    .scene-thumbnail-small {
        width: 50px;
        height: 50px;
        border-radius: 4px;
        background: linear-gradient(135deg, var(--primary-teal), var(--accent-cyan));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        overflow: hidden;
    }

    .scene-thumbnail-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .scene-item-text {
        flex-grow: 1;
        min-width: 0;
    }

    .scene-item-text .title {
        font-weight: 600;
        color: var(--text-light);
        font-size: 0.9rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .scene-item-text .order {
        color: #666;
        font-size: 0.8rem;
        margin: 0;
    }

    /* Toggle Button */
    #toggle-sidebar {
        position: fixed;
        left: 0;
        top: 64px;
        width: 40px;
        height: 40px;
        background-color: var(--bg-surface);
        border: 2px solid var(--primary-green);
        border-radius: 0 4px 4px 0;
        color: var(--text-light);
        cursor: pointer;
        z-index: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    #toggle-sidebar:hover {
        background-color: var(--primary-green);
        color: var(--bg-dark);
    }

    #toggle-sidebar.hidden {
        left: -40px;
    }

    /* Hotspot Info Popup */
    .hotspot-info-popup {
        position: fixed;
        background-color: var(--bg-surface);
        border: 2px solid var(--primary-green);
        border-radius: 8px;
        padding: 1rem;
        max-width: 300px;
        z-index: 1100;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
        display: none;
    }

    .hotspot-info-popup.show {
        display: block;
    }

    .hotspot-info-popup h5 {
        color: var(--accent-cyan);
        margin: 0 0 0.5rem 0;
        font-weight: 700;
    }

    .hotspot-info-popup p {
        color: #ccc;
        margin: 0 0 0.75rem 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .hotspot-info-popup img {
        width: 100%;
        max-height: 150px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 0.75rem;
    }

    .hotspot-info-close {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        font-size: 1.2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #sidebar {
            width: 240px;
        }

        .viewer-controls {
            flex-direction: column;
            height: auto;
            gap: 0.5rem;
            padding: 0.75rem;
        }

        .scene-info-bar {
            width: 100%;
        }

        .scene-info-bar h4 {
            font-size: 1rem;
        }

        .scene-info-bar p {
            font-size: 0.8rem;
        }

        .controls-right {
            width: 100%;
            justify-content: flex-end;
        }

        .control-btn {
            width: 35px;
            height: 35px;
            font-size: 0.9rem;
        }

        .sidebar-close {
            display: block;
        }

        .hotspot-info-popup {
            max-width: 260px;
            font-size: 0.85rem;
        }
    }

    /* Scrollbar styling */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }

    #sidebar::-webkit-scrollbar-track {
        background: rgba(45, 155, 94, 0.1);
    }

    #sidebar::-webkit-scrollbar-thumb {
        background: var(--primary-green);
        border-radius: 3px;
    }

    #sidebar::-webkit-scrollbar-thumb:hover {
        background: var(--accent-cyan);
    }
</style>
<?php $__env->stopPush(); ?>

<!-- Panorama Viewer -->
<div id="panorama"></div>

<!-- Sidebar with Scene Navigation -->
<div id="sidebar" class="hidden">
    <div class="sidebar-header">
        <h5><i class="fas fa-list me-2"></i>Semua Lokasi</h5>
        <button class="sidebar-close" onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="scenes-list" id="scenesList"></ul>
</div>

<!-- Toggle Sidebar Button -->
<button id="toggle-sidebar" class="hidden" onclick="toggleSidebar()">
    <i class="fas fa-chevron-right"></i>
</button>

<!-- Bottom Control Bar -->
<div class="viewer-controls">
    <div class="scene-info-bar">
        <h4 id="currentSceneTitle"><?php echo e($scene->title); ?></h4>
        <p id="currentSceneDesc"><?php echo e(Str::limit($scene->description, 100)); ?></p>
    </div>
    <div class="controls-right">
        <button class="control-btn" id="fullscreenBtn" title="Fullscreen" onclick="enterFullscreen()">
            <i class="fas fa-expand"></i>
        </button>
        <button class="control-btn" id="sidebarToggleBtn" title="Scene List" onclick="toggleSidebar()">
            <i class="fas fa-list"></i>
        </button>
    </div>
</div>

<!-- Hotspot Info Popup -->
<div class="hotspot-info-popup" id="hotspotPopup">
    <button class="hotspot-info-close" onclick="closeHotspotPopup()">
        <i class="fas fa-times"></i>
    </button>
    <h5 id="popupTitle"></h5>
    <p id="popupContent"></p>
    <img id="popupImage" style="display: none;">
</div>

<?php $__env->startPush('scripts'); ?>
<!-- Pannellum JS -->
<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

<script>
    // Global state
    let viewer = null;
    let allScenes = [];
    let currentSceneId = <?php echo e($scene->id); ?>;

    // Fetch scene data and initialize Pannellum
    async function initializePannellum() {
        try {
            // Fetch scene data from API
            const response = await fetch(`/tour/data/<?php echo e($scene->id); ?>`);
            const data = await response.json();

            console.log('Scene data:', data);

            // Build Pannellum configuration
            const sceneConfig = buildPannellumConfig(data);
            console.log('Pannellum config:', sceneConfig);

            // Initialize Pannellum
            viewer = pannellum.viewer('panorama', sceneConfig);

            // Handle scene changes
            viewer.on('scenechange', function(sceneId) {
                updateSceneInfo(sceneId);
                updateActiveScene(sceneId);
            });

            // Handle hotspot clicks
            viewer.on('hotspotclick', function(event) {
                handleHotspotClick(event);
            });

        } catch (error) {
            console.error('Error initializing Pannellum:', error);
            showError('Failed to load panorama viewer');
        }
    }

    // Build Pannellum configuration from API response
    function buildPannellumConfig(data) {
        const config = {
            default: {
                firstScene: 'scene_' + data.scene.id,
                autoLoad: true,
                showFullscreenCtrl: false,
            },
            scenes: {}
        };

        // Add current scene
        const sceneKey = 'scene_' + data.scene.id;
        config.scenes[sceneKey] = {
            type: 'equirectangular',
            panorama: data.scene.image,
            hotSpots: buildHotspots(data.hotspots || [])
        };

        // Store all scenes for navigation
        allScenes.push(data.scene.id);

        return config;
    }

    // Build hotspots array for Pannellum
    function buildHotspots(hotspots) {
        return hotspots.map((spot, index) => {
            const hotspot = {
                pitch: spot.pitch,
                yaw: spot.yaw,
                type: spot.type === 'scene' ? 'scene' : 'info',
                text: spot.title
            };

            // Add type-specific properties
            if (spot.type === 'scene') {
                hotspot.sceneId = 'scene_' + spot.targetSceneId;
                hotspot.cssClass = 'scene-hotspot';
            } else {
                hotspot.cssClass = 'info-hotspot';
                hotspot.clickHandlerFunc = () => showHotspotPopup(spot);
            }

            return hotspot;
        });
    }

    // Handle hotspot click
    function handleHotspotClick(event) {
        const hotspot = event.detail.hotspot;
        console.log('Hotspot clicked:', hotspot);

        if (hotspot.type === 'scene') {
            // Navigate to scene
            const sceneId = hotspot.sceneId;
            navigateToScene(parseInt(sceneId.replace('scene_', '')));
        } else if (hotspot.type === 'info') {
            // Show info popup - this will be called from buildHotspots
        }
    }

    // Show hotspot info popup
    function showHotspotPopup(hotspot) {
        const popup = document.getElementById('hotspotPopup');
        document.getElementById('popupTitle').textContent = hotspot.title;
        document.getElementById('popupContent').textContent = hotspot.content || '';
        
        const img = document.getElementById('popupImage');
        if (hotspot.image) {
            img.src = hotspot.image;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }

        popup.classList.add('show');

        // Position popup near cursor
        const panorama = document.getElementById('panorama');
        popup.style.top = (panorama.offsetHeight / 2 - 100) + 'px';
        popup.style.left = (panorama.offsetWidth / 2 - 150) + 'px';
    }

    // Close hotspot popup
    function closeHotspotPopup() {
        document.getElementById('hotspotPopup').classList.remove('show');
    }

    // Navigate to another scene
    async function navigateToScene(sceneId) {
        try {
            const response = await fetch(`/tour/data/${sceneId}`);
            const data = await response.json();

            // Load new panorama
            viewer.loadScene('scene_' + sceneId, {
                type: 'equirectangular',
                panorama: data.scene.image,
                hotSpots: buildHotspots(data.hotspots || [])
            });

            currentSceneId = sceneId;
            updateSceneInfo(sceneId);
            updateActiveScene(sceneId);

        } catch (error) {
            console.error('Error navigating to scene:', error);
            showError('Failed to load scene');
        }
    }

    // Update scene info in control bar
    function updateSceneInfo(sceneId) {
        // Find scene info (would normally come from API)
        document.getElementById('currentSceneTitle').textContent = 'Scene ' + sceneId;
        // Description would be fetched from API in production
    }

    // Update active scene in sidebar
    function updateActiveScene(sceneId) {
        document.querySelectorAll('.scene-item').forEach(el => {
            el.classList.remove('active');
            if (el.dataset.sceneId == sceneId) {
                el.classList.add('active');
            }
        });
    }

    // Toggle sidebar visibility
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-sidebar');

        sidebar.classList.toggle('hidden');
        toggleBtn.classList.toggle('hidden');
    }

    // Enter fullscreen
    function enterFullscreen() {
        const panorama = document.getElementById('panorama');
        
        if (panorama.requestFullscreen) {
            panorama.requestFullscreen();
        } else if (panorama.mozRequestFullScreen) {
            panorama.mozRequestFullScreen();
        } else if (panorama.webkitRequestFullscreen) {
            panorama.webkitRequestFullscreen();
        }
    }

    // Show error message
    function showError(message) {
        alert(message); // Simple error display
    }

    // Render scenes list in sidebar
    function renderScenesList() {
        const listContainer = document.getElementById('scenesList');
        
        // Clear existing items
        listContainer.innerHTML = '';

        // Add current scene
        const item = document.createElement('li');
        item.className = 'scene-item active';
        item.dataset.sceneId = currentSceneId;
        item.innerHTML = `
            <div class="scene-thumbnail-small">
                <i class="fas fa-image"></i>
            </div>
            <div class="scene-item-text">
                <p class="title"><?php echo e($scene->title); ?></p>
                <p class="order">#1</p>
            </div>
        `;
        item.onclick = () => navigateToScene(currentSceneId);
        listContainer.appendChild(item);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializePannellum();
        renderScenesList();

        // Close popup when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#hotspotPopup') && 
                !e.target.closest('.pnlm-hotspot')) {
                closeHotspotPopup();
            }
        });
    });

    // Handle keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeHotspotPopup();
        }
        if (e.key === 's' || e.key === 'S') {
            toggleSidebar();
        }
        if (e.key === 'f' || e.key === 'F') {
            enterFullscreen();
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\virtual-tour\resources\views/tour/show.blade.php ENDPATH**/ ?>