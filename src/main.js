// QR Code generated via https://api.qrserver.com/v1/create-qr-code/

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
// ... other existing elements
const cameraSessionView = document.getElementById('camera-session-view');
const studioView = document.getElementById('studio-view');
const previewView = document.getElementById('preview-view');
const openStudioBtn = document.getElementById('open-studio-btn');
const studioBackBtn = document.getElementById('studio-back-btn');
const studioConfirmBtn = document.getElementById('studio-confirm-btn');
const studioBadge = document.getElementById('studio-badge');

const captureBtn = document.getElementById('capture-btn');
const captureBtnText = document.getElementById('capture-btn-text');
const cancelRetakeBtn = document.getElementById('cancel-retake-btn');
const retakeSingleBtn = document.getElementById('retake-single-btn');
const retakeBtn = document.getElementById('retake-btn');
const downloadBtn = document.getElementById('download-btn');
const saveBtn = document.getElementById('save-btn');
const photoPreview = document.getElementById('photo-preview');
const photoPreviewContainer = document.getElementById('photo-preview-container');
const postCaptureControls = document.getElementById('post-capture-controls');
const flash = document.getElementById('flash');
const photoCounter = document.getElementById('photo-counter');
const countdownOverlay = document.getElementById('countdown-overlay');
const countdownNumber = document.getElementById('countdown-number');
const processingOverlay = document.getElementById('processing-overlay');

// QR Modal Elements
const qrModal = document.getElementById('qr-modal');
const closeQrBtn = document.getElementById('close-qr');
const finishSessionBtn = document.getElementById('finish-session-btn');
const qrImage = document.getElementById('qr-image');

let stream;
let capturedPhotos = [null, null, null, null, null, null];
let selectedSlotToRetake = null;
let studioLayoutMode = '3-strip'; // Default '3-strip' (3 Foto Vertikal) atau '6-grid'
let selectedStripPhotos = [0, 1, 2];
let selected6Photos = [0, 1, 2, 3, 4, 5];
let activePoolSelectIndex = null;
const TOTAL_PHOTOS = 6;

// Theme State
let overlayMode = localStorage.getItem('overlayMode') === 'true';
let selectedTemplateId = localStorage.getItem('selectedTemplateId') || '';
let availableTemplates = [];

const settingsToggle = document.getElementById('settings-toggle');
const settingsModal = document.getElementById('settings-modal');
const closeSettings = document.getElementById('close-settings');
const resetThemeBtn = document.getElementById('reset-theme');

// Initial Setup
settingsToggle.addEventListener('click', () => {
    loadTemplates();
    settingsModal.classList.remove('hidden');
});
closeSettings.addEventListener('click', () => settingsModal.classList.add('hidden'));
settingsModal.addEventListener('click', (e) => e.target === settingsModal && settingsModal.classList.add('hidden'));

resetThemeBtn.addEventListener('click', () => {
    if (confirm('Reset tema?')) {
        if (availableTemplates.length > 0) {
            window.selectTemplate(availableTemplates[0].id);
        } else {
            selectedTemplateId = '';
            localStorage.setItem('selectedTemplateId', '');
            overlayMode = false;
            localStorage.setItem('overlayMode', 'false');
            renderGallery();
        }
        alert('Tema telah direset.');
    }
});

// Fetch and Render Active Templates (Checked by Admin)
async function loadTemplates() {
    try {
        const res = await fetch('manage_templates.php?action=list&only_active=1&_t=' + Date.now());
        availableTemplates = await res.json();
        
        // Auto-select first if none selected or invalid
        const exists = availableTemplates.find(t => t.id === selectedTemplateId);
        if (!exists && availableTemplates.length > 0) {
            window.selectTemplate(availableTemplates[0].id);
        } else if (availableTemplates.length === 0) {
            selectedTemplateId = '';
            localStorage.setItem('selectedTemplateId', '');
        }
        
        renderGallery();
        renderStudioThemeGallery();
        updateStudioFramePreview();
    } catch (err) {
        console.error("Failed to load templates:", err);
    }
}

// Inisialisasi awal pemuatan template
loadTemplates();

function renderGallery() {
    const gallery = document.getElementById('template-gallery');
    if (!gallery) return;

    // Clear gallery
    gallery.innerHTML = '';

    if (availableTemplates.length === 0) {
        gallery.innerHTML = `
            <div class="col-span-2 text-center py-8 text-ramadan-secondary/60">
                <p class="font-semibold">Belum ada template yang diunggah.</p>
                <p class="text-xs mt-1">Gunakan menu Admin di bawah untuk menambahkan template.</p>
            </div>
        `;
        return;
    }

    let html = '';
    // Add dynamics
    availableTemplates.forEach(t => {
        const isActive = selectedTemplateId === t.id;
        const thumb = t.outer || t.ketupat || t.lampu || t.rama || '';
        html += `
            <button onclick="window.selectTemplate('${t.id}')" 
                class="template-item group relative aspect-[9/16] rounded-[2.5rem] overflow-hidden transition-all duration-500 
                ${isActive ? 'ring-[12px] ring-ramadan-gold/70 border-8 border-white' : 'border-4 border-ramadan-gold/10 hover:border-ramadan-gold/50 shadow-xl'}">
                
                <!-- Full Preview Image -->
                ${thumb ? `<img src="${thumb}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">` : `<div class="w-full h-full bg-slate-900 flex items-center justify-center text-4xl">🖼️</div>`}
                
                <!-- Improved Label Overlay (Subtler) -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80 group-hover:opacity-100 transition-opacity">
                    <div class="absolute bottom-6 left-0 right-0 px-4 text-center">
                        <span class="text-sm font-bold text-white uppercase tracking-[0.2em] drop-shadow-lg">${t.name}</span>
                    </div>
                </div>

                <!-- Active State Indicator -->
                ${isActive ? `
                    <div class="absolute inset-0 bg-ramadan-green/20 backdrop-blur-[1px] flex items-center justify-center">
                        <div class="bg-ramadan-gold text-white rounded-full p-4 shadow-[0_0_30px_rgba(212,175,55,0.6)] scale-110">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                ` : `
                    <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="bg-white/90 p-2 rounded-full shadow-lg">
                            <svg class="w-5 h-5 text-ramadan-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </div>
                `}
            </button>
        `;
    });

    gallery.innerHTML = html;
}

function renderStudioThemeGallery() {
    const studioGallery = document.getElementById('studio-theme-gallery');
    if (!studioGallery) return;
    studioGallery.innerHTML = '';

    if (availableTemplates.length === 0) {
        studioGallery.innerHTML = `<span class="text-xs text-ramadan-secondary/60 italic py-1 px-2">Belum ada template custom. Tema standar aktif.</span>`;
        return;
    }

    availableTemplates.forEach(t => {
        const isActive = selectedTemplateId === t.id;
        const thumb = t.outer || t.ketupat || t.lampu || t.rama || '';
        const item = document.createElement('button');
        item.type = 'button';
        item.onclick = () => window.selectTemplate(t.id);
        item.className = `flex items-center gap-2.5 p-1.5 pr-3.5 rounded-2xl border-2 transition-all shrink-0 cursor-pointer ${
            isActive 
                ? 'bg-ramadan-gold text-ramadan-green border-white shadow-lg scale-105 font-bold ring-2 ring-ramadan-gold/50' 
                : 'bg-black/30 text-ramadan-cream/80 border-ramadan-gold/30 hover:border-ramadan-gold hover:bg-black/50'
        }`;
        
        item.innerHTML = `
            <div class="w-8 h-12 rounded-lg overflow-hidden bg-black/60 border border-white/20 shrink-0 relative flex items-center justify-center">
                ${thumb ? `<img src="${thumb}" class="w-full h-full object-cover" alt="${t.name}">` : `<span class="text-xs">🖼️</span>`}
                ${isActive ? `<div class="absolute inset-0 bg-ramadan-green/40 flex items-center justify-center text-white text-xs font-black">✓</div>` : ''}
            </div>
            <div class="text-left flex flex-col">
                <span class="text-xs font-bold leading-tight ${isActive ? 'text-ramadan-green' : 'text-ramadan-cream'}">${t.name}</span>
                <span class="text-[9px] ${isActive ? 'text-ramadan-green/80' : 'text-ramadan-cream/60'}">${t.overlayMode ? 'Mode Overlay' : 'Mode Bingkai'}</span>
            </div>
        `;
        studioGallery.appendChild(item);
    });
}

// Image asset cache to make studio live preview and final generation instantaneous
const imageAssetCache = new Map();
function loadCachedImage(src) {
    if (!src) return Promise.resolve(null);
    if (imageAssetCache.has(src)) {
        const cached = imageAssetCache.get(src);
        if (cached && (cached.complete || cached.naturalWidth > 0)) return Promise.resolve(cached);
    }
    return new Promise((resolve) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => {
            imageAssetCache.set(src, img);
            resolve(img);
        };
        img.onerror = () => resolve(null);
        img.src = src;
    });
}

function updateStudioLayoutUI() {
    const currentTemplate = availableTemplates.find(t => t.id === selectedTemplateId);
    const templateName = currentTemplate ? currentTemplate.name : 'Standar Ramadan';
    
    const themeBadge = document.getElementById('studio-theme-name-badge');
    const themeLabel = document.getElementById('studio-current-theme-label');
    if (themeBadge) themeBadge.innerText = `Tema: ${templateName}`;
    if (themeLabel) themeLabel.innerText = `Tema Aktif: ${templateName}`;

    const btn3 = document.getElementById('layout-mode-3-btn');
    const btn6 = document.getElementById('layout-mode-6-btn');
    const studioBadge = document.getElementById('studio-badge');
    const layoutTitle = document.getElementById('studio-layout-title');
    const studioSubtitle = document.getElementById('studio-subtitle-text');

    if (studioLayoutMode === '3-strip') {
        if (btn3) {
            btn3.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border shadow-sm bg-ramadan-gold text-ramadan-green border-white ring-2 ring-ramadan-gold/50 cursor-pointer';
        }
        if (btn6) {
            btn6.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border shadow-sm bg-black/30 text-ramadan-cream/80 border-ramadan-gold/30 hover:border-ramadan-gold hover:bg-black/50 cursor-pointer';
        }
        if (studioBadge) studioBadge.innerText = '3 / 3 Foto Terpilih';
        if (layoutTitle) layoutTitle.innerText = 'Template Strip Bingkai (3 Foto)';
        if (studioSubtitle) studioSubtitle.innerText = 'Pilih tema frame di bawah, lalu atur 3 foto favoritmu ke slot template di kanan.';
    } else {
        if (btn6) {
            btn6.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border shadow-sm bg-ramadan-gold text-ramadan-green border-white ring-2 ring-ramadan-gold/50 cursor-pointer';
        }
        if (btn3) {
            btn3.className = 'px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border shadow-sm bg-black/30 text-ramadan-cream/80 border-ramadan-gold/30 hover:border-ramadan-gold hover:bg-black/50 cursor-pointer';
        }
        if (studioBadge) studioBadge.innerText = '6 / 6 Foto Terpilih';
        if (layoutTitle) layoutTitle.innerText = 'Template Grid 2×3 A5 (6 Foto)';
        if (studioSubtitle) studioSubtitle.innerText = 'Pilih tema frame di bawah, lalu atur 6 fotomu ke slot template grid 2×3 di kanan.';
    }
}

function updateStudioFramePreview() {
    updateStudioLayoutUI();
    updateStudioLivePreview();
}

window.selectTemplate = (id) => {
    selectedTemplateId = id;
    localStorage.setItem('selectedTemplateId', id);
    
    // Apply template settings
    const template = availableTemplates.find(t => t.id === id);
    if (template) {
        overlayMode = template.overlayMode;
        localStorage.setItem('overlayMode', overlayMode);

        // Auto match layout mode to template sizeType
        if (template.sizeType === 'a5_6grid') {
            studioLayoutMode = '6-grid';
        } else {
            studioLayoutMode = '3-strip';
        }
    } else {
        overlayMode = false;
        localStorage.setItem('overlayMode', 'false');
    }

    renderGallery();
    renderStudioThemeGallery();
    updateStudioLayoutUI();
    renderStudio();
    updateStudioLivePreview();
};

// Load and Apply Custom Booth Appearance Settings
async function loadBoothSettings() {
    // Try localStorage cached settings first for instant render
    try {
        const cached = localStorage.getItem('cachedBoothSettings');
        if (cached) {
            applyBoothSettings(JSON.parse(cached));
        }
    } catch (e) {}

    try {
        const res = await fetch('manage_settings.php?action=get&_t=' + Date.now(), { cache: 'no-store' });
        const data = await res.json();
        if (data.success && data.settings) {
            localStorage.setItem('cachedBoothSettings', JSON.stringify(data.settings));
            applyBoothSettings(data.settings);
        }
    } catch (err) {
        console.warn('Could not load custom booth settings, using defaults:', err);
    }
}

function applyBoothSettings(settings) {
    if (!settings) return;

    // 1. Header Title & Subtitle
    const titleEl = document.getElementById('booth-title');
    const subtitleEl = document.getElementById('booth-subtitle');
    if (titleEl && settings.title) {
        titleEl.textContent = settings.title;
        if (settings.titleColor) {
            titleEl.style.setProperty('color', settings.titleColor, 'important');
        }
    }
    if (subtitleEl && settings.subtitle) {
        subtitleEl.textContent = settings.subtitle;
        if (settings.subtitleColor) {
            subtitleEl.style.setProperty('color', settings.subtitleColor, 'important');
        }
    }

    // 2. Background Image & Background Color
    if (settings.bgImage) {
        document.body.style.setProperty('background-image', `url('${settings.bgImage}')`, 'important');
        document.body.style.setProperty('background-size', 'cover', 'important');
        document.body.style.setProperty('background-position', 'center', 'important');
        document.body.style.setProperty('background-repeat', 'no-repeat', 'important');
    } else if (settings.bgColor) {
        document.body.style.setProperty('background-color', settings.bgColor, 'important');
        document.body.style.setProperty('background-image', `radial-gradient(circle at 10% 20%, rgba(212, 140, 18, 0.1) 0%, transparent 30%), radial-gradient(circle at 90% 80%, rgba(212, 140, 18, 0.1) 0%, transparent 30%)`, 'important');
    }

    // 3. Side Decorations
    const decoContainer = document.getElementById('side-decorations-container');
    if (decoContainer) {
        if (settings.showDeco === false || settings.showDeco === 'false') {
            decoContainer.style.setProperty('display', 'none', 'important');
        } else {
            decoContainer.style.setProperty('display', 'block', 'important');
            const tl = document.getElementById('deco-top-left');
            const tr = document.getElementById('deco-top-right');
            const bl = document.getElementById('deco-bottom-left');
            const br = document.getElementById('deco-bottom-right');
            if (tl && settings.decoTopLeft) tl.src = settings.decoTopLeft;
            if (tr && settings.decoTopRight) tr.src = settings.decoTopRight;
            if (bl && settings.decoBottomLeft) bl.src = settings.decoBottomLeft;
            if (br && settings.decoBottomRight) br.src = settings.decoBottomRight;
        }
    }

    // 4. CSS Custom Properties for theme colors
    const root = document.documentElement;
    if (settings.primaryColor) {
        root.style.setProperty('--ramadan-primary', settings.primaryColor);
    }
    if (settings.bgColor) {
        root.style.setProperty('--ramadan-green', settings.bgColor);
    }
    if (settings.goldColor) {
        root.style.setProperty('--ramadan-gold', settings.goldColor);
    }
    if (settings.secondaryColor) {
        root.style.setProperty('--ramadan-secondary', settings.secondaryColor);
    }
}

loadTemplates();
loadBoothSettings();

// Initialize Camera
async function initCamera() {
    // Check if HTTPS (requirement for camera on non-localhost)
    const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
    if (!isLocal && window.location.protocol !== 'https:') {
        alert("🚨 Akses Kamera Ditolak: Halaman ini tidak menggunakan HTTPS. Browsers hanya mengizinkan kamera pada koneksi aman (HTTPS). Silakan aktifkan SSL di server Anda.");
        return;
    }

    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { 
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        });
        video.srcObject = stream;
    } catch (err) {
        console.error("Error accessing camera:", err);
        if (err.name === 'NotAllowedError') {
            alert("Izin Kamera Ditolak. Harap izinkan akses kamera di pengaturan browser Anda.");
        } else if (err.name === 'NotFoundError') {
            alert("Kamera tidak ditemukan. Pastikan kamera sudah terpasang.");
        } else {
            alert("Gagal mengakses kamera: " + err.message);
        }
    }
}

// --- VIEW MANAGEMENT ---
function showCameraView() {
    if (cameraSessionView) cameraSessionView.classList.remove('hidden');
    if (studioView) studioView.classList.add('hidden');
    if (previewView) previewView.classList.add('hidden');
    updateSlotsUI();
}

async function showStudioView() {
    const filledCount = capturedPhotos.filter(Boolean).length;
    if (filledCount < 3) {
        alert("Harap ambil minimal 3 foto terlebih dahulu!");
        return;
    }
    if (cameraSessionView) cameraSessionView.classList.add('hidden');
    if (studioView) studioView.classList.remove('hidden');
    if (previewView) previewView.classList.add('hidden');

    if (availableTemplates.length === 0) {
        await loadTemplates();
    }
    
    // Auto sync layout mode with template sizeType
    const currentTemplate = availableTemplates.find(t => t.id === selectedTemplateId);
    if (currentTemplate && currentTemplate.sizeType === 'a5_6grid') {
        studioLayoutMode = '6-grid';
    } else {
        studioLayoutMode = '3-strip';
    }

    updateStudioLayoutUI();
    renderStudioThemeGallery();
    renderStudio();
    updateStudioLivePreview();
}

function showPreviewView() {
    if (cameraSessionView) cameraSessionView.classList.add('hidden');
    if (studioView) studioView.classList.add('hidden');
    if (previewView) previewView.classList.remove('hidden');
}

// Update Side Slots UI & Buttons in Camera View
function updateSlotsUI() {
    for (let i = 0; i < TOTAL_PHOTOS; i++) {
        const slot = document.getElementById('slot-' + i);
        const slotImg = document.getElementById('slot-img-' + i);
        const slotEmpty = document.getElementById('slot-empty-' + i);
        const slotOverlay = document.getElementById('slot-overlay-' + i);

        if (!slot) continue;

        if (capturedPhotos[i]) {
            if (slotImg) {
                slotImg.src = capturedPhotos[i];
                slotImg.classList.remove('hidden');
            }
            if (slotEmpty) slotEmpty.classList.add('hidden');
            if (slotOverlay) slotOverlay.classList.remove('hidden');
        } else {
            if (slotImg) slotImg.classList.add('hidden');
            if (slotEmpty) slotEmpty.classList.remove('hidden');
            if (slotOverlay) slotOverlay.classList.add('hidden');
        }

        if (selectedSlotToRetake === i) {
            slot.classList.add('ring-4', 'ring-ramadan-gold', 'border-white', 'scale-[1.03]');
        } else {
            slot.classList.remove('ring-4', 'ring-ramadan-gold', 'border-white', 'scale-[1.03]');
        }
    }

    const filledCount = capturedPhotos.filter(Boolean).length;
    
    if (photoCounter) {
        photoCounter.innerText = `Foto ${filledCount} / ${TOTAL_PHOTOS}`;
    }

    // Toggle "Lanjut Pilih 3 Foto" button if at least 3 photos taken
    if (openStudioBtn) {
        if (filledCount >= 3) {
            openStudioBtn.classList.remove('hidden');
            openStudioBtn.innerHTML = `🎨 Lanjut Pilih 3 Foto (${filledCount}/6) &rarr;`;
        } else {
            openStudioBtn.classList.add('hidden');
        }
    }

    if (selectedSlotToRetake !== null) {
        captureBtnText.innerText = `Ulangi Foto #${selectedSlotToRetake + 1}`;
        if (cancelRetakeBtn) cancelRetakeBtn.classList.remove('hidden');
    } else {
        if (cancelRetakeBtn) cancelRetakeBtn.classList.add('hidden');
        const firstEmpty = capturedPhotos.findIndex(p => p === null);
        if (firstEmpty !== -1) {
            captureBtnText.innerText = `Ambil Foto #${firstEmpty + 1}`;
        } else {
            captureBtnText.innerText = `🎨 Masuk ke Studio Pilihan`;
        }
    }
}

// Photo Detail Modal Elements & Handlers
const photoDetailModal = document.getElementById('photo-detail-modal');
const photoDetailTitle = document.getElementById('photo-detail-title');
const photoDetailImg = document.getElementById('photo-detail-img');
const photoModalRetakeBtn = document.getElementById('photo-modal-retake-btn');
const photoModalCloseBtn = document.getElementById('photo-modal-close-btn');
const closePhotoModalBtn = document.getElementById('close-photo-modal-btn');
let activeModalSlotIndex = null;

function openPhotoDetailModal(index) {
    if (!capturedPhotos[index]) {
        // Slot is empty, select it for shooting
        showCameraView();
        selectedSlotToRetake = (selectedSlotToRetake === index) ? null : index;
        updateSlotsUI();
        return;
    }

    activeModalSlotIndex = index;
    if (photoDetailTitle) photoDetailTitle.innerText = `Hasil Foto #${index + 1}`;
    if (photoDetailImg) photoDetailImg.src = capturedPhotos[index];
    if (photoDetailModal) photoDetailModal.classList.remove('hidden');
}

function closePhotoDetailModal() {
    if (photoDetailModal) photoDetailModal.classList.add('hidden');
    activeModalSlotIndex = null;
}

window.selectSlotToRetake = (index) => {
    openPhotoDetailModal(index);
};
window.openPhotoDetailModal = openPhotoDetailModal;
window.closePhotoDetailModal = closePhotoDetailModal;

if (photoModalRetakeBtn) {
    photoModalRetakeBtn.addEventListener('click', () => {
        if (activeModalSlotIndex !== null) {
            selectedSlotToRetake = activeModalSlotIndex;
            closePhotoDetailModal();
            showCameraView();
            updateSlotsUI();
        }
    });
}

if (photoModalCloseBtn) {
    photoModalCloseBtn.addEventListener('click', closePhotoDetailModal);
}

if (closePhotoModalBtn) {
    closePhotoModalBtn.addEventListener('click', closePhotoDetailModal);
}

if (photoDetailModal) {
    photoDetailModal.addEventListener('click', (e) => {
        if (e.target === photoDetailModal) {
            closePhotoDetailModal();
        }
    });
}

if (cancelRetakeBtn) {
    cancelRetakeBtn.addEventListener('click', () => {
        selectedSlotToRetake = null;
        updateSlotsUI();
    });
}

if (openStudioBtn) {
    openStudioBtn.addEventListener('click', showStudioView);
}

if (studioBackBtn) {
    studioBackBtn.addEventListener('click', showCameraView);
}

if (studioConfirmBtn) {
    studioConfirmBtn.addEventListener('click', () => {
        saveAndRequestPrint(studioConfirmBtn);
    });
}

if (retakeSingleBtn) {
    retakeSingleBtn.addEventListener('click', showStudioView);
}

const layoutMode3Btn = document.getElementById('layout-mode-3-btn');
const layoutMode6Btn = document.getElementById('layout-mode-6-btn');
if (layoutMode3Btn) {
    layoutMode3Btn.addEventListener('click', () => window.setStudioLayoutMode('3-strip'));
}
if (layoutMode6Btn) {
    layoutMode6Btn.addEventListener('click', () => window.setStudioLayoutMode('6-grid'));
}

// Initial Side Slots State
updateSlotsUI();

// Capture Photo Process with Countdown (Max 6)
captureBtn.addEventListener('click', async () => {
    let targetIndex = selectedSlotToRetake;
    if (targetIndex === null) {
        targetIndex = capturedPhotos.findIndex(p => p === null);
    }
    
    // If all 6 already filled and user clicks the button
    if (targetIndex === -1) {
        showStudioView();
        return;
    }

    captureBtn.disabled = true;
    captureBtn.classList.add('opacity-50', 'cursor-not-allowed');

    // Start Countdown
    countdownOverlay.classList.remove('hidden');
    for (let i = 3; i > 0; i--) {
        countdownNumber.innerText = i;
        await new Promise(r => setTimeout(r, 1000));
    }
    countdownOverlay.classList.add('hidden');

    // Flash Effect
    flash.classList.add('flash-active');
    setTimeout(() => flash.classList.remove('flash-active'), 300);

    // Target aspect ratio based on imgWidth (920) and imgHeight (450)
    const targetAspect = 920 / 450;
    const videoAspect = video.videoWidth / video.videoHeight;
    
    let drawWidth = video.videoWidth;
    let drawHeight = video.videoHeight;
    let startX = 0;
    let startY = 0;

    // If video is wider than target aspect ratio, crop sides
    if (videoAspect > targetAspect) {
        drawWidth = video.videoHeight * targetAspect;
        startX = (video.videoWidth - drawWidth) / 2;
    } 
    // If video is taller than target aspect, crop top/bottom
    else if (videoAspect < targetAspect) {
        drawHeight = video.videoWidth / targetAspect;
        startY = (video.videoHeight - drawHeight) / 2;
    }

    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = 920; // Expected width
    tempCanvas.height = 450; // Expected height
    const tempCtx = tempCanvas.getContext('2d');
    
    // Draw cropped image (mirrored to match preview)
    tempCtx.save();
    tempCtx.translate(tempCanvas.width, 0);
    tempCtx.scale(-1, 1);
    tempCtx.drawImage(
        video, 
        startX, startY, drawWidth, drawHeight,
        0, 0, tempCanvas.width, tempCanvas.height
    );
    tempCtx.restore();
    
    capturedPhotos[targetIndex] = tempCanvas.toDataURL('image/jpeg', 0.88);
    selectedSlotToRetake = null;
    
    captureBtn.disabled = false;
    captureBtn.classList.remove('opacity-50', 'cursor-not-allowed');

    updateSlotsUI();

    // If all 6 photos are filled, transition to Studio View
    if (capturedPhotos.every(Boolean)) {
        setTimeout(showStudioView, 400);
    }
});

// --- STUDIO DRAG & DROP / CLICK-TO-ASSIGN LOGIC ---
const SLOT_LABELS_6 = [
    '#1 Kiri Atas', '#2 Kanan Atas',
    '#3 Kiri Tengah', '#4 Kanan Tengah',
    '#5 Kiri Bawah', '#6 Kanan Bawah'
];

const SLOT_LABELS_3 = [
    'Slot 1 (Atas)', 'Slot 2 (Tengah)', 'Slot 3 (Bawah)'
];

window.setStudioLayoutMode = (mode) => {
    studioLayoutMode = mode;
    updateStudioLayoutUI();
    renderStudio();
    updateStudioLivePreview();
};

function renderStudio() {
    const poolGrid = document.getElementById('studio-pool-grid');
    if (!poolGrid) return;

    poolGrid.innerHTML = '';

    const is6Grid = studioLayoutMode === '6-grid';
    const activeSlotList = is6Grid ? selected6Photos : selectedStripPhotos;
    const numSlots = is6Grid ? 6 : 3;
    const slotLabels = is6Grid ? SLOT_LABELS_6 : SLOT_LABELS_3;

    // Auto-fill activeSlotList with valid indices if missing
    const availableIndices = [];
    capturedPhotos.forEach((p, idx) => { if (p) availableIndices.push(idx); });

    for (let s = 0; s < numSlots; s++) {
        if (capturedPhotos[activeSlotList[s]] === null || capturedPhotos[activeSlotList[s]] === undefined) {
            if (availableIndices[s] !== undefined) {
                activeSlotList[s] = availableIndices[s];
            } else if (availableIndices.length > 0) {
                activeSlotList[s] = availableIndices[0];
            }
        }
    }

    // 1. Render Left Pool (6 Available Photos)
    capturedPhotos.forEach((photoUrl, idx) => {
        if (!photoUrl) return;

        const slotInActive = activeSlotList.indexOf(idx);
        let badgeHtml = '';
        if (slotInActive !== -1) {
            badgeHtml = `<span class="px-2 py-0.5 bg-ramadan-gold text-ramadan-green rounded-full text-[9px] font-black shadow">${slotLabels[slotInActive]}</span>`;
        } else {
            badgeHtml = `<span class="px-2 py-0.5 bg-black/60 text-white/90 rounded-full text-[9px] font-bold">Tersedia</span>`;
        }

        const isSelectedActive = activePoolSelectIndex === idx;

        const card = document.createElement('div');
        card.className = `relative aspect-[920/450] rounded-xl overflow-hidden border-2 ${
            isSelectedActive 
                ? 'ring-4 ring-emerald-400 border-white scale-105 shadow-xl' 
                : (slotInActive !== -1 ? 'border-ramadan-gold/80 shadow-md' : 'border-white/30')
        } bg-black/40 cursor-grab active:cursor-grabbing transition-all hover:scale-[1.02] group`;
        card.draggable = true;

        card.innerHTML = `
            <span class="absolute top-1.5 left-2 bg-black/70 text-ramadan-cream text-[10px] font-black px-2 py-0.5 rounded shadow z-10">#${idx + 1}</span>
            <div class="absolute top-1.5 right-2 z-10">${badgeHtml}</div>
            <img src="${photoUrl}" class="w-full h-full object-cover pointer-events-none">
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[11px] font-bold p-1 text-center">
                ${isSelectedActive ? '✓ Terpilih (Pencet Slot di Kanan)' : '👆 Klik atau Drag ke Slot di Kanan'}
            </div>
        `;

        card.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', idx.toString());
            card.classList.add('opacity-50');
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('opacity-50');
        });

        card.addEventListener('click', () => {
            if (activePoolSelectIndex === idx) {
                activePoolSelectIndex = null;
            } else {
                activePoolSelectIndex = idx;
            }
            renderStudio();
        });

        poolGrid.appendChild(card);
    });

    // 2. Render Column 2: Dedicated Dropzone Slots (Tempat Drop-Drop Foto)
    const dropzonesWrapper = document.getElementById('studio-dropzones-wrapper');
    if (dropzonesWrapper) {
        dropzonesWrapper.innerHTML = '';
        if (is6Grid) {
            dropzonesWrapper.className = 'grid grid-cols-2 gap-2 flex-1 w-full min-h-[350px] p-1';
        } else {
            dropzonesWrapper.className = 'flex flex-col justify-between gap-2.5 flex-1 w-full min-h-[350px] p-1';
        }

        for (let s = 0; s < numSlots; s++) {
            const photoIdx = activeSlotList[s];
            const photoSrc = (photoIdx !== null && photoIdx !== undefined && capturedPhotos[photoIdx]) ? capturedPhotos[photoIdx] : '';

            const dropzone = document.createElement('div');
            dropzone.id = 'dropzone-' + s;
            dropzone.className = `dropzone-slot relative flex-1 aspect-[920/450] ${is6Grid ? 'min-h-[85px]' : 'min-h-[105px]'} rounded-xl overflow-hidden border-2 border-ramadan-gold/70 bg-black/60 cursor-pointer transition-all flex items-center justify-center group shadow-md hover:border-ramadan-gold hover:scale-[1.01]`;

            dropzone.innerHTML = `
                <span class="absolute top-1.5 left-2 bg-ramadan-gold text-ramadan-green text-[9px] md:text-[10px] font-black px-2 py-0.5 rounded shadow z-10">${slotLabels[s]}</span>
                ${photoSrc ? `<img id="dropzone-img-${s}" src="${photoSrc}" class="w-full h-full object-cover">` : `<span class="text-ramadan-cream/40 text-xs font-bold flex flex-col items-center gap-1">📷 Slot Kosong</span>`}
                <div class="absolute inset-0 bg-black/70 text-white text-[10px] md:text-[11px] font-bold opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1 backdrop-blur-[1px] text-center p-1">
                    ${activePoolSelectIndex !== null ? `✓ Pasang Foto #${activePoolSelectIndex + 1} ke ${slotLabels[s]}` : `📥 Drop / Klik Ganti Foto`}
                </div>
            `;

            dropzone.addEventListener('dragover', (e) => window.handleDragOver(e, s));
            dropzone.addEventListener('dragleave', (e) => window.handleDragLeave(e, s));
            dropzone.addEventListener('drop', (e) => window.handleDrop(e, s));
            dropzone.addEventListener('click', () => window.handleSlotClick(s));

            dropzonesWrapper.appendChild(dropzone);
        }
    }

    updateStudioLivePreview();
}

window.handleDragOver = (e, slotIndex) => {
    e.preventDefault();
    const dropzone = document.getElementById('dropzone-' + slotIndex);
    if (dropzone) {
        dropzone.classList.add('ring-4', 'ring-emerald-400', 'border-white', 'scale-102', 'bg-emerald-500/20');
    }
};

window.handleDragLeave = (e, slotIndex) => {
    const dropzone = document.getElementById('dropzone-' + slotIndex);
    if (dropzone) {
        dropzone.classList.remove('ring-4', 'ring-emerald-400', 'border-white', 'scale-102', 'bg-emerald-500/20');
    }
};

window.handleDrop = (e, slotIndex) => {
    e.preventDefault();
    const dropzone = document.getElementById('dropzone-' + slotIndex);
    if (dropzone) {
        dropzone.classList.remove('ring-4', 'ring-emerald-400', 'border-white', 'scale-102', 'bg-emerald-500/20');
    }

    const photoIdxStr = e.dataTransfer.getData('text/plain');
    if (photoIdxStr !== '') {
        const photoIdx = parseInt(photoIdxStr);
        if (capturedPhotos[photoIdx]) {
            if (studioLayoutMode === '6-grid') {
                selected6Photos[slotIndex] = photoIdx;
            } else {
                selectedStripPhotos[slotIndex] = photoIdx;
            }
            activePoolSelectIndex = null;
            renderStudio();
        }
    }
};

window.handleSlotClick = (slotIndex) => {
    const is6Grid = studioLayoutMode === '6-grid';
    const targetArray = is6Grid ? selected6Photos : selectedStripPhotos;

    if (activePoolSelectIndex !== null) {
        targetArray[slotIndex] = activePoolSelectIndex;
        activePoolSelectIndex = null;
        renderStudio();
    } else {
        // Rotate to next available photo index
        const available = capturedPhotos.map((p, i) => p ? i : null).filter(i => i !== null);
        if (available.length > 0) {
            const currentIdx = targetArray[slotIndex];
            const nextIdx = available[(available.indexOf(currentIdx) + 1) % available.length];
            targetArray[slotIndex] = nextIdx;
            renderStudio();
        }
    }
};

// --- UNIFIED CANVAS DRAWING ENGINE (LIVE PREVIEW & FINAL STRIP) ---
async function renderStripCanvas(stripCanvas) {
    const ctx = stripCanvas.getContext('2d');
    const template = availableTemplates.find(t => t.id === selectedTemplateId);
    
    // Check if 6 Photos Grid Mode on A5
    if (studioLayoutMode === '6-grid') {
        // A5 Resolution (1748 x 2480 at 300 DPI)
        stripCanvas.width = 1748;
        stripCanvas.height = 2480;

        // 1. Draw Background
        if (!overlayMode) {
            let bgSource = './gambar/background.png';
            if (template && template.outer) bgSource = template.outer;
            const bgImg = await loadCachedImage(bgSource);
            if (bgImg) {
                ctx.drawImage(bgImg, 0, 0, stripCanvas.width, stripCanvas.height);
            } else {
                ctx.fillStyle = '#FFFDF5';
                ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);
            }
        } else {
            ctx.fillStyle = '#FFFDF5';
            ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);
        }

        // 2. Draw 6 Photos in 2 columns x 3 rows
        const paddingX = 94;
        const gapX = 40;
        const gapY = 45;
        const imgWidth = Math.round((stripCanvas.width - (2 * paddingX) - gapX) / 2); // ~760 px
        const imgHeight = Math.round(imgWidth * (450 / 920)); // ~372 px
        const totalGridH = (3 * imgHeight) + (2 * gapY); // ~1206 px
        const topY = Math.round((stripCanvas.height - totalGridH) / 2); // ~637 px (centered on A5)
        const cornerRadius = 24;

        const final6Photos = selected6Photos.map((idx, s) => capturedPhotos[idx] || capturedPhotos[s] || capturedPhotos[0]);
        const innerImg = (template && template.inner) ? await loadCachedImage(template.inner) : null;

        for (let index = 0; index < final6Photos.length; index++) {
            const dataUrl = final6Photos[index];
            if (!dataUrl) continue;
            const col = index % 2;
            const row = Math.floor(index / 2);
            const posX = paddingX + col * (imgWidth + gapX);
            const posY = topY + row * (imgHeight + gapY);

            const img = await loadCachedImage(dataUrl);
            if (img) {
                ctx.save();
                ctx.beginPath();
                ctx.roundRect(posX, posY, imgWidth, imgHeight, cornerRadius);
                ctx.clip();
                ctx.drawImage(img, posX, posY, imgWidth, imgHeight);
                ctx.restore();

                // Draw golden frame border
                ctx.strokeStyle = '#D4AF37';
                ctx.lineWidth = 6;
                ctx.beginPath();
                ctx.roundRect(posX, posY, imgWidth, imgHeight, cornerRadius);
                ctx.stroke();

                if (innerImg) {
                    ctx.drawImage(innerImg, posX - 6, posY - 6, imgWidth + 12, imgHeight + 12);
                }
            }
        }

        // Ornaments for 6-Grid
        // 1. Ketupat (Slot #2 Kanan Atas)
        let ketupatSource = (template && template.ketupat) ? template.ketupat : './gambar/ketupat.webp';
        const ketupatImg = await loadCachedImage(ketupatSource);
        if (ketupatImg) {
            const layout = (template && template.layout && template.layout.ketupat) ? template.layout.ketupat : { size: 350, x: 120, y: 150 };
            const kSize = layout.size;
            const slotX = paddingX + imgWidth + gapX;
            const slotY = topY;
            const x = slotX + imgWidth - kSize + layout.x;
            const y = slotY + imgHeight - kSize + layout.y;
            ctx.drawImage(ketupatImg, x, y, kSize, kSize);
        }

        // 2. Lampu (Slot #3 Kiri Tengah)
        let lampuSource = (template && template.lampu) ? template.lampu : './gambar/lampu.webp';
        const lampuImg = await loadCachedImage(lampuSource);
        if (lampuImg) {
            const layout = (template && template.layout && template.layout.lampu) ? template.layout.lampu : { size: 300, x: -100, y: 140 };
            const lSize = layout.size;
            const slotX = paddingX;
            const slotY = topY + imgHeight + gapY;
            const x = slotX + layout.x;
            const y = slotY + imgHeight - lSize + layout.y;
            ctx.drawImage(lampuImg, x, y, lSize, lSize);
        }

        // 3. Rama (Slot #6 Kanan Bawah)
        let ramaSource = (template && template.rama) ? template.rama : './gambar/rama.png';
        const ramaImg = await loadCachedImage(ramaSource);
        if (ramaImg) {
            const layout = (template && template.layout && template.layout.rama) ? template.layout.rama : { size: 550, x: 150, y: 300 };
            const rSize = layout.size;
            const slotX = paddingX + imgWidth + gapX;
            const slotY = topY + (2 * (imgHeight + gapY));
            const x = slotX + imgWidth - rSize + layout.x;
            const y = slotY + imgHeight - rSize + layout.y;
            ctx.drawImage(ramaImg, x, y, rSize, rSize);
        }

        // Overlay Theme (if overlayMode)
        if (overlayMode && template && template.outer) {
            const overlayImg = await loadCachedImage(template.outer);
            if (overlayImg) {
                ctx.drawImage(overlayImg, 0, 0, stripCanvas.width, stripCanvas.height);
            }
        }

        // Corner Decoration
        await drawDecorations(ctx, stripCanvas.width, stripCanvas.height);
        return;
    }

    // 3 Photos Strip Mode (Vertical)
    const isA5 = (template?.sizeType || 'a5') === 'a5';
    let imgWidth, imgHeight, padding, headerHeight, footerHeight, gap;

    if (isA5) {
        stripCanvas.width = 1748;
        stripCanvas.height = 2480;
        imgWidth = 1540;
        imgHeight = 650;
        padding = 104;
        headerHeight = 180;
        footerHeight = 230;
        gap = 60;
    } else {
        stripCanvas.width = 1080;
        stripCanvas.height = 1920;
        imgWidth = 920;
        imgHeight = 450;
        padding = 80;
        headerHeight = 150;
        footerHeight = 100;
        gap = 80;
    }
    
    // 1. Draw Background (only if NOT in overlay mode)
    if (!overlayMode) {
        let bgSource = './gambar/background.png';
        if (template && template.outer) bgSource = template.outer;
        const bgImg = await loadCachedImage(bgSource);
        if (bgImg) {
            ctx.drawImage(bgImg, 0, 0, stripCanvas.width, stripCanvas.height);
        } else {
            ctx.fillStyle = '#FFFDF5';
            ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);
        }
    } else {
        ctx.fillStyle = '#FFFDF5';
        ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);
    }

    // 2. Draw 3 Selected Photos from selectedStripPhotos
    const final3Photos = [
        capturedPhotos[selectedStripPhotos[0]] || capturedPhotos[0],
        capturedPhotos[selectedStripPhotos[1]] || capturedPhotos[1] || capturedPhotos[0],
        capturedPhotos[selectedStripPhotos[2]] || capturedPhotos[2] || capturedPhotos[0]
    ];

    let frameSource = './gambar/atassebagaibingkai.png';
    if (template && template.inner) frameSource = template.inner;
    const innerFrameImg = await loadCachedImage(frameSource);

    for (let index = 0; index < final3Photos.length; index++) {
        const dataUrl = final3Photos[index];
        if (!dataUrl) continue;
        const img = await loadCachedImage(dataUrl);
        if (img) {
            const yPos = padding + headerHeight + (index * (imgHeight + gap));
            const cornerRadius = 30;
            
            // Draw the photo with rounded corners
            ctx.save();
            ctx.beginPath();
            ctx.roundRect(padding, yPos, imgWidth, imgHeight, cornerRadius - 5);
            ctx.clip();
            ctx.drawImage(img, padding, yPos, imgWidth, imgHeight);
            ctx.restore();

            // Draw frame
            if (innerFrameImg) {
                ctx.drawImage(innerFrameImg, padding - 10, yPos - 10, imgWidth + 20, imgHeight + 20);
            }

            // Ketupat on index 0
            if (index === 0) {
                let ketupatSource = (template && template.ketupat) ? template.ketupat : './gambar/ketupat.webp';
                const ketupatImg = await loadCachedImage(ketupatSource);
                if (ketupatImg) {
                    const layout = (template && template.layout && template.layout.ketupat) ? template.layout.ketupat : { size: 350, x: 120, y: 150 };
                    const kSize = layout.size;
                    const x = padding + imgWidth - kSize + layout.x;
                    const y = yPos + imgHeight - kSize + layout.y;
                    ctx.drawImage(ketupatImg, x, y, kSize, kSize);
                }
            }

            // Lampu on index 1
            if (index === 1) {
                let lampuSource = (template && template.lampu) ? template.lampu : './gambar/lampu.webp';
                const lampuImg = await loadCachedImage(lampuSource);
                if (lampuImg) {
                    const layout = (template && template.layout && template.layout.lampu) ? template.layout.lampu : { size: 300, x: -100, y: 140 };
                    const lSize = layout.size;
                    const x = padding + layout.x;
                    const y = yPos + imgHeight - lSize + layout.y;
                    ctx.drawImage(lampuImg, x, y, lSize, lSize);
                }
            }

            // Rama on index 2
            if (index === 2) {
                let ramaSource = (template && template.rama) ? template.rama : './gambar/rama.png';
                const ramaImg = await loadCachedImage(ramaSource);
                if (ramaImg) {
                    const layout = (template && template.layout && template.layout.rama) ? template.layout.rama : { size: 550, x: 150, y: 300 };
                    const rSize = layout.size;
                    const x = padding + imgWidth - rSize + layout.x;
                    const y = yPos + imgHeight - rSize + layout.y;
                    ctx.drawImage(ramaImg, x, y, rSize, rSize);
                }
            }
        }
    }

    // Overlay Theme (if overlayMode)
    if (overlayMode && template && template.outer) {
        const overlayImg = await loadCachedImage(template.outer);
        if (overlayImg) {
            ctx.drawImage(overlayImg, 0, 0, stripCanvas.width, stripCanvas.height);
        }
    }

    // Corner Decoration
    await drawDecorations(ctx, stripCanvas.width, stripCanvas.height);
}

// Live preview renderer for Studio View
let isLivePreviewRendering = false;
let pendingLivePreview = false;

async function updateStudioLivePreview() {
    if (isLivePreviewRendering) {
        pendingLivePreview = true;
        return;
    }
    isLivePreviewRendering = true;

    const liveImg = document.getElementById('studio-live-preview-img');
    const spinner = document.getElementById('studio-preview-spinner');

    const spinnerTimer = setTimeout(() => {
        if (spinner) spinner.classList.remove('hidden');
    }, 60);

    try {
        await renderStripCanvas(canvas);
        const dataUrl = canvas.toDataURL('image/jpeg', 0.90);
        if (liveImg) {
            liveImg.src = dataUrl;
        }
        if (photoPreview) {
            photoPreview.src = dataUrl;
        }
    } catch (err) {
        console.error('Error updating live preview:', err);
    } finally {
        clearTimeout(spinnerTimer);
        if (spinner) spinner.classList.add('hidden');
        isLivePreviewRendering = false;
        if (pendingLivePreview) {
            pendingLivePreview = false;
            updateStudioLivePreview();
        }
    }
}

// --- GENERATE FINAL PHOTO STRIP & TRANSITION TO VIEW 3 ---
async function generateStrip() {
    processingOverlay.classList.remove('hidden');
    try {
        await renderStripCanvas(canvas);
        const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
        photoPreview.src = dataUrl;
        showPreviewView();
    } catch (err) {
        console.error('Error generating strip:', err);
    } finally {
        processingOverlay.classList.add('hidden');
    }
}

async function drawDecorations(ctx, width, height) {
    const cornerImg = await loadCachedImage('./gambar/pojokkiribawah.webp');
    if (cornerImg) {
        const cornerWidth = 200;
        const cornerHeight = (cornerImg.height / cornerImg.width) * cornerWidth;
        ctx.drawImage(cornerImg, 20, height - cornerHeight - 20, cornerWidth, cornerHeight);
    }
}

// Retake / Reset All Photos (Start new 6-shot session)
retakeBtn.addEventListener('click', () => {
    capturedPhotos = [null, null, null, null, null, null];
    selectedSlotToRetake = null;
    selectedStripPhotos = [0, 1, 2];
    activePoolSelectIndex = null;
    showCameraView();
});

// Download Photo
downloadBtn.addEventListener('click', () => {
    try {
        const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
        const link = document.createElement('a');
        link.style.display = 'none';
        link.href = dataUrl;
        link.download = 'ramadhankarimmmahaghora1.jpg';
        document.body.appendChild(link);
        link.click();
        setTimeout(() => document.body.removeChild(link), 100);
    } catch (err) {
        console.error("Download failed:", err);
        canvas.toBlob((blob) => {
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'ramadhankarimmmahaghora1.jpg';
            link.click();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        }, 'image/jpeg', 0.95);
    }
});

// Session state
let currentSessionId = null;
let saveCount = 0;
let currentViewUrl = null;

const selesaiContainer = document.getElementById('selesai-container');
const selesaiBtn = document.getElementById('selesai-btn');
const saveCountBadge = document.getElementById('save-count-badge');

function resetCameraForNextRound() {
    capturedPhotos = [null, null, null, null, null, null];
    selectedSlotToRetake = null;
    selectedStripPhotos = [0, 1, 2];
    activePoolSelectIndex = null;
    showCameraView();
}

function resetSaveButton() {
    saveBtn.disabled = false;
    saveBtn.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
        </svg>
        Simpan
    `;
}

// Save Photo to Server & Request Print to Operator Booth
async function saveAndRequestPrint(buttonTrigger = null) {
    let btnTextEl = null;
    let originalText = '';
    
    if (buttonTrigger) {
        buttonTrigger.disabled = true;
        btnTextEl = buttonTrigger.querySelector('span:last-child') || buttonTrigger;
        originalText = btnTextEl.innerText;
        btnTextEl.innerText = 'Mengirim ke Antrean Cetak...';
    }

    if (processingOverlay) processingOverlay.classList.remove('hidden');

    try {
        // 1. Render final strip on canvas
        await renderStripCanvas(canvas);
        const stripDataUrl = canvas.toDataURL('image/jpeg', 0.92);
        const validPhotos = capturedPhotos.filter(Boolean);
        const allImages = [...validPhotos, stripDataUrl];

        // 2. Upload to upload.php
        const payload = { images: allImages };
        if (currentSessionId) {
            payload.session_id = currentSessionId;
        }

        const res = await fetch('upload.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (!res.ok) throw new Error(`Upload failed: ${res.statusText}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.error || "Server upload error");

        currentSessionId = data.session_id;
        currentViewUrl = data.view_url;
        saveCount++;
        if (saveCountBadge) saveCountBadge.innerText = saveCount;
        if (selesaiContainer) selesaiContainer.classList.remove('hidden');

        // Determine strip filename from saved_files
        const savedFiles = data.saved_files || [];
        const stripFile = savedFiles.find(f => f.includes('strip')) || savedFiles[savedFiles.length - 1] || 'round_1_strip.jpeg';
        const photoUrl = `uploads/${data.session_id}/${stripFile}`;

        // 3. Send Print Request to print_action.php
        const template = availableTemplates.find(t => t.id === selectedTemplateId);
        const templateLabel = template ? template.name : 'Tema Standar';
        const formatLabel = (studioLayoutMode === '6-grid' || (template && (template.sizeType === 'a5_6grid' || template.sizeType === '4r_6grid'))) ? 'A5 6-Grid' : 'A5 3-Strip';

        try {
            await fetch('print_action.php?action=request_print', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: data.session_id,
                    photo_url: photoUrl,
                    label: `${templateLabel} (${formatLabel})`,
                    copies: 1
                })
            });
        } catch (printErr) {
            console.warn("Print request network warning:", printErr);
        }

        // 4. Open QR Code Modal with QR image
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(data.view_url)}`;
        qrImage.src = qrApiUrl;
        qrModal.classList.remove('hidden');

    } catch (err) {
        console.error("Gagal cetak / simpan:", err);
        alert("Gagal memproses cetak: " + (err.message || "Pastikan server aktif"));
    } finally {
        if (processingOverlay) processingOverlay.classList.add('hidden');
        if (buttonTrigger && btnTextEl) {
            buttonTrigger.disabled = false;
            btnTextEl.innerText = originalText;
        }
    }
}

// Wire up Save / Print Buttons
if (saveBtn) {
    saveBtn.addEventListener('click', () => saveAndRequestPrint(saveBtn));
}

// Selesai Button — show QR Code
if (selesaiBtn) {
    selesaiBtn.addEventListener('click', () => {
        if (!currentViewUrl) return;
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(currentViewUrl)}`;
        qrImage.src = qrApiUrl;
        qrModal.classList.remove('hidden');
    });
}

// QR Modal Handlers
if (closeQrBtn) {
    closeQrBtn.addEventListener('click', () => {
        qrModal.classList.add('hidden');
    });
}

// Finish Session — start fresh for next person
if (finishSessionBtn) {
    finishSessionBtn.addEventListener('click', () => {
        qrModal.classList.add('hidden');
        
        // Reset session state for the next person
        currentSessionId = null;
        currentViewUrl = null;
        saveCount = 0;
        if (saveCountBadge) saveCountBadge.innerText = '0';
        if (selesaiContainer) selesaiContainer.classList.add('hidden');
        
        // Reset camera
        resetCameraForNextRound();
    });
}

// Start camera and load templates on load
window.addEventListener('load', () => {
    initCamera();
    loadTemplates();
});
