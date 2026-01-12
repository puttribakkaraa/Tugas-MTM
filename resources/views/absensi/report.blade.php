<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi - Sigap Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f0f7ff; /* Soft Blue sesuai referensi */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 850px;
        }

        /* Header Style */
        .header-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .header-icon {
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e5eef7;
        }

        .header-icon img {
            width: 45px;
            height: 45px;
            display: block;
        }

        .header-text h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 4px;
        }

        .header-text p {
            font-size: 14px;
            color: #64748b;
        }

        /* Card Laporan */
        .report-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0, 58, 122, 0.05);
            border: 1px solid #e2e8f0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 35px;
        }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-group label {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-group label i {
            color: #ef4444; /* Ikon merah sesuai referensi */
        }

        .input-group input {
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 15px;
            color: #1e293b;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-group input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        /* Action Buttons */
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            border-top: 1px solid #f1f5f9;
            padding-top: 25px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
            color: white;
        }

        .btn:active {
            transform: scale(0.96);
        }

        .btn-excel {
            background-color: #007bff;
        }

        .btn-excel:hover {
            background-color: #0062cc;
        }

        .btn-pdf {
            background-color: #007bff; /* Disamakan biru sesuai referensi */
        }

        .btn-pdf:hover {
            background-color: #0062cc;
        }

        .btn-back {
            background-color: #5a6268;
        }

        .btn-back:hover {
            background-color: #4e555b;
        }

        .footer-note {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <div class="header-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/3502/3502688.png" alt="Icon Laporan">
        </div>
        
        <div class="header-text">
            <h1>Laporan Absensi Karyawan</h1>
            <p>Pantau kehadiran dan performa karyawan dari waktu ke waktu</p>
        </div>
    </div>

    <div class="report-card">
        <form action="{{ route('absensi.export') }}" method="GET">
            <div class="form-grid">
                <div class="input-group">
                    <label><i class="fa-solid fa-calendar-day"></i> Dari Tanggal</label>
                    <input type="date" name="start_date" required>
                </div>

                <div class="input-group">
                    <label><i class="fa-solid fa-calendar-check"></i> Sampai Tanggal</label>
                    <input type="date" name="end_date" required>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" name="format" value="excel" class="btn btn-excel">
                    <i class="fa-solid fa-magnifying-glass"></i> Download Excel
                </button>

                <button type="submit" name="format" value="pdf" class="btn btn-pdf">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF
                </button>

                <a href="{{ url('/dashboard') }}" class="btn btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </form>
    </div>

    <div class="footer-note">
        Sigap Management
    </div>
</div>

</body>
</html>