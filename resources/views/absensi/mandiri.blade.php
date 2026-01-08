<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Absensi Mandiri - Sigap PT MTM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Lexend', sans-serif;
            background: #f2f6fb;
            margin: 0;
        }

        /* ===== BACKGROUND KARAKTER ===== */
        .bg-main {
            min-height: 100vh;
            background:
                url("{{ asset('images/kiri.png') }}") left bottom no-repeat,
                url("{{ asset('images/kanan.png') }}") right bottom no-repeat,
                linear-gradient(135deg, #004080, #0066cc);
            background-size:
                130px auto,
                130px auto,
                cover;
            padding-bottom: 120px;
        }

        @media (min-width: 414px) {
            .bg-main {
                background-size:
                    150px auto,
                    150px auto,
                    cover;
            }
        }

        @media (min-width: 768px) {
            .bg-main {
                background-size:
                    260px auto,
                    260px auto,
                    cover;
            }
        }

        /* Tombol status */
        .status-btn {
            padding: .9rem;
            border-radius: 1.3rem;
            font-weight: 700;  
            background: #f1f5f9;
            text-align: center;
            transition: all .2s ease;
            cursor: pointer;
        }

        /* Animasi alasan */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide { animation: slideDown .25s ease-out; }
    </style>
</head>

<body>

<div class="bg-main flex flex-col items-center">

    <!-- HEADER -->
    <div class="text-center text-white pt-6 pb-6 px-2">
        <h1 class="text-2xl font-extrabold uppercase">
            SISTEM ABSENSI MANDIRI
        </h1>
        <p class="text-xs opacity-90 mt-1">
            Sigap PT MTM <br>
            PT MTM - Integrated System
        </p>
    </div>

    <!-- CARD -->
    <div class="bg-white w-full max-w-[330px] rounded-[2.5rem] shadow-2xl z-10">

        <!-- CARD HEADER -->
        <div class="p-6 text-center border-b">
            <div class="w-16 h-16 mx-auto rounded-full bg-blue-50 flex items-center justify-center mb-3">
                <i class="fa-solid fa-fingerprint text-blue-900 text-2xl"></i>
            </div>
            <h2 class="text-lg font-extrabold text-slate-800">
                Selamat Datang
            </h2>
            <p class="text-[11px] text-slate-400">
                Silakan masukkan data kehadiran
            </p>
        </div>

        <!-- FORM -->
        <div class="p-6">
            <form method="POST" action="/absen-mandiri" class="space-y-5">
                @csrf

                <!-- NPK -->
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        Nomor Induk Karyawan (NPK)
                    </label>
                    <input type="number" name="npk" required
                        placeholder="3608"
                        class="w-full mt-2 bg-slate-50 border-2 border-slate-200 p-3 rounded-xl text-base font-bold focus:outline-none focus:border-blue-500">
                </div>

                <!-- STATUS -->
                <div>
                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        Status Kehadiran
                    </label>

                    <div class="grid grid-cols-2 gap-3 mt-3">

                        <label>
                            <input type="radio" name="status" value="Hadir" checked class="peer hidden">
                            <div class="status-btn peer-checked:bg-blue-900 peer-checked:text-white">
                                ✔ HADIR
                            </div>
                        </label>

                        <label>
                            <input type="radio" name="status" value="Izin" class="peer hidden">
                            <div class="status-btn peer-checked:bg-orange-400 peer-checked:text-white">
                                IZIN
                            </div>
                        </label>

                        <label>
                            <input type="radio" name="status" value="Sakit" class="peer hidden">
                            <div class="status-btn peer-checked:bg-blue-700 peer-checked:text-white">
                                SAKIT
                            </div>
                        </label>

                        <label>
                            <input type="radio" name="status" value="Cuti" class="peer hidden">
                            <div class="status-btn peer-checked:bg-red-500 peer-checked:text-white">
                                CUTI
                            </div>
                        </label>

                    </div>
                </div>

                <!-- ALASAN (TIDAK DIHILANGKAN) -->
                <div id="reasonBox" class="hidden">
                    <textarea name="alasan" rows="2"
                        class="w-full bg-amber-50 border-2 border-amber-200 p-3 rounded-xl text-sm font-medium focus:outline-none"
                        placeholder="Masukkan alasan / keterangan..."></textarea>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-blue-900 text-white py-3 rounded-full font-bold shadow-lg hover:brightness-110 transition">
                    Kirim / Absen Mandiri
                </button>

            </form>
        </div>
    </div>

  

</div>

<!-- SCRIPT ALASAN -->
<script>
    const radios = document.querySelectorAll('input[name="status"]');
    const reasonBox = document.getElementById('reasonBox');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value !== 'Hadir' && radio.checked) {
                reasonBox.classList.remove('hidden');
                reasonBox.classList.add('animate-slide');
            } else if (radio.value === 'Hadir' && radio.checked) {
                reasonBox.classList.add('hidden');
            }
        });
    });
</script>

</body>
</html>
