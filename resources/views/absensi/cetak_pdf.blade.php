<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi</title>
    <style>
        @page { margin: 0.7cm; }
        body { font-family: sans-serif; font-size: 8px; color: #333; }
        
        /* Header Astra Style */
        .header-table { width: 100%; border-bottom: 2px solid #1e3a8a; margin-bottom: 15px; }
        .company-name { font-size: 16px; font-weight: bold; color: #1e3a8a; }
        .report-title { font-size: 10px; color: #4b5563; text-transform: uppercase; }

        /* Table Design */
        table { width: 100%; border-collapse: collapse; table-layout: fixed; border: 1px solid #000; }
        th { background-color: #f1f5f9; border: 1px solid #000; padding: 5px 1px; color: #1e3a8a; }
        td { border: 1px solid #000; padding: 3px 1px; text-align: center; vertical-align: middle; }
        
        .text-left { text-align: left; padding-left: 5px; font-weight: bold; }
        .bg-weekend { background-color: #ffe4e6; } /* Warna merah muda untuk Sabtu/Minggu */
        
        /* Status Text Colors */
        .txt-hadir { color: #059669; font-weight: bold; }
        .txt-sakit { color: #2563eb; font-weight: bold; }
        .txt-izin { color: #d97706; font-weight: bold; }
        .txt-cuti { color: #0891b2; font-weight: bold; }
        .txt-alpha { color: #dc2626; font-weight: bold; }
        .jam { font-size: 6px; color: #64748b; display: block; }

        /* Baris Rekap */
        .rekap-row { background-color: #f8fafc; font-weight: bold; }
        
        /* Legend Section */
        .footer-table { width: 100%; margin-top: 20px; border: none; }
        .footer-table td { border: none; text-align: left; vertical-align: top; padding: 0; }
    </style>
</head>
<body>
    <table class="header-table" style="border:none;">
        <tr style="border:none;">
            <td style="border:none; text-align:left;">
                <div class="company-name">SIGAP MANAGEMENT</div>
                <div class="report-title">Monitoring Kehadiran Karyawan</div>
                <div style="font-size: 9px;">Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}</div>
            </td>
           
        </tr>
    </table>

    @php
        $period = \Carbon\CarbonPeriod::create($start, $end);
        $rekapTotal = [];
        foreach($period as $date) {
            $rekapTotal[$date->format('Y-m-d')] = ['H' => 0, 'A' => 0];
        }
    @endphp

    <table>
        <thead>
            <tr>
                <th style="width: 20px;">NO</th>
                <th style="width: 35px;">NPK</th>
                <th style="width: 100px;">NAMA KARYAWAN</th>
                <th style="width: 55px;">DEPT</th>
                @foreach($period as $date)
                    <th class="{{ $date->isWeekend() ? 'bg-weekend' : '' }}">
                        <span style="{{ $date->isWeekend() ? 'color: #be123c;' : '' }}">{{ $date->format('d') }}</span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $index => $emp)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $emp->npk }}</td>
                <td class="text-left">{{ strtoupper($emp->name) }}</td>
                <td style="font-size: 7px;">{{ $emp->department }}</td>
                @foreach($period as $date)
                    @php
                        $attendance = $emp->attendances->where('date', $date->format('Y-m-d'))->first();
                        $isWeekend = $date->isWeekend();
                        if($attendance && $attendance->status == 'Hadir') {
                            $rekapTotal[$date->format('Y-m-d')]['H']++;
                        } elseif(!$isWeekend && !$attendance && $date->lte(now())) {
                            $rekapTotal[$date->format('Y-m-d')]['A']++;
                        }
                    @endphp
                    <td class="{{ $isWeekend ? 'bg-weekend' : '' }}">
                        @if($attendance)
                            @if($attendance->status == 'Hadir')
                                <b class="txt-hadir">V</b>
                                <span class="jam">{{ \Carbon\Carbon::parse($attendance->created_at)->timezone('Asia/Jakarta')->format('H:i') }}</span>
                            @elseif($attendance->status == 'Sakit') <b class="txt-sakit">S</b>
                            @elseif($attendance->status == 'Izin') <b class="txt-izin">I</b>
                            @elseif($attendance->status == 'Cuti') <b class="txt-cuti">C</b>
                            @endif
                        @else
                            @if(!$isWeekend && $date->lte(now()))
                                <b class="txt-alpha">X</b>
                            @endif
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach

            <tr class="rekap-row">
                <td colspan="4" style="text-align:right; padding-right:10px;">TOTAL HADIR</td>
                @foreach($period as $date)
                    <td class="{{ $date->isWeekend() ? 'bg-weekend' : '' }}" style="color: #059669;">
                        {{ $rekapTotal[$date->format('Y-m-d')]['H'] ?: '' }}
                    </td>
                @endforeach
            </tr>
            <tr class="rekap-row">
                <td colspan="4" style="text-align:right; padding-right:10px;">TOTAL TIDAK HADIR (X)</td>
                @foreach($period as $date)
                    <td class="{{ $date->isWeekend() ? 'bg-weekend' : '' }}" style="color: #dc2626;">
                        {{ $rekapTotal[$date->format('Y-m-d')]['A'] ?: '' }}
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                <strong>Keterangan Status:</strong><br>
                <span class="txt-hadir">V</span> = Hadir (H) &nbsp;&nbsp;
                <span class="txt-sakit">S</span> = Sakit (S) &nbsp;&nbsp;
                <span class="txt-izin">I</span> = Izin (I) <br>
                <span class="txt-cuti">C</span> = Cuti (C) &nbsp;&nbsp;
                <span class="txt-alpha">X</span> = Alpha (A) &nbsp;&nbsp;
                <span style="background-color: #ffe4e6; border: 1px solid #ccc;">&nbsp;&nbsp;&nbsp;</span> = Hari Libur
            </td>
            <td style="width: 50%; text-align: right; color: #9ca3af;">
                Dicetak pada: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB<br>
               
            </td>
        </tr>
    </table>
</body>
</html>