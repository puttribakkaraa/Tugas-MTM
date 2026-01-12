<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Absensi Mandiri - Sigap PT MTM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;700;800&display=swap');
    
    body { 
        font-family: 'Lexend', sans-serif; 
        background: #004080; 
        margin: 0; 
        overflow-x: hidden;
    }

    .bg-main {
        min-height: 100vh;
        /* Menggunakan background-position agar gambar menempel ke pojok */
        background:
            url("{{ asset('images/kiri.png') }}") left bottom no-repeat,
            url("{{ asset('images/kanan.png') }}") right bottom no-repeat,
            linear-gradient(135deg, #004080, #0066cc);
        
        /* UKURAN GAMBAR DIPERBESAR (Mobile: 160px, Desktop: 320px) */
        background-size: 160px auto, 160px auto, cover;
        background-attachment: fixed;
    }

    /* Untuk layar HP yang lebih tinggi/lebar agar gambar tidak terpotong card */
    @media (max-width: 480px) {
        .bg-main {
            background-size: 155px auto, 155px auto, cover;
            /* Memberikan ruang di bawah agar gambar terlihat utuh */
            padding-bottom: 20px; 
        }
    }

    /* Menyesuaikan ukuran gambar untuk layar Tablet/Laptop */
    @media (min-width: 768px) { 
        .bg-main { 
            background-size: 320px auto, 320px auto, cover; 
        } 
    }

    .status-btn { 
        padding: 0.6rem;
        border-radius: 1rem; 
        font-weight: 700; 
        font-size: 0.75rem;
        background: #f1f5f9; 
        text-align: center; 
        transition: all .2s ease; 
        cursor: pointer; 
    }

    @keyframes slideDown { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide { animation: slideDown .25s ease-out; }
</style>
</head>

<body>
<div class="bg-main flex flex-col items-center justify-start sm:justify-center p-4">
    
    <div class="text-center text-white mb-4 mt-2">
        <h1 class="text-xl font-extrabold uppercase tracking-tight">SISTEM ABSENSI</h1>
        <p class="text-[10px] opacity-80 uppercase">PT MTM - Integrated System</p>
    </div>

    <div class="bg-white w-full max-w-[290px] rounded-[2rem] shadow-2xl z-10 overflow-hidden">
        
        <div class="p-4 text-center border-b">
            <div class="w-12 h-12 mx-auto rounded-full bg-blue-50 flex items-center justify-center mb-2">
                <i class="fa-solid fa-fingerprint text-blue-900 text-xl"></i>
            </div>
            <h2 class="text-md font-extrabold text-slate-800">Absensi Mandiri</h2>
            <p class="text-[10px] text-slate-400">Silakan isi data kehadiran</p>
        </div>

        <div class="p-5">
            <form method="POST" action="{{ url('/absen-mandiri') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">NPK Karyawan</label>
                    <input type="number" name="npk" required placeholder="Contoh: 3608"
                        class="w-full mt-1 bg-slate-50 border-2 border-slate-100 p-2.5 rounded-xl text-sm font-bold focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-3">
                    <label class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</label>
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <label><input type="radio" name="status" value="Hadir" class="peer hidden" required><div class="status-btn peer-checked:bg-blue-900 peer-checked:text-white">HADIR</div></label>
                        <label><input type="radio" name="status" value="Izin" class="peer hidden"><div class="status-btn peer-checked:bg-orange-400 peer-checked:text-white">IZIN</div></label>
                        <label><input type="radio" name="status" value="Sakit" class="peer hidden"><div class="status-btn peer-checked:bg-blue-700 peer-checked:text-white">SAKIT</div></label>
                        <label><input type="radio" name="status" value="Cuti" class="peer hidden"><div class="status-btn peer-checked:bg-red-500 peer-checked:text-white">CUTI</div></label>
                    </div>
                </div>

                <div id="reasonBox" class="hidden mb-3">
                    <textarea name="alasan" rows="2" class="w-full bg-amber-50 border-2 border-amber-100 p-2 rounded-xl text-xs font-medium focus:outline-none"
                        placeholder="Keterangan..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-full text-sm font-bold shadow-lg hover:scale-[1.02] active:scale-95 transition-all mt-1">
                    KIRIM ABSENSI
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Logic Alasan
    const radios = document.querySelectorAll('input[name="status"]');
    const reasonBox = document.getElementById('reasonBox');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value !== 'Hadir' && radio.checked) {
                reasonBox.classList.remove('hidden');
                reasonBox.classList.add('animate-slide');
            } else {
                reasonBox.classList.add('hidden');
            }
        });
    });

    // SweetAlert Error/Success
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{!! session('error') !!}", confirmButtonColor: '#1e3a8a' });
    @endif

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{!! session('success') !!}", confirmButtonColor: '#1e3a8a' });
    @endif
</script>
</body>
</html>