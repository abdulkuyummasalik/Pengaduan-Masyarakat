<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ResponseController extends Controller
{
    public function index()
    {
        $reports = Report::orderBy('voting', 'desc')->get();
        return view('report.response.index', compact('reports'));
    }

    public function response(Request $request, $id)
    {
        $report = Report::find($id);
        return view('report.response.show', compact('report'));
    }

    public function store(Request $request, $id)
    {
        // Temukan report berdasarkan ID
        $report = Report::findOrFail($id);

        // Cari response yang sudah ada untuk report ini
        $response = Response::where('report_id', $report->id)->first();

        if ($response) {
            // Jika response sudah ada, perbarui hanya statusnya
            $response->update([
                'response_status' => 'DONE',
            ]);
        } else {
            // Jika response belum ada, buat baru dengan status default atau dari input
            Response::create([
                'staff_id' => Auth::user()->id,
                'report_id' => $report->id,
                'response_status' => 'DONE',
            ]);
        }

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('response.index')->with('success', 'Status berhasil disimpan atau diperbarui.');
    }


    // public function store(Request $request, $id)
    // {
    //     $report = Report::find($id);
    //     $response = Response::create([
    //         'staff_id' => Auth::user()->id,
    //         'report_id' => $report->id,
    //         'response_status' => 'DONE',
    //     ]);

    //     return redirect()->route('response.index')->with('success', 'Berhasil ditambahkan');
    // }

    public function progresStore($id, Request $request)
    {
        $report = Report::find($id);

        $request->validate([
            'response_content' => 'required',
        ]);

        $response = Response::where('report_id', $report->id)->first();

        if (!$response) {
            // Jika respons belum ada, buat baru
            $response = Response::create([
                'staff_id' => Auth::user()->id,
                'report_id' => $report->id,
                'response_status' => 'ON_PROCESS',
                'response_content' => json_encode([$request->response_content]), // Simpan sebagai array JSON
            ]);
        } else {
            // Jika respons sudah ada, tambahkan ke array
            $currentContent = json_decode($response->response_content, true);
            $currentContent[] = $request->response_content;
            $response->update([
                'response_content' => json_encode($currentContent),
            ]);
        }

        return redirect()->route('response.index')->with('success', 'Berhasil ditambahkan');
    }

    public function destroyContent($id, $index)
    {
        $response = Response::find($id);

        if (!$response) {
            return redirect()->back()->with('error', 'Respon tidak ditemukan.');
        }

        // Decode JSON ke array
        $responseContent = json_decode($response->response_content, true);

        // Validasi indeks
        if (!isset($responseContent[$index])) {
            return redirect()->back()->with('error', 'Progres tidak ditemukan.');
        }

        // Hapus progres pada indeks tertentu
        unset($responseContent[$index]);

        // Reset array key dan encode kembali ke JSON
        $response->response_content = json_encode(array_values($responseContent));
        $response->save();

        return redirect()->back()->with('success', 'Progres berhasil dihapus.');
    }



    public function progres($id)
    {
        $report = Report::find($id);
        $response = Response::where('report_id', $report->id)->first();
        return view('report.response.progres', compact('report', 'response'));
    }

    public function exportExcel()
    {
        $file_name = 'data_pengaduan_semua.xlsx';
        return Excel::download(new ReportExport(), $file_name);
    }

    public function exportByDate(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if (!$start_date || !$end_date) {
            return redirect()->back()->with('failed', 'Harap pilih tanggal mulai dan tanggal selesai!');
        }

        $file_name = "data_pengaduan_{$start_date}_to_{$end_date}.xlsx";
        return Excel::download(new ReportExport(null, $start_date, $end_date), $file_name);
    }

    public function reject($id)
    {
        $report = Report::findOrFail($id);

        $response = Response::firstOrCreate(
            ['report_id' => $id],
            [
                'staff_id' => auth()->id(),
                'response_status' => 'ON_PROCESS'
            ]
        );

        $response->update([
            'response_status' => 'REJECT',
            'staff_id' => auth()->id()
        ]);
        return redirect()->route('response.index')->with('success', 'Laporan berhasil ditolak');
    }
}
