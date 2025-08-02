@extends('layouts.app')
@section('content')
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="relative z-10 container mx-auto px-4 py-12 space-y-8">
        @if (Session::get('success'))
            <div class="p-4 mb-4 text-green-800 bg-green-100 border border-green-300 rounded-lg" role="alert">
                <span class="font-medium">Berhasil!,</span> {{ Session::get('success') }}.
            </div>
        @endif

        @if (Session::get('failed'))
            <div class="p-4 mb-4 text-red-800 bg-red-100 border border-red-300 rounded-lg" role="alert">
                <span class="font-medium">Kesalahan!</span> {{ Session::get('failed') }}
            </div>
        @endif
        @forelse ($reports as $report)
            <div id="report{{ $report->id }}"
                class="w-full max-w-4xl rounded-lg shadow-lg p-4 mx-auto border
                {{ match ($report->responses->first()?->response_status) {
                    'DONE' => 'text-green-500 bg-green-900/20 border-green-500',
                    'ON_PROCESS' => 'bg-yellow-900/20 border-yellow-500',
                    'REJECT' => 'bg-red-900/20 border-red-500',
                    default => 'bg-gray-900/20 border-gray-500',
                } }}">
                <button onclick="toggleDetails('details{{ $report->id }}')"
                    class="w-full text-left font-bold text-lg focus:outline-none hover:text-orange-300
                       {{ match ($report->progres_status) {
                           'DONE' => 'text-green-400 border-green-500 hover:text-green-300',
                           'ON_PROCESS' => 'text-yellow-400 border-yellow-500 hover:text-yellow-300',
                           'REJECT' => 'text-red-400 border-red-500 hover:text-red-300',
                           default => 'text-gray-400 border-gray-500 hover:text-gray-300',
                       } }}">
                    Pengaduan {{ $report->created_at->translatedFormat('j F Y') }}
                </button>

                <div id="details{{ $report->id }}" class="details mt-4">
                    <div class="flex border-b border-gray-300">
                        @foreach (['Data', 'Gambar', 'Status'] as $index => $tab)
                            <button
                                onclick="switchTab('tab-{{ strtolower($tab) }}-{{ $report->id }}', 'details{{ $report->id }}')"
                                class="flex-1 text-center py-2 font-bold text-gray-300 hover:bg-white/5 focus:outline-none">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <div id="tab-data-{{ $report->id }}" class="tab-content active">
                            <h3 class="text-xl font-bold text-orange-500 mb-4">Detail Pengaduan</h3>
                            <p class="text-gray-300">
                                <strong>Tipe:</strong> <span class="text-green-400">{{ $report->type }}</span><br>
                                <strong>Lokasi:</strong> {{ $report->village }}, {{ $report->subdistrict }},
                                {{ $report->regency }}, {{ $report->province }}<br>
                                <strong>Deskripsi:</strong> {{ $report->description }}
                            </p>
                        </div>
                        <div id="tab-gambar-{{ $report->id }}" class="tab-content">
                            <h3 class="text-xl font-bold text-orange-500 mb-4">Gambar Pengaduan</h3>
                            <img src="{{ asset('storage/images/' . $report->image) }}" alt="Gambar"
                                class="w-full h-64 rounded-lg border border-white/20">
                        </div>
                        <div id="tab-status-{{ $report->id }}" class="tab-content">
                            <h3 class="text-xl font-bold text-orange-500 mb-4">Status Pengaduan</h3>
                            <p class="text-gray-300">
                                Pengaduan
                                {{ $report->responses->isNotEmpty() ? 'telah ditanggapi' : 'sedang dalam proses' }},
                                dengan status:
                                <span
                                    class="{{ match ($report->responses->first()?->response_status) {
                                        'DONE' => 'bg-green-500 rounded-sm text-white p-2',
                                        'ON_PROCESS' => 'bg-yellow-500 rounded-sm text-white p-2',
                                        'REJECT' => 'bg-red-500 rounded-sm text-white p-2',
                                        default => 'bg-gray-500 rounded-sm text-white p-2',
                                    } }}">
                                    {{ $report->responses->first()?->response_status ?? 'PENDING' }}
                                </span>
                                <br>
                                @if ($report->responses->first()?->response_content)
                                    <strong>Progres:</strong>
                                    @foreach (json_decode($report->responses->first()->response_content, true) as $progres)
                                        <li>{{ $progres }}</li>
                                    @endforeach
                                @else
                                    <span class="text-gray-400">Belum ada respon yang tersedia.</span>
                                @endif
                            </p>

                            @if ($report->responses->isEmpty())
                                <p class="text-gray-300">Apakah ingin menghapus?</p>
                                <form action="{{ route('report.article.delete', $report->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                        Hapus Pengaduan
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-300">Tidak ada pengaduan yang ditemukan.</p>
        @endforelse
    </div>
    @push('style')
        <style>
            .bg-pattern {
                background-image:
                    linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                background-size: 20px 20px;
            }

            .details {
                display: none;
            }

            .details.active {
                display: block;
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            img {
                object-fit: cover;
            }
        </style>
    @endpush
    @push('script')
        <script>
            function toggleDetails(containerId) {
                const details = document.getElementById(containerId);
                details.classList.toggle("active");
            }

            function switchTab(tabId, containerId) {
                const container = document.getElementById(containerId);
                const tabs = container.querySelectorAll(".tab-content");
                tabs.forEach(tab => tab.classList.remove("active"));

                const activeTab = document.getElementById(tabId);
                activeTab.classList.add("active");
            }
        </script>
    @endpush
@endsection
