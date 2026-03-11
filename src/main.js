const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureBtn = document.getElementById('capture-btn');
const retakeBtn = document.getElementById('retake-btn');
const downloadBtn = document.getElementById('download-btn');
const photoPreview = document.getElementById('photo-preview');
const photoPreviewContainer = document.getElementById('photo-preview-container');
const postCaptureControls = document.getElementById('post-capture-controls');
const flash = document.getElementById('flash');
const photoCounter = document.getElementById('photo-counter');
const countdownOverlay = document.getElementById('countdown-overlay');
const countdownNumber = document.getElementById('countdown-number');
const processingOverlay = document.getElementById('processing-overlay');

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

    // Capture Frame
    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = video.videoWidth;
    tempCanvas.height = video.videoHeight;
    const tempCtx = tempCanvas.getContext('2d');
    tempCtx.drawImage(video, 0, 0);
    
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
    const imgHeight = 600;
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
        // Use toDataURL for smaller files or as a primary method for compatibility
        const dataUrl = canvas.toDataURL('image/png', 1.0);
        
        // Final check if dataUrl is valid
        if (!dataUrl.startsWith('data:image/png')) {
            throw new Error('Invalid format generated');
        }

        const link = document.createElement('a');
        link.style.display = 'none';
        link.href = dataUrl;
        link.download = 'ramadhankarimmmahaghora1.png';
        
        document.body.appendChild(link);
        link.click();
        
        // Clean up
        setTimeout(() => {
            document.body.removeChild(link);
        }, 100);
    } catch (err) {
        console.error("Download failed:", err);
        // Fallback to Blob if DataURL fails (rare)
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

// Start camera on load
window.addEventListener('load', initCamera);
