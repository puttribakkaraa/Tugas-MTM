<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Method untuk menampilkan halaman filter laporan
    public function report()
    {
        return view('absensi.report');
    }

   public function index(Request $request)
{
    $startOfMonth = now()->startOfMonth();
    $endOfMonth = now()->endOfMonth();
    $today = now()->toDateString();
    
    // Ambil jumlah per halaman dari dropdown, default 10
    $perPage = $request->input('per_page', 10);
    
    $dateRange = \Carbon\CarbonPeriod::create($startOfMonth, $endOfMonth);

    $query = Employee::with(['attendances' => function($q) use ($startOfMonth, $endOfMonth) {
        $q->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
    }]);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('npk', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%");
        });
    }

    // Filter tidak hadir jika dipilih
    if ($request->status_filter == 'tidak_hadir') {
        $query->whereDoesntHave('attendances', function($q) use ($today) {
            $q->where('date', $today);
        });
        $dateRange = \Carbon\CarbonPeriod::create(now(), now());
    }

    // --- LOGIKA HITUNG STATS (Dihitung dari semua data untuk akurasi dashboard) ---
    $allEmployees = Employee::with(['attendances' => function($q) use ($today) {
        $q->where('date', $today);
    }])->get();

    $stats = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'cuti' => 0, 'alpa' => 0];
    foreach ($allEmployees as $emp) {
        $atd = $emp->attendances->first();
        if ($atd) {
            $status = strtolower($atd->status);
            if (isset($stats[$status])) $stats[$status]++;
        } else {
            $stats['alpa']++;
        }
    }

    // Ambil data dengan Pagination
    $employees = $query->paginate($perPage)->withQueryString();

    return view('absensi.grid', compact('employees', 'dateRange', 'startOfMonth', 'stats', 'perPage'));
}
    

    public function export(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $format = $request->format;

        if (!$start || !$end) {
            return back()->with('error', 'Pilih rentang tanggal terlebih dahulu!');
        }

        // Ambil data karyawan beserta absensinya untuk dikirim ke Excel/PDF
        $employees = Employee::with(['attendances' => function($query) use ($start, $end) {
            $query->whereBetween('date', [$start, $end]);
        }])->get();

        // JIKA PILIH EXCEL
  // Cek baris 74 di AttendanceController.php
if ($format == 'excel') {
    $fileName = 'Absensi_Sigap.xlsx'; 
    
    // PASTIKAN ada 3 variabel di dalam kurung:
    return Excel::download(new AbsensiExport($employees, $start, $end), $fileName);
}

        // JIKA PILIH PDF
        $pdf = Pdf::loadView('absensi.cetak_pdf', [
            'employees' => $employees,
            'start' => $start,
            'end' => $end
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Absensi_Sigap.pdf');
    }

   public function store(Request $request)
{
    $request->validate([
        'npk' => 'required|unique:employees,npk',
        'name' => 'required',
        'department' => 'required',
        'title' => 'required',
    ]);

    Employee::create($request->all());

    // UBAH DARI 'view' MENJADI 'redirect'
    // Arahkan kembali ke halaman grid/tabel karyawan
    return redirect()->route('absensi.index')->with('success', 'Karyawan baru berhasil ditambahkan!');
}

 public function AbsenMandiri(Request $request)
{
    $request->validate([
        'npk' => 'required',
        'status' => 'required|in:Hadir,Izin,Sakit,Cuti',
        'alasan' => 'nullable|string'
    ]);

    // Set zona waktu Indonesia
    date_default_timezone_set('Asia/Jakarta');
    $now = now();
    $startTime = '08:15';
    $endTime   = '09:30';
    $currentTime = $now->format('H:i');

    // CEK WAKTU (Berlaku untuk semua status)
    if ($currentTime < $startTime || $currentTime > $endTime) {
        // Mengirim pesan error ke session
        return redirect()->back()->with('error', "Waktu absensi berakhir! Batas jam absen adalah pukul $startTime s/d $endTime WIB. Saat ini pukul $currentTime WIB.");
    }

    $employee = Employee::where('npk', $request->npk)->first();
    if (!$employee) {
        return redirect()->back()->with('error', 'NPK tidak ditemukan! Silakan hubungi Admin.');
    }

    $today = $now->toDateString();
    if (Attendance::where('employee_id', $employee->id)->where('date', $today)->exists()) {
        return redirect()->back()->with('error', 'Anda sudah melakukan absensi hari ini.');
    }

    Attendance::create([
        'employee_id' => $employee->id,
        'date' => $today,
        'time_in' => $now->toTimeString(),
        'status' => $request->status,
        'reason' => ($request->status === 'Hadir') ? null : $request->alasan,
    ]);

    return redirect()->route('absensi.sukses')->with([
        'nama' => $employee->name,
        'status' => $request->status,
        'jam' => $now->format('H:i')
    ]);
}
    public function dashboard()
    {
        $totalKaryawan = Employee::count();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $dateRange = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $labels = [];
        $dataPersentase = [];

        foreach ($dateRange as $date) {
            $tanggal = $date->format('Y-m-d');
            $labels[] = $date->format('d');
            $jumlahInput = Attendance::where('date', $tanggal)->count();
            $persentase = $totalKaryawan > 0 ? ($jumlahInput / $totalKaryawan) * 100 : 0;
            $dataPersentase[] = round($persentase, 1);
        }

        $hadirHariIni = Attendance::where('date', now()->toDateString())->count();
        $tidakHadir = $totalKaryawan - $hadirHariIni;
        $persentaseHadir = $totalKaryawan > 0 ? ($hadirHariIni / $totalKaryawan) * 100 : 0;

        return view('absensi.dashboard', compact('totalKaryawan', 'hadirHariIni', 'tidakHadir', 'persentaseHadir', 'labels', 'dataPersentase'));
    }

    public function scan(Request $request)
    {
        $employee = Employee::where('npk', $request->npk)->first();
        if ($employee) {
            Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => now()->toDateString()],
               ['time_in' => now()->toTimeString(), 'status' => 'Hadir']);
            return response()->json(['status' => 'success', 'message' => 'Absen Berhasil!']);
        }
        return response()->json(['status' => 'error', 'message' => 'NPK tidak ditemukan'], 404);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required',
            'title' => 'required',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update([
            'name' => $request->name,
            'department' => $request->department,
            'title' => $request->title,
        ]);

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus data karyawan (Hapus)
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Opsional: Hapus juga data absensinya agar database bersih
        $employee->attendances()->delete();
        
        $employee->delete();

        return redirect()->back()->with('success', 'Karyawan dan data absensi berhasil dihapus.');
    }
}
