<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Antrean Print & Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              ramadan: {
                primary: "#D4AF37",   
                secondary: "#63392E", 
                cream: "#FFFDF5",     
                gold: "#C9A227",      
                green: "#1B4332",     
                lightGreen: "#2D6A4F",
                dark: "#0F172A",
              },
            },
          },
        },
      }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        
        .tab-btn.active {
            background-color: #1B4332;
            color: #FFFDF5;
            box-shadow: 0 10px 25px -5px rgba(27, 67, 50, 0.4);
        }

        .tab-btn:not(.active) {
            background-color: #FFFFFF;
            color: #64748B;
            border: 1px solid #E2E8F0;
        }

        .tab-btn:not(.active):hover {
            background-color: #F1F5F9;
            color: #1E293B;
        }

        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.6; }
        }
        .animate-pulse-dot {
            animation: pulse-dot 1.5s infinite ease-in-out;
        }
    </style>
</head>
<body class="p-4 md:p-8 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Top Navbar -->
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold uppercase tracking-wider">
                        Ramadan Booth Master
                    </span>
                    <span class="text-xs text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sistem Aktif
                    </span>
                </div>
                <h1 class="text-3xl font-playfair font-bold text-[#1B4332]">Dashboard Operator</h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="index.html" class="flex items-center gap-2 text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-full font-semibold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Ke Kamera Booth</span>
                </a>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <!-- Tab: Print Queue -->
                <button onclick="switchTab('queue')" id="tab-btn-queue" class="tab-btn active px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Antrean Print Peserta</span>
                    <span id="queue-badge-count" class="px-2.5 py-0.5 bg-amber-400 text-slate-900 text-xs font-black rounded-full shadow-sm">0</span>
                </button>

                <!-- Tab: Template Manager -->
                <button onclick="switchTab('templates')" id="tab-btn-templates" class="tab-btn px-6 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Pengaturan Template</span>
                </button>
            </div>

            <!-- Queue Quick Filter & Sound Toggle (visible on queue tab) -->
            <div id="queue-controls" class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl cursor-pointer hover:bg-slate-50">
                    <input type="checkbox" id="sound-toggle" checked class="w-4 h-4 accent-emerald-600 rounded">
                    <span>🔔 Notifikasi Suara</span>
                </label>

                <select id="status-filter" onchange="fetchQueue()" class="text-xs font-semibold bg-white border border-slate-200 px-3 py-2 rounded-xl text-slate-700 outline-none focus:border-emerald-600">
                    <option value="all">Semua Status</option>
                    <option value="pending" selected>⏳ Menunggu Cetak (Pending)</option>
                    <option value="printing">🖨️ Sedang Dicetak</option>
                    <option value="completed">✅ Selesai</option>
                </select>

                <button onclick="clearCompletedQueue()" class="text-xs font-semibold bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 border border-slate-200 px-3 py-2 rounded-xl transition-colors">
                    Bersihkan Riwayat
                </button>
            </div>
        </div>

        <!-- ================= TAB 1: PRINT QUEUE ================= -->
        <section id="tab-content-queue" class="space-y-6">
            <!-- Live Status Banner -->
            <div class="bg-emerald-950 text-emerald-100 p-5 rounded-3xl shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-emerald-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-800/80 flex items-center justify-center text-xl shrink-0">
                        🖨️
                    </div>
                    <div>
                        <h2 class="font-bold text-base text-white">Live Monitor Antrean Cetak</h2>
                        <p class="text-xs text-emerald-300">Setiap kali peserta menekan tombol "Cetak" di HP mereka setelah scan QR, foto akan langsung muncul di sini.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <button onclick="fetchQueue()" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4 animate-spin-hover" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Refresh Antrean</span>
                    </button>
                </div>
            </div>

            <!-- Print Cards Grid -->
            <div id="queue-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Dynamically populated by JS -->
                <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-slate-200">
                    <p class="text-sm font-semibold">Memuat data antrean...</p>
                </div>
            </div>
        </section>

        <!-- ================= TAB 2: TEMPLATES MANAGER ================= -->
        <section id="tab-content-templates" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Form -->
                <div class="lg:col-span-2 space-y-8">
                    <section class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold mb-4 text-[#2D6A4F]">Tambah Template Baru</h2>
                        <form id="upload-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Nama Template</label>
                                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-xl border border-gray-200 outline-none focus:border-emerald-600">
                                </div>
                                <div class="flex items-center gap-2 pt-6">
                                    <input type="checkbox" name="overlayMode" id="form-overlay" class="w-5 h-5 accent-[#2D6A4F]">
                                    <label for="form-overlay" class="text-sm font-medium">Gunakan Tema Luar sebagai Overlay</label>
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

                            <button type="submit" class="w-full px-8 py-4 bg-[#2D6A4F] text-white rounded-2xl font-bold hover:scale-[1.01] transition-all shadow-lg active:scale-95">
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
        </section>
    </div>

    <!-- Hidden Print Iframe -->
    <iframe id="print-frame" style="display: none; position: fixed; right: 0; bottom: 0; width: 0; height: 0; border: 0;"></iframe>

    <script>
        // ================= TAB MANAGEMENT =================
        function switchTab(tab) {
            const btnQueue = document.getElementById('tab-btn-queue');
            const btnTemplates = document.getElementById('tab-btn-templates');
            const contentQueue = document.getElementById('tab-content-queue');
            const contentTemplates = document.getElementById('tab-content-templates');
            const queueControls = document.getElementById('queue-controls');

            if (tab === 'queue') {
                btnQueue.classList.add('active');
                btnTemplates.classList.remove('active');
                contentQueue.classList.remove('hidden');
                contentTemplates.classList.add('hidden');
                queueControls.classList.remove('hidden');
                fetchQueue();
            } else {
                btnTemplates.classList.add('active');
                btnQueue.classList.remove('active');
                contentTemplates.classList.remove('hidden');
                contentQueue.classList.add('hidden');
                queueControls.classList.add('hidden');
                fetchTemplates();
                setTimeout(updateLivePreview, 200);
            }
        }

        // ================= AUDIO NOTIFICATION =================
        function playChime() {
            if (!document.getElementById('sound-toggle').checked) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                osc.frequency.setValueAtTime(880, ctx.currentTime + 0.15); // A5
                
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
                
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                osc.start();
                osc.stop(ctx.currentTime + 0.8);
            } catch (e) {
                // Audio context may require user interaction first
            }
        }

        // ================= PRINT QUEUE LOGIC =================
        let lastQueueCount = 0;
        let lastQueueIds = new Set();

        async function fetchQueue() {
            const status = document.getElementById('status-filter').value;
            try {
                const res = await fetch(`print_action.php?action=get_queue&status=${status}`);
                const data = await res.json();
                
                if (data.success) {
                    renderQueue(data.queue || []);
                    const pendingCount = data.pending_count || 0;
                    document.getElementById('queue-badge-count').innerText = pendingCount;
                    
                    // Check for new items to ring chime
                    const currentPendingIds = new Set((data.queue || []).filter(q => q.status === 'pending').map(q => q.id));
                    let hasNew = false;
                    for (let id of currentPendingIds) {
                        if (!lastQueueIds.has(id)) {
                            hasNew = true;
                            break;
                        }
                    }
                    if (hasNew && lastQueueIds.size > 0) {
                        playChime();
                    }
                    lastQueueIds = currentPendingIds;
                }
            } catch (err) {
                console.error("Queue fetch error:", err);
            }
        }

        function renderQueue(items) {
            const list = document.getElementById('queue-list');
            
            if (items.length === 0) {
                list.innerHTML = `
                    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                        <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                            ☕
                        </div>
                        <h3 class="text-base font-bold text-slate-700">Belum Ada Antrean Cetak</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Saat peserta menekan tombol cetak di galeri HP mereka, daftar foto yang diminta cetak akan langsung muncul di sini.</p>
                    </div>
                `;
                return;
            }

            list.innerHTML = items.map(item => {
                const isPending = item.status === 'pending';
                const isPrinting = item.status === 'printing';
                const isCompleted = item.status === 'completed';

                let statusBadge = '';
                if (isPending) {
                    statusBadge = `<span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-[10px] font-extrabold uppercase flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse-dot"></span> Menunggu Cetak</span>`;
                } else if (isPrinting) {
                    statusBadge = `<span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full text-[10px] font-extrabold uppercase flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Sedang Dicetak</span>`;
                } else if (isCompleted) {
                    statusBadge = `<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-extrabold uppercase flex items-center gap-1">✓ Selesai</span>`;
                } else {
                    statusBadge = `<span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-extrabold uppercase">Dibatalkan</span>`;
                }

                return `
                    <div class="bg-white rounded-3xl border ${isPending ? 'border-amber-300 ring-2 ring-amber-100 shadow-md' : 'border-slate-200 shadow-sm'} p-4 flex flex-col justify-between transition-all hover:shadow-lg">
                        <div>
                            <!-- Header Info -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">ID: ${item.session_id.substring(0, 15)}...</span>
                                    <h4 class="text-sm font-bold text-slate-800">${item.label || 'Photo Strip'}</h4>
                                </div>
                                ${statusBadge}
                            </div>

                            <!-- Photo Preview -->
                            <div class="aspect-[9/16] bg-slate-900 rounded-2xl overflow-hidden mb-4 border border-slate-100 relative group flex items-center justify-center">
                                <img src="${item.photo_url}" class="w-full h-full object-contain cursor-pointer transition-transform group-hover:scale-105" onclick="window.open('${item.photo_url}', '_blank')">
                                <a href="${item.photo_url}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5 backdrop-blur-[1px]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat HD
                                </a>
                            </div>

                            <!-- Meta Info -->
                            <div class="text-[11px] text-slate-400 space-y-1 mb-4">
                                <p>⏱️ Request: <span class="text-slate-600 font-semibold">${item.created_at}</span></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-3 border-t border-slate-100">
                            <button onclick="printPhoto('${item.id}', '${item.photo_url}')" 
                                    class="w-full py-3 px-4 bg-[#1B4332] hover:bg-[#2D6A4F] text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span>🖨️ Cetak / Print Sekarang</span>
                            </button>

                            <div class="grid grid-cols-2 gap-2">
                                ${!isCompleted ? `
                                    <button onclick="updateQueueStatus('${item.id}', 'completed')" 
                                            class="py-2 px-3 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl font-bold text-[11px] transition-colors">
                                        ✓ Tandai Selesai
                                    </button>
                                ` : `
                                    <button onclick="updateQueueStatus('${item.id}', 'pending')" 
                                            class="py-2 px-3 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl font-bold text-[11px] transition-colors">
                                        ↩ Jadikan Pending
                                    </button>
                                `}
                                <button onclick="deleteQueueItem('${item.id}')" 
                                        class="py-2 px-3 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl font-bold text-[11px] transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Dedicated Clean Print Handler (Opens print dialog for just the image, borderless)
        function printPhoto(queueId, imageUrl) {
            // 1. Mark status as printing
            updateQueueStatus(queueId, 'printing');

            // 2. Open clean print window
            const printWindow = window.open('', '_blank', 'width=800,height=900');
            if (!printWindow) {
                alert('Pop-up terblokir oleh browser. Harap izinkan pop-up untuk mencetak otomatis.');
                return;
            }

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cetak Photo Booth</title>
                    <style>
                        @page {
                            size: auto;
                            margin: 0mm;
                        }
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-height: 100vh;
                            background: #fff;
                        }
                        img {
                            width: 100%;
                            height: 100vh;
                            object-fit: contain;
                            display: block;
                        }
                    </style>
                </head>
                <body>
                    <img src="${imageUrl}" onload="window.focus(); window.print(); setTimeout(() => window.close(), 1000);">
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        async function updateQueueStatus(id, status) {
            try {
                await fetch('print_action.php?action=update_status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, status })
                });
                fetchQueue();
            } catch (e) {
                console.error(e);
            }
        }

        async function deleteQueueItem(id) {
            if (!confirm('Hapus item ini dari antrean?')) return;
            try {
                await fetch('print_action.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                fetchQueue();
            } catch (e) {
                console.error(e);
            }
        }

        async function clearCompletedQueue() {
            if (!confirm('Bersihkan semua riwayat yang sudah selesai / dibatalkan?')) return;
            try {
                await fetch('print_action.php?action=clear_completed', {
                    method: 'POST'
                });
                fetchQueue();
            } catch (e) {
                console.error(e);
            }
        }

        // ================= TEMPLATE MANAGER LOGIC =================
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

        const loadDefault = (key, src) => {
            previewImages[key] = new Image();
            previewImages[key].onload = updateLivePreview;
            previewImages[key].src = src;
        };
        
        loadDefault('default_ketupat', './gambar/ketupat.webp');
        loadDefault('default_lampu', './gambar/lampu.webp');
        loadDefault('default_rama', './gambar/rama.png');

        function updateLivePreview() {
            const imgWidth = 920;
            const imgHeight = 450;
            const padding = 80;
            const headerHeight = 150;
            const footerHeight = 100;
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#FFFDF5';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            const isOverlay = form.overlayMode.checked;

            if (!isOverlay && previewImages.outer && previewImages.outer.complete) {
                ctx.drawImage(previewImages.outer, 0, 0, canvas.width, canvas.height);
            }

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

            if (isOverlay && previewImages.outer && previewImages.outer.complete) {
                ctx.drawImage(previewImages.outer, 0, 0, canvas.width, canvas.height);
            }
        }

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

        async function fetchTemplates() {
            try {
                const res = await fetch('manage_templates.php?action=list');
                const data = await res.json();
                renderTemplates(data);
            } catch (e) {
                console.error(e);
            }
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
                const res = await fetch('manage_templates.php?action=upload', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();
                if (result.success) {
                    alert('Template & Layout berhasil disimpan!');
                    fetchTemplates();
                    form.reset();
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
                const res = await fetch('manage_templates.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                if ((await res.json()).success) fetchTemplates();
            } catch (err) {
                alert('Gagal menghapus');
            }
        }

        // Init
        fetchQueue();
        setInterval(fetchQueue, 3000); // Polling every 3 seconds
    </script>
</body>
</html>
