<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user')->orderBy('created_at', 'desc');

        if ($request->has('province') && $request->province !== 'all') {
            $query->where('province', $request->province);
        }

        $reports = $query->get();

        return view('report.article.index', compact('reports'));
    }

    public function me()
    {
        $reports = Report::where('user_id', Auth::id())
            ->with(['responses', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('report.article.me', compact('reports'));
    }

    public function create()
    {
        $provinceUrl = env('APP_PROVINCE_URL', 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');

        try {
            $province = Http::timeout(10)->get($provinceUrl);
            $dataProvince = $province->successful() ? $province->json() : [];
        } catch (\Exception $e) {
            Log::error('Failed to fetch provinces: ' . $e->getMessage());
            $dataProvince = [];
        }

        return view('report.article.create', compact('dataProvince'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:KEJAHATAN,PEMBANGUNAN,SOSIAL',
            'province' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'village' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'statement' => 'required|accepted',
        ], [
            'type.required' => 'Kategori harus dipilih',
            'province.required' => 'Provinsi harus dipilih',
            'regency.required' => 'Kabupaten harus dipilih',
            'subdistrict.required' => 'Kecamatan harus dipilih',
            'village.required' => 'Desa harus dipilih',
            'description.required' => 'Konten artikel harus diisi',
            'description.min' => 'Konten artikel minimal 20 karakter',
            'image.required' => 'Gambar harus diunggah',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, atau svg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
            'statement.required' => 'Anda harus menyetujui pernyataan kebenaran',
            'statement.accepted' => 'Anda harus menyetujui pernyataan kebenaran',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Generate nama file unik
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Simpan ke storage/app/public/images (TANPA prefix 'public/')
            $image->storeAs('images', $imageName, 'public');

            Log::info('Image uploaded successfully', [
                'filename' => $imageName,
                'path' => 'storage/app/public/images/' . $imageName
            ]);
        }

        try {
            $report = Report::create([
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'province' => $validated['province'],
                'regency' => $validated['regency'],
                'subdistrict' => $validated['subdistrict'],
                'village' => $validated['village'],
                'description' => $validated['description'],
                'voting' => [],
                'image' => $imageName,
                'statement' => true,
                'viewers' => 0,
            ]);

            return redirect()->route('report.article.index')
                ->with('success', 'Artikel berhasil dibuat!');
        } catch (\Exception $e) {
            // Jika gagal simpan ke database, hapus gambar yang sudah diupload
            if ($imageName && Storage::disk('public')->exists('images/' . $imageName)) {
                Storage::disk('public')->delete('images/' . $imageName);
            }

            Log::error('Failed to create report: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('failed', 'Gagal membuat artikel. Silakan coba lagi.');
        }
    }

    public function vote(Request $request, $id)
    {
        try {
            $report = Report::findOrFail($id);
            $userId = Auth::id();

            $voting = is_array($report->voting) ? $report->voting : [];

            if (in_array($userId, $voting)) {
                // Remove vote
                $voting = array_values(array_filter($voting, function ($value) use ($userId) {
                    return $value !== $userId;
                }));
                $message = 'Vote berhasil dibatalkan';
            } else {
                // Add vote
                $voting[] = $userId;
                $message = 'Vote berhasil diberikan';
            }

            $report->voting = $voting;
            $report->save();

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Vote failed: ' . $e->getMessage());
            return redirect()->back()->with('failed', 'Gagal memproses vote');
        }
    }

    public function show($id)
    {
        try {
            $report = Report::with(['user', 'comments.user', 'responses'])
                ->findOrFail($id);

            // Increment viewers
            $report->increment('viewers');

            return view('report.article.show', compact('report'));
        } catch (\Exception $e) {
            Log::error('Failed to show report: ' . $e->getMessage());
            return redirect()->route('report.article.index')
                ->with('failed', 'Artikel tidak ditemukan');
        }
    }

    public function dashboard()
    {
        $reports = Report::count();
        $responses = Response::count();
        $totalViewers = Report::sum('viewers');
        $totalVotes = Report::get()->sum(function ($report) {
            return count(is_array($report->voting) ? $report->voting : []);
        });

        return view('report.dashboard.index', compact('reports', 'responses', 'totalViewers', 'totalVotes'));
    }

    public function delete($id)
    {
        try {
            $report = Report::findOrFail($id);

            // Check authorization
            if ($report->user_id !== Auth::id()) {
                return redirect()->route('report.article.me')
                    ->with('failed', 'Anda tidak memiliki akses untuk menghapus artikel ini');
            }

            // Hapus gambar dari storage jika ada
            if ($report->image && Storage::disk('public')->exists('images/' . $report->image)) {
                Storage::disk('public')->delete('images/' . $report->image);
                Log::info('Image deleted: ' . $report->image);
            }

            $report->delete();

            return redirect()->route('report.article.me')
                ->with('success', 'Artikel berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Failed to delete report: ' . $e->getMessage());
            return redirect()->route('report.article.me')
                ->with('failed', 'Artikel gagal dihapus!');
        }
    }
}
