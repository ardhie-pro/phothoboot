// QR Code generated via https://api.qrserver.com/v1/create-qr-code/

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
// ... other existing elements
const captureBtn = document.getElementById('capture-btn');
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
let capturedPhotos = [];
const TOTAL_PHOTOS = 3;

// Theme State
let overlayMode = localStorage.getItem('overlayMode') === 'true';
let selectedTemplateId = localStorage.getItem('selectedTemplateId') || '';
let availableTemplates = [];

const settingsToggle = document.getElementById('settings-toggle');
const settingsModal = document.getElementById('settings-modal');
const closeSettings = document.getElementById('close-settings');
const resetThemeBtn = document.getElementById('reset-theme');

// Initial Setup
settingsToggle.addEventListener('click', () => settingsModal.classList.remove('hidden'));
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

// Fetch and Render Templates
async function loadTemplates() {
    try {
        const res = await fetch('manage_templates.php?action=list');
        availableTemplates = await res.json();
        
        // Auto-select first if none selected or invalid
        const exists = availableTemplates.find(t => t.id === selectedTemplateId);
        if (!exists && availableTemplates.length > 0) {
            window.selectTemplate(availableTemplates[0].id);
        }
        
        renderGallery();
    } catch (err) {
        console.error("Failed to load templates:", err);
    }
}

function renderGallery() {
    const gallery = document.getElementById('template-gallery');
    if (!gallery) return;

    // Clear gallery
    gallery.innerHTML = '';

    let html = '';
    // Add dynamics
    availableTemplates.forEach(t => {
        const isActive = selectedTemplateId === t.id;
        html += `
            <button onclick="window.selectTemplate('${t.id}')" 
                class="template-item group relative aspect-[9/16] rounded-[2.5rem] overflow-hidden transition-all duration-500 
                ${isActive ? 'ring-[12px] ring-ramadan-gold/70 border-8 border-white' : 'border-4 border-ramadan-gold/10 hover:border-ramadan-gold/50 shadow-xl'}">
                
                <!-- Full Preview Image -->
                <img src="${t.outer}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                
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

window.selectTemplate = (id) => {
    selectedTemplateId = id;
    localStorage.setItem('selectedTemplateId', id);
    
    // Apply template settings
    const template = availableTemplates.find(t => t.id === id);
    if (template) {
        overlayMode = template.overlayMode;
        localStorage.setItem('overlayMode', overlayMode);
    } else {
        // Reset if not found
        overlayMode = false;
        localStorage.setItem('overlayMode', 'false');
    }

    renderGallery();
};

loadTemplates();

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

// Capture Photo Process with Countdown
captureBtn.addEventListener('click', async () => {
    if (capturedPhotos.length >= TOTAL_PHOTOS) return;
    
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
    
    // Draw cropped image
    tempCtx.drawImage(
        video, 
        startX, startY, drawWidth, drawHeight, // Source rectangle
        0, 0, tempCanvas.width, tempCanvas.height // Destination rectangle
    );
    
    capturedPhotos.push(tempCanvas.toDataURL('image/png'));
    
    photoCounter.classList.remove('hidden');
    photoCounter.innerText = `Foto ${capturedPhotos.length} / ${TOTAL_PHOTOS}`;

    captureBtn.disabled = false;
    captureBtn.classList.remove('opacity-50', 'cursor-not-allowed');

    if (capturedPhotos.length === TOTAL_PHOTOS) {
        generateStrip();
    }
});

async function generateStrip() {
    processingOverlay.classList.remove('hidden');
    const stripCanvas = canvas;
    const ctx = stripCanvas.getContext('2d');
    
    // Standard dimensions for the strip (results in 1080x1920)
    const imgWidth = 920;
    const imgHeight = 450;
    const padding = 80;
    const headerHeight = 150; 
    const footerHeight = 100;
    
    stripCanvas.width = imgWidth + (padding * 2);
    stripCanvas.height = (imgHeight * 3) + (padding * 4) + headerHeight + footerHeight;
    
    // 1. Draw Background (only if NOT in overlay mode)
    if (!overlayMode) {
        const bgImg = new Image();
        
        // Template Selection Logic
        let bgSource = './gambar/background.png'; // Hard fallback if no template selected
        const template = availableTemplates.find(t => t.id === selectedTemplateId);
        if (template && template.outer) bgSource = template.outer;
        
        bgImg.src = bgSource;
        await new Promise((resolve) => {
            bgImg.onload = () => {
                ctx.drawImage(bgImg, 0, 0, stripCanvas.width, stripCanvas.height);
                resolve();
            };
            bgImg.onerror = () => {
                ctx.fillStyle = '#FFFDF5';
                ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);
                resolve();
            };
        });
    } else {
        // Just fill solid background first if it's going to be an overlay
        ctx.fillStyle = '#FFFDF5';
        ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);
    }

    // 2. Draw 3 Photos with their respective Frames
    const imagePromises = capturedPhotos.map((dataUrl, index) => {
        return new Promise(async (resolve) => {
            const img = new Image();
            img.onload = async () => {
                const yPos = padding + headerHeight + (index * (imgHeight + padding));
                const cornerRadius = 30;
                
                // (REMOVED SOLID BACKGROUND TO PREVENT COVERING TEMA LUAR)
                
                // Draw the photo
                ctx.save();
                ctx.beginPath();
                ctx.roundRect(padding, yPos, imgWidth, imgHeight, cornerRadius - 5);
                ctx.clip();
                ctx.drawImage(img, padding, yPos, imgWidth, imgHeight);
                ctx.restore();

                // 3. Draw individual frame for each camera result
                const frameImg = new Image();
                
                // Frame Selection Logic
                let frameSource = './gambar/atassebagaibingkai.png';
                const template = availableTemplates.find(t => t.id === selectedTemplateId);
                if (template && template.inner) frameSource = template.inner;

                frameImg.src = frameSource;
                await new Promise((res) => {
                    frameImg.onload = () => {
                        // Draw exactly over the photo slot
                        ctx.drawImage(frameImg, padding - 10, yPos - 10, imgWidth + 20, imgHeight + 20);
                        res();
                    };
                    frameImg.onerror = res; // Skip if frame doesn't exist
                });

                // Add Ketupat on the FIRST photo (index 0) bottom right
                if (index === 0) {
                    const ketupatImg = new Image();
                    const template = availableTemplates.find(t => t.id === selectedTemplateId);
                    
                    let ketupatSource = './gambar/ketupat.webp';
                    if (template && template.ketupat) ketupatSource = template.ketupat;
                    
                    ketupatImg.src = ketupatSource;
                    await new Promise((res) => {
                        ketupatImg.onload = () => {
                            const layout = (template && template.layout && template.layout.ketupat) ? template.layout.ketupat : { size: 350, x: 120, y: 150 };
                            const kSize = layout.size;
                            const x = padding + imgWidth - kSize + layout.x;
                            const y = yPos + imgHeight - kSize + layout.y;

                            ctx.drawImage(ketupatImg, x, y, kSize, kSize);
                            res();
                        };
                        ketupatImg.onerror = res;
                    });
                }

                // Add Lampu on the SECOND photo (index 1) bottom left
                if (index === 1) {
                    const lampuImg = new Image();
                    const template = availableTemplates.find(t => t.id === selectedTemplateId);
                    
                    let lampuSource = './gambar/lampu.webp';
                    if (template && template.lampu) lampuSource = template.lampu;

                    lampuImg.src = lampuSource;
                    await new Promise((res) => {
                        lampuImg.onload = () => {
                            const layout = (template && template.layout && template.layout.lampu) ? template.layout.lampu : { size: 300, x: -100, y: 140 };
                            const lSize = layout.size;
                            const x = padding + layout.x;
                            const y = yPos + imgHeight - lSize + layout.y;

                            ctx.drawImage(lampuImg, x, y, lSize, lSize);
                            res();
                        };
                        lampuImg.onerror = res;
                    });
                }

                // Add Rama on the THIRD photo (index 2) bottom right
                if (index === 2) {
                    const ramaImg = new Image();
                    const template = availableTemplates.find(t => t.id === selectedTemplateId);
                    
                    let ramaSource = './gambar/rama.png';
                    if (template && template.rama) ramaSource = template.rama;

                    ramaImg.src = ramaSource;
                    await new Promise((res) => {
                        ramaImg.onload = () => {
                            const layout = (template && template.layout && template.layout.rama) ? template.layout.rama : { size: 550, x: 150, y: 300 };
                            const rSize = layout.size;
                            const x = padding + imgWidth - rSize + layout.x;
                            const y = yPos + imgHeight - rSize + layout.y;

                            ctx.drawImage(ramaImg, x, y, rSize, rSize);
                            res();
                        };
                        ramaImg.onerror = res;
                    });
                }
                
                resolve();
            };
            img.src = dataUrl;
        });
    });

    await Promise.all(imagePromises);

    // 4. Draw Overlay Theme (if in overlay mode)
    if (overlayMode) {
        const bgImg = new Image();
        
        let bgSource = './gambar/background.png';
        const template = availableTemplates.find(t => t.id === selectedTemplateId);
        if (template && template.outer) bgSource = template.outer;

        bgImg.src = bgSource;
        await new Promise((resolve) => {
            bgImg.onload = () => {
                ctx.drawImage(bgImg, 0, 0, stripCanvas.width, stripCanvas.height);
                resolve();
            };
            bgImg.onerror = resolve;
        });
    }

    // 5. Draw Decorations (Logo, etc.)
    await drawDecorations(ctx, stripCanvas.width, stripCanvas.height);
    
    processingOverlay.classList.add('hidden');
    showPreview();
}

async function drawDecorations(ctx, width, height) {
    // Corner Decoration at Bottom Left
    const cornerImg = new Image();
    cornerImg.src = './gambar/pojokkiribawah.webp';
    await new Promise((resolve) => {
        cornerImg.onload = () => {
            const cornerWidth = 200;
            const cornerHeight = (cornerImg.height / cornerImg.width) * cornerWidth;
            ctx.drawImage(cornerImg, 20, height - cornerHeight - 20, cornerWidth, cornerHeight);
            resolve();
        };
        cornerImg.onerror = resolve;
    });
}

function showPreview() {
    // Force PNG quality and check format
    const dataUrl = canvas.toDataURL('image/png', 1.0);
    photoPreview.src = dataUrl;
    photoPreviewContainer.classList.remove('hidden');
    video.classList.add('hidden');
    photoCounter.classList.add('hidden');
    
    // Toggle Buttons
    captureBtn.classList.add('hidden');
    postCaptureControls.classList.remove('hidden');
}

// Retake Photo
retakeBtn.addEventListener('click', () => {
    capturedPhotos = [];
    photoPreviewContainer.classList.add('hidden');
    video.classList.remove('hidden');
    captureBtn.classList.remove('hidden');
    postCaptureControls.classList.add('hidden');
    photoCounter.classList.add('hidden');
});

// Download Photo
downloadBtn.addEventListener('click', () => {
    try {
        const dataUrl = canvas.toDataURL('image/png', 1.0);
        if (!dataUrl.startsWith('data:image/png')) {
            throw new Error('Invalid format generated');
        }
        const link = document.createElement('a');
        link.style.display = 'none';
        link.href = dataUrl;
        link.download = 'ramadhankarimmmahaghora1.png';
        document.body.appendChild(link);
        link.click();
        setTimeout(() => document.body.removeChild(link), 100);
    } catch (err) {
        console.error("Download failed:", err);
        canvas.toBlob((blob) => {
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'ramadhankarimmmahaghora1.png';
            link.click();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        }, 'image/png');
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
    capturedPhotos = [];
    photoPreviewContainer.classList.add('hidden');
    video.classList.remove('hidden');
    captureBtn.classList.remove('hidden');
    postCaptureControls.classList.add('hidden');
    photoCounter.classList.add('hidden');
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

// Save Photo to Server (uploads to session, then resets for next round)
saveBtn.addEventListener('click', async () => {
    try {
        const stripDataUrl = canvas.toDataURL('image/png', 1.0);
        const allImages = [...capturedPhotos, stripDataUrl];
        
        saveBtn.disabled = true;
        saveBtn.innerHTML = `
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengupload...
        `;

        const payload = { images: allImages };
        // If we already have a session, append to it
        if (currentSessionId) {
            payload.session_id = currentSessionId;
        }

        const response = await fetch('/upload.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            throw new Error(`Upload failed: ${response.statusText}`);
        }

        const data = await response.json();
        
        if (data.success) {
            // Store session info
            currentSessionId = data.session_id;
            currentViewUrl = data.view_url;
            saveCount++;

            // Update Selesai button badge
            saveCountBadge.innerText = saveCount;
            selesaiContainer.classList.remove('hidden');

            // Brief success feedback
            saveBtn.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Tersimpan! (Sesi ${saveCount})
            `;
            saveBtn.classList.remove('bg-ramadan-primary');
            saveBtn.classList.add('bg-ramadan-gold', 'text-ramadan-green');

            // After 1.5s, reset camera for next round
            setTimeout(() => {
                resetSaveButton();
                saveBtn.classList.add('bg-ramadan-primary');
                saveBtn.classList.remove('bg-ramadan-gold', 'text-ramadan-green');
                resetCameraForNextRound();
            }, 1500);

        } else {
            throw new Error(data.error || "Server error");
        }

    } catch (err) {
        console.error("Gagal mengupload foto:", err);
        alert("Gagal menyimpan ke server. Pastikan upload.php berfungsi dan PHP server aktif.");
        saveBtn.disabled = false;
        saveBtn.innerText = "Coba Lagi";
    }
});

// Selesai Button — show QR Code
selesaiBtn.addEventListener('click', () => {
    if (!currentViewUrl) return;
    
    const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(currentViewUrl)}`;
    qrImage.src = qrApiUrl;
    qrModal.classList.remove('hidden');
});

// QR Modal Handlers
closeQrBtn.addEventListener('click', () => {
    qrModal.classList.add('hidden');
});

// Finish Session — start fresh for next person
finishSessionBtn.addEventListener('click', () => {
    qrModal.classList.add('hidden');
    
    // Reset session state for the next person
    currentSessionId = null;
    currentViewUrl = null;
    saveCount = 0;
    saveCountBadge.innerText = '0';
    selesaiContainer.classList.add('hidden');
    
    // Reset camera
    resetCameraForNextRound();
    resetSaveButton();
});

// Start camera on load
window.addEventListener('load', initCamera);
