<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Templates</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #FFFDF5; }
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-playfair text-[#1B4332]">Menu Admin Template</h1>
            <a href="index.html" class="text-sm bg-gray-200 px-4 py-2 rounded-full hover:bg-gray-300 transition-all">Kembali ke Booth</a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left: Form -->
            <div class="lg:col-span-2 space-y-8">
                <section class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 text-[#2D6A4F]">Tambah Template Baru</h2>
                    <form id="upload-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Nama Template</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 rounded-xl border border-gray-200">
                            </div>
                            <div class="flex items-center gap-2 pt-6">
                                <input type="checkbox" name="overlayMode" id="form-overlay" class="w-5 h-5 accent-[#2D6A4F]">
                                <label for="form-overlay" class="text-sm">Gunakan Tema Luar sebagai Overlay</label>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Ketupat Settings -->
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <h3 class="font-bold text-sm text-[#2D6A4F] mb-3 flex items-center gap-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div> Ketupat (Optional)
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">File</label>
                                        <input type="file" name="ketupat" accept="image/*" class="w-full text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Ukuran (px)</label>
                                        <input type="number" name="ketupat_size" placeholder="350" value="350" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser X (px)</label>
                                        <input type="number" name="ketupat_x" placeholder="120" value="120" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser Y (px)</label>
                                        <input type="number" name="ketupat_y" placeholder="150" value="150" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Lampu Settings -->
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <h3 class="font-bold text-sm text-[#2D6A4F] mb-3 flex items-center gap-2">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div> Lampu (Optional)
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">File</label>
                                        <input type="file" name="lampu" accept="image/*" class="w-full text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Ukuran (px)</label>
                                        <input type="number" name="lampu_size" placeholder="300" value="300" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser X (px)</label>
                                        <input type="number" name="lampu_x" placeholder="-100" value="-100" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser Y (px)</label>
                                        <input type="number" name="lampu_y" placeholder="140" value="140" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                </div>
                            </div>

                            <!-- Rama Settings -->
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <h3 class="font-bold text-sm text-[#2D6A4F] mb-3 flex items-center gap-2">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div> Rama (Optional)
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">File</label>
                                        <input type="file" name="rama" accept="image/*" class="w-full text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Ukuran (px)</label>
                                        <input type="number" name="rama_size" placeholder="550" value="550" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser X (px)</label>
                                        <input type="number" name="rama_x" placeholder="150" value="150" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Geser Y (px)</label>
                                        <input type="number" name="rama_y" placeholder="300" value="300" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 text-sm deco-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t">
                            <label class="block text-sm font-semibold mb-2">Tema Luar Utama (1080x1920) <span class="text-red-500">*</span></label>
                            <input type="file" name="outerImage" id="outer-input" accept="image/*" required class="w-full text-sm">
                        </div>

                        <button type="submit" class="w-full px-8 py-4 bg-[#2D6A4F] text-white rounded-2xl font-bold hover:scale-[1.02] transition-all shadow-lg active:scale-95">
                            Upload Template & Save Layout
                        </button>
                    </form>
                </section>

                <!-- Template List -->
                <section>
                    <h2 class="text-xl font-bold mb-4 text-[#2D6A4F]">Daftar Template</h2>
                    <div id="template-list" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <p class="text-gray-500 italic">Memuat daftar template...</p>
                    </div>
                </section>
            </div>

            <!-- Right: Preview (Sticky) -->
            <div class="lg:sticky lg:top-8 space-y-4">
                <h2 class="text-xl font-bold text-[#2D6A4F] flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Live Preview Monitor
                </h2>
                <div class="relative bg-white rounded-[3rem] p-4 shadow-2xl border-8 border-gray-100 overflow-hidden aspect-[9/16] w-full max-w-[350px] mx-auto">
                    <canvas id="preview-canvas" width="1080" height="1920" class="w-full h-full object-contain rounded-[2rem] bg-[#FFFDF5]"></canvas>
                    <div class="absolute inset-0 pointer-events-none border-[12px] border-white/50 rounded-[2.5rem]"></div>
                </div>
                <div class="bg-[#1B4332] text-ramadan-cream p-4 rounded-2xl text-[10px] space-y-1 shadow-md">
                    <p class="font-bold border-b border-ramadan-cream/20 pb-1 mb-1">INFO LAYOUT (1080x1920)</p>
                    <p>• Angka adalah pixel (px)</p>
                    <p>• Geser X: (+) Kanan, (-) Kiri</p>
                    <p>• Geser Y: (+) Bawah, (-) Atas</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('upload-form');
        const list = document.getElementById('template-list');
        const canvas = document.getElementById('preview-canvas');
        const ctx = canvas.getContext('2d');

        // Preview State
        const previewImages = {
            outer: null,
            ketupat: null,
            lampu: null,
            rama: null,
            default_ketupat: new Image(),
            default_lampu: new Image(),
            default_rama: new Image()
        };

        // Load Defaults with redraw trigger
        const loadDefault = (key, src) => {
            previewImages[key] = new Image();
            previewImages[key].onload = updateLivePreview;
            previewImages[key].src = src;
        };
        
        loadDefault('default_ketupat', './gambar/ketupat.webp');
        loadDefault('default_lampu', './gambar/lampu.webp');
        loadDefault('default_rama', './gambar/rama.png');

        function updateLivePreview() {
            // SYNC with src/main.js logic
            const imgWidth = 920;
            const imgHeight = 450;
            const padding = 80;
            const headerHeight = 150;
            const footerHeight = 100;
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            // Default background color
            ctx.fillStyle = '#FFFDF5';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const isOverlay = form.overlayMode.checked;

            // 1. Draw Outer if NOT Overlay (Background Mode)
            if (!isOverlay && previewImages.outer && previewImages.outer.complete) {
                ctx.drawImage(previewImages.outer, 0, 0, canvas.width, canvas.height);
            }

            // 2. Draw Mock Photos
            ctx.fillStyle = '#E5E7EB';
            for(let i=0; i<3; i++) {
                const yPos = padding + headerHeight + (i * (imgHeight + padding));
                ctx.beginPath();
                if (ctx.roundRect) {
                    ctx.roundRect(padding, yPos, imgWidth, imgHeight, 20);
                } else {
                    ctx.rect(padding, yPos, imgWidth, imgHeight);
                }
                ctx.fill();
            }

            // 3. Draw Decorators
            const drawDeco = (type, index) => {
                const img = previewImages[type] || previewImages[`default_${type}`];
                if (!img || !img.complete) return;

                const size = parseInt(form[`${type}_size`].value) || 0;
                const xOff = parseInt(form[`${type}_x`].value) || 0;
                const yOff = parseInt(form[`${type}_y`].value) || 0;
                
                const yPos = padding + headerHeight + (index * (imgHeight + padding));
                
                let x, y;
                if (type === 'lampu') {
                    x = padding + xOff;
                } else {
                    x = padding + imgWidth - size + xOff;
                }
                y = yPos + imgHeight - size + yOff;

                ctx.drawImage(img, x, y, size, size);
            };

            drawDeco('ketupat', 0);
            drawDeco('lampu', 1);
            drawDeco('rama', 2);

            // 4. Draw Outer if IS Overlay
            if (isOverlay && previewImages.outer && previewImages.outer.complete) {
                ctx.drawImage(previewImages.outer, 0, 0, canvas.width, canvas.height);
            }
        }

        // Listen for all inputs
        document.querySelectorAll('.deco-input, #form-overlay').forEach(el => {
            el.addEventListener('input', updateLivePreview);
        });

        const handleFileInput = (inputName, previewKey) => {
            const el = form[inputName];
            el.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (re) => {
                        const img = new Image();
                        img.onload = updateLivePreview;
                        img.src = re.target.result;
                        previewImages[previewKey] = img;
                    };
                    reader.readAsDataURL(file);
                }
            });
        };

        handleFileInput('outerImage', 'outer');
        handleFileInput('ketupat', 'ketupat');
        handleFileInput('lampu', 'lampu');
        handleFileInput('rama', 'rama');

        // Fetch and Render
        async function fetchTemplates() {
            const res = await fetch('/manage_templates.php?action=list');
            const data = await res.json();
            renderTemplates(data);
        }

        function renderTemplates(templates) {
            if (templates.length === 0) {
                list.innerHTML = '<p class="text-gray-500 italic col-span-2">Belum ada template.</p>';
                return;
            }

            list.innerHTML = templates.map(t => `
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 p-4">
                    <div class="aspect-[16/9] bg-gray-50 rounded-xl mb-4 relative overflow-hidden flex items-center justify-center border">
                        <img src="${t.outer}" class="absolute inset-0 w-full h-full object-cover opacity-50">
                        <div class="relative z-10 text-center">
                            <span class="text-xs font-bold text-[#2D6A4F] uppercase">${t.name}</span>
                            <div class="mt-2 space-y-1">
                                <p class="text-[8px] ${t.overlayMode ? 'text-blue-500' : 'text-gray-400'} font-bold">
                                    ${t.overlayMode ? '✓ OVERLAY' : 'BACKGROUND'}
                                </p>
                            </div>
                        </div>
                    </div>
                    <button onclick="deleteTemplate('${t.id}')" class="w-full py-2 bg-red-50 text-red-600 rounded-xl font-bold text-xs hover:bg-red-100">
                        Hapus
                    </button>
                </div>
            `).join('');
        }

        form.onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            if (form.overlayMode.checked) formData.set('overlayMode', 'true');
            
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Menyimpan Konfigurasi...';

            try {
                const res = await fetch('/manage_templates.php?action=upload', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();
                if (result.success) {
                    alert('Template & Layout berhasil disimpan!');
                    location.reload(); // Quick reset
                } else {
                    alert('Gagal: ' + (result.error || 'Terjadi kesalahan'));
                }
            } catch (err) {
                alert('Connection error');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        };

        async function deleteTemplate(id) {
            if (!confirm('Yakin ingin menghapus template ini?')) return;
            try {
                const res = await fetch('/manage_templates.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                if ((await res.json()).success) fetchTemplates();
            } catch (err) {
                alert('Gagal menghapus');
            }
        }

        fetchTemplates();
        setTimeout(updateLivePreview, 500); // Initial draw
    </script>
</body>
</html>
