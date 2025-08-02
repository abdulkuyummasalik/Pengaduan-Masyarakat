<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class ReportController extends Controller
{

    public function index(Request $request)
    {
        $query = Report::query();

        if ($request->has('province') && $request->province !== 'all') {
            $query->where('province', $request->province);
        }

        $reports = $query->get();

        return view('report.article.index', compact('reports'));
    }

    public function me()
    {
        $reports = Report::where('user_id', auth()->id())
            ->with(['responses', 'user'])
            ->get();
        return view('report.article.me', compact('reports'));
    }

    public function create()
    {
        $provinceUrl = env('APP_PROVINCE_URL', 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $province = Http::get($provinceUrl);
        $dataProvince = $province->json();
        return view('report.article.create', compact('dataProvince'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'province' => 'required|string',
            'regency' => 'required|string',
            'subdistrict' => 'required|string',
            'village' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'statement' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
        }

        $report = Report::create([
            'user_id' => Auth::user()->id,
            'type' => $request->input('type'),
            'province' => $request->input('province'),
            'regency' => $request->input('regency'),
            'subdistrict' => $request->input('subdistrict'),
            'village' => $request->input('village'),
            'description' => $request->input('description'),
            'voting' => json_encode([]), // Default kosong
            'image' => $imagePath ? basename($imagePath) : null,
            'statement' => $request->has('statement'),
        ]);

        return redirect()->route('report.article.index')->with('success', 'Artikel berhasil dibuat!');
    }

    public function vote(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $userId = Auth::id();
        $voting = json_decode($report->voting, true) ?: [];

        if (in_array($userId, $voting)) {
            $voting = array_filter($voting, function ($value) use ($userId) {
                return $value !== $userId;
            });

            $message = 'Vote berhasil dibatalkan';
        } else {
            $voting[] = $userId;
            $message = 'Vote berhasil diberikan';
        }

        $report->voting = json_encode($voting);
        $report->save();

        return redirect()->back()->with('success', $message);
    }


    public function show(Report $report, $id)
    {
        $report = $report->findOrFail($id);
        $report->increment('viewers', 1);
        return view('report.article.show', compact('report'));
    }


    public function dashboard()
    {
        $reports = Report::all()->count();
        $responses = Response::all()->count();

        return view('report.dashboard.index', compact('reports', 'responses'));
    }

    public function delete($id)
    {
        $report = Report::find($id);
        if ($report) {
            $report->delete();
            return redirect()->route('report.article.me')->with('success', 'Artikel berhasil dihapus!');
        }
        return redirect()->route('report.article.me')->with('failed', 'Artikel gagal dihapus!');
    }
}
