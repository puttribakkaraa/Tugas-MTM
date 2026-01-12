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
        }

        /* PRIORITAS GAMBAR: Tinggi dinaikkan agar karakter terlihat sampai badan */
        .char-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 55vh; 
            z-index: 10; /* Z-index lebih tinggi agar kaki karakter di depan card jika perlu */
            pointer-events: none;
        }

        .char-bg {
            position: absolute;
            bottom: -10px;
            height: 100%;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4));
            transition: all 0.5s ease;
        }

        .img-kiri { left: -20px; transform: rotate(2deg); }
        .img-kanan { right: -20px; transform: rotate(-2deg); }

        @media (min-width: 905px) {
            .char-container { height: 75vh; }
            .img-kiri { left: 5%; }
            .img-kanan { right: 5%; }
        }

        .pop-in {
            animation: pop 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.1) forwards;
        }

        @keyframes pop {
            0% { opacity: 0; transform: translateY(-50px) scale(0.9); }
            100% { opacity: 1; transform: translateY(0px) scale(1); } 
        }
    </style>
</head>
<body class="min-h-screen flex items-start justify-center px-6 pt-10">

    <div class="char-container">
        <img src="{{ asset('images/suksess1.png') }}" class="char-bg img-kiri" alt="Sukses 1">
        <img src="{{ asset('images/suksess2.png') }}" class="char-bg img-kanan" alt="Sukses 2">
    </div>

    <div class="pop-in bg-white/90 backdrop-blur-lg rounded-[2.5rem] px-5 py-6
                text-center shadow-[0_20px_50px_rgba(0,0,0,0.3)]
                max-w-[280px] w-full relative z-20
                border-b-[6px] border-emerald-500 mt-2">

        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-emerald-500 text-3xl"></i>
        </div>

        <h1 class="text-lg font-black text-blue-950 leading-tight">
            ABSENSI SUKSES!
        </h1>

        <div class="mt-5 space-y-3">
            <div class="bg-white/50 p-3 rounded-xl border border-slate-100">
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Karyawan</p>
                <p class="text-sm font-extrabold text-blue-900 truncate">
                    {{ session('nama') ?? 'User MTM' }}
                </p>
            </div>

            <div class="flex gap-2">
                <div class="flex-1 bg-white/50 p-2 rounded-xl border border-slate-100">
                    <p class="text-[8px] font-bold text-slate-400 uppercase">Status</p>
                    <p class="text-[10px] font-black text-emerald-600">
                        {{ session('status') ?? 'HADIR' }}
                    </p>
                </div>
                <div class="flex-1 bg-white/50 p-2 rounded-xl border border-slate-100">
                    <p class="text-[8px] font-bold text-slate-400 uppercase">Jam</p>
                    <p class="text-[10px] font-black text-slate-700">
                        {{ session('jam') ?? now()->format('H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <a href="/absen-mandiri"
           class="flex items-center justify-center mt-6 bg-blue-900 
                  text-white w-full py-3.5 rounded-xl
                  text-xs font-bold shadow-lg uppercase tracking-widest active:scale-95 transition-all">
            Selesai
        </a>
    </div>

    <div class="fixed bottom-3 left-0 right-0 text-center z-30 opacity-60">
        <p class="text-[7px] font-bold text-white tracking-[0.4em]">
            PT MTM • DIGITAL SYSTEM
        </p>
    </div>

</body>
</html>