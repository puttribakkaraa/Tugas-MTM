<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Berhasil - Sigap PT MTM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Lexend', sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            height: 100vh;
            width: 100vw;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* CONTAINER GAMBAR: Diperkecil skalanya agar tidak terlalu naik ke tengah */
        .char-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 42vh; /* Dikurangi dari 55vh agar tidak menabrak card */
            z-index: 5; 
            pointer-events: none;
            display: flex;
            justify-content:空间-between;
        }

        .char-bg {
            position: absolute;
            bottom: -5px;
            height: 100%;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.3));
            transition: all 0.5s ease;
        }

        /* Posisi gambar lebih ke pinggir agar card di tengah terlihat jelas */
        .img-kiri { left: -10px; }
        .img-kanan { right: -10px; }

        @media (min-width: 905px) {
            .char-container { height: 65vh; }
            .img-kiri { left: 5%; }
            .img-kanan { right: 5%; }
        }

        .pop-in {
            animation: pop 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.1) forwards;
        }

        @keyframes pop {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); } 
        }
    </style>
</head>
<body class="px-6 pt-6"> <div class="pop-in bg-white/95 backdrop-blur-md rounded-[2rem] px-4 py-5
                text-center shadow-[0_15px_40px_rgba(0,0,0,0.4)]
                max-w-[260px] w-full relative z-20
                border-b-[5px] border-emerald-500 mt-2">

        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
        </div>

        <h1 class="text-md font-black text-blue-950 leading-tight tracking-tight">
            ABSENSI SUKSES!
        </h1>

        <div class="mt-4 space-y-2">
            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Karyawan</p>
                <p class="text-xs font-extrabold text-blue-900 truncate">
                    {{ session('nama') ?? 'User MTM' }}
                </p>
            </div>

            <div class="flex gap-2">
                <div class="flex-1 bg-slate-50 p-2 rounded-xl border border-slate-100">
                    <p class="text-[7px] font-bold text-slate-400 uppercase">Status</p>
                    <p class="text-[9px] font-black text-emerald-600">
                        {{ session('status') ?? 'HADIR' }}
                    </p>
                </div>
                <div class="flex-1 bg-slate-50 p-2 rounded-xl border border-slate-100">
                    <p class="text-[7px] font-bold text-slate-400 uppercase">Jam</p>
                    <p class="text-[9px] font-black text-slate-700">
                        {{ session('jam') ?? now()->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <a href="/absen-mandiri"
           class="flex items-center justify-center mt-5 bg-blue-900 
                  text-white w-full py-3 rounded-xl
                  text-[10px] font-bold shadow-lg uppercase tracking-widest active:scale-95 transition-all">
            Selesai
        </a>
    </div>

    <div class="char-container">
        <img src="{{ asset('images/suksess1.png') }}" class="char-bg img-kiri" alt="Sukses 1">
        <img src="{{ asset('images/suksess2.png') }}" class="char-bg img-kanan" alt="Sukses 2">
    </div>

    <div class="fixed bottom-2 left-0 right-0 text-center z-30 opacity-50">
        <p class="text-[6px] font-bold text-white tracking-[0.3em]">
            PT MTM • DIGITAL SYSTEM
        </p>
    </div>

</body>
</html>