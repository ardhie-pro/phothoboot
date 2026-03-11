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

// Theme Customization State
let customOuterTheme = localStorage.getItem('customOuterTheme') || null;
let customInnerTheme = localStorage.getItem('customInnerTheme') || null;
let overlayMode = localStorage.getItem('overlayMode') === 'true';

const settingsToggle = document.getElementById('settings-toggle');
const settingsModal = document.getElementById('settings-modal');
const closeSettings = document.getElementById('close-settings');
const outerThemeInput = document.getElementById('outer-theme-input');
const innerThemeInput = document.getElementById('inner-theme-input');
const overlayModeToggle = document.getElementById('overlay-mode-toggle');
const resetThemeBtn = document.getElementById('reset-theme');
const outerPreview = document.getElementById('outer-preview');
const innerPreview = document.getElementById('inner-preview');

// Initial Toggle State
overlayModeToggle.checked = overlayMode;

// Settings Handlers
overlayModeToggle.onchange = (e) => {
    overlayMode = e.target.checked;
    localStorage.setItem('overlayMode', overlayMode);
};

// Helper to Update Previews
function updateThemePreviews() {
    if (customOuterTheme) {
        outerPreview.innerHTML = `<img src="${customOuterTheme}" class="h-full w-full object-cover">`;
    } else {
        outerPreview.innerHTML = `<span class="text-xs text-ramadan-secondary/50">Belum ada file</span>`;
    }
    if (customInnerTheme) {
        innerPreview.innerHTML = `<img src="${customInnerTheme}" class="h-full w-full object-cover">`;
    } else {
        innerPreview.innerHTML = `<span class="text-xs text-ramadan-secondary/50">Belum ada file</span>`;
    }
}

// Initial Previews
updateThemePreviews();

// Settings Handlers
settingsToggle.addEventListener('click', () => settingsModal.classList.remove('hidden'));
closeSettings.addEventListener('click', () => settingsModal.classList.add('hidden'));
settingsModal.addEventListener('click', (e) => e.target === settingsModal && settingsModal.classList.add('hidden'));

outerThemeInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (re) => {
            customOuterTheme = re.target.result;
            localStorage.setItem('customOuterTheme', customOuterTheme);
            updateThemePreviews();
            alert('Tema Luar berhasil diperbarui!');
        };
        reader.readAsDataURL(file);
    }
});

innerThemeInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (re) => {
            customInnerTheme = re.target.result;
            localStorage.setItem('customInnerTheme', customInnerTheme);
            updateThemePreviews();
            alert('Tema Dalam berhasil diperbarui!');
        };
        reader.readAsDataURL(file);
    }
});

resetThemeBtn.addEventListener('click', () => {
    if (confirm('Reset tema ke bawaan?')) {
        customOuterTheme = null;
        customInnerTheme = null;
        localStorage.removeItem('customOuterTheme');
        localStorage.removeItem('customInnerTheme');
        updateThemePreviews();
        alert('Tema telah direset.');
    }
});

// Initialize Camera
async function initCamera() {
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
        alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin.");
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

    // Target aspect ratio based on imgWidth (800) and imgHeight (500)
    const targetAspect = 800 / 500;
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
    tempCanvas.width = 800; // Expected width
    tempCanvas.height = 500; // Expected height
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
    
    // Standard dimensions for the strip
    const imgWidth = 800;
    const imgHeight = 500;
    const padding = 60;
    const headerHeight = 180; 
    const footerHeight = 100;
    
    stripCanvas.width = imgWidth + (padding * 2);
    stripCanvas.height = (imgHeight * 3) + (padding * 4) + headerHeight + footerHeight;
    
    // 1. Draw Background (only if NOT in overlay mode)
    if (!overlayMode) {
        const bgImg = new Image();
        bgImg.src = customOuterTheme || './gambar/background.png';
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
                frameImg.src = customInnerTheme || `./gambar/atassebagaibingkai.png`;
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
                    ketupatImg.src = './gambar/ketupat.webp';
                    await new Promise((res) => {
                        ketupatImg.onload = () => {
                            const kSize = 350; // Besar kecil ketupat
                            const geserKanan = 120; // Ubah ini: makin besar (+) makin ke kanan, makin kecil (-) makin ke kiri
                            const geserBawah = 150; // Ubah ini: makin besar (+) makin ke bawah, makin kecil (-) makin ke atas (naik)
                            
                            const x = padding + imgWidth - kSize + geserKanan;
                            const y = yPos + imgHeight - kSize + geserBawah;

                            ctx.drawImage(ketupatImg, x, y, kSize, kSize);
                            res();
                        };
                        ketupatImg.onerror = res;
                    });
                }

                // Add Lampu on the SECOND photo (index 1) bottom left
                if (index === 1) {
                    const lampuImg = new Image();
                    lampuImg.src = './gambar/lampu.webp';
                    await new Promise((res) => {
                        lampuImg.onload = () => {
                            const lSize = 300; // Besar kecil lampu
                            const geserKanan = -100; // Ubah ini: makin besar (+) makin ke kanan, makin kecil (-) makin ke kiri
                            const geserBawah = 140;  // Ubah ini: makin besar (+) makin ke bawah, makin kecil (-) makin ke atas (naik)
                            
                            const x = padding + geserKanan;
                            const y = yPos + imgHeight - lSize + geserBawah;

                            ctx.drawImage(lampuImg, x, y, lSize, lSize);
                            res();
                        };
                        lampuImg.onerror = res;
                    });
                }

                // Add Rama on the THIRD photo (index 2) bottom right
                if (index === 2) {
                    const ramaImg = new Image();
                    ramaImg.src = './gambar/rama.png';
                    await new Promise((res) => {
                        ramaImg.onload = () => {
                            const rSize = 550; // Besar kecil rama
                            const geserKanan = 150; // Ubah ini: makin besar (+) makin ke kanan, makin kecil (-) makin ke kiri
                            const geserBawah = 300; // Ubah ini: makin besar (+) makin ke bawah, makin kecil (-) makin ke atas (naik)
                            
                            const x = padding + imgWidth - rSize + geserKanan;
                            const y = yPos + imgHeight - rSize + geserBawah;

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
        bgImg.src = customOuterTheme || './gambar/background.png';
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
