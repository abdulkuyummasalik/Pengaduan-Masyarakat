@extends('layouts.app')

@section('content')
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="relative z-10 container mx-auto px-4 py-12">

        {{-- Success Alert --}}
        @if (Session::get('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 border border-green-300 rounded-lg flex items-center" role="alert">
                <i class="ri-checkbox-circle-line text-2xl mr-3"></i>
                <div>
                    <span class="font-medium">Berhasil!</span> {{ Session::get('success') }}
                </div>
            </div>
        @endif

        {{-- Error Alert --}}
        @if (Session::get('failed'))
            <div class="p-4 mb-6 text-red-800 bg-red-100 border border-red-300 rounded-lg flex items-center" role="alert">
                <i class="ri-error-warning-line text-2xl mr-3"></i>
                <div>
                    <span class="font-medium">Kesalahan!</span> {{ Session::get('failed') }}
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-6xl font-black text-orange-500 mb-4">
                Artikel SiPengaduan
            </h1>
            <p class="text-gray-300 max-w-2xl mx-auto">
                Temukan informasi terkini, analisis mendalam, dan berbagai perspektif tentang pengaduan masyarakat
            </p>
        </div>

        {{-- Filter Section --}}
        <div class="mb-12 max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <select id="provinceSelect"
                    class="bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-green-500 flex-grow">
                    <option value="all">Semua Provinsi</option>
                </select>
                <button id="searchButton"
                    class="px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
                    <i class="ri-search-line mr-2"></i>Cari
                </button>
                <button id="resetButton"
                    class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                    <i class="ri-refresh-line mr-2"></i>Reset
                </button>
            </div>
        </div>

        {{-- Add Article Button --}}
        <div class="mt-8 mb-8 flex justify-center">
            <a href="{{ route('report.article.create') }}"
                class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition shadow-lg hover:shadow-xl flex items-center font-semibold">
                <i class="ri-add-circle-line mr-2 text-xl"></i>Tambah Artikel
            </a>
        </div>

        {{-- Articles Grid --}}
        @if($reports->isEmpty())
            <div class="text-center py-20">
                <i class="ri-article-line text-8xl text-gray-600 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-400 mb-2">Belum Ada Artikel</h3>
                <p class="text-gray-500 mb-6">Jadilah yang pertama membuat artikel pengaduan!</p>
                <a href="{{ route('report.article.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                    <i class="ri-add-line mr-2"></i>Buat Artikel Pertama
                </a>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($reports as $report)
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 group">

                        {{-- Image Section --}}
                        <div class="relative h-48 bg-gradient-to-br from-gray-700 to-gray-900 overflow-hidden">
                            @if($report->has_image)
                                <img src="{{ $report->image_url }}"
                                     alt="Artikel: {{ Str::limit($report->description, 30) }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-700 to-gray-800\'><div class=\'text-center\'><i class=\'ri-image-line text-6xl text-gray-500 mb-2\'></i><p class=\'text-gray-400 text-sm\'>Gambar Tidak Tersedia</p></div></div>';">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="ri-image-line text-7xl text-gray-500 mb-3 opacity-50"></i>
                                        <p class="text-gray-400 text-sm font-medium">Tidak Ada Gambar</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Category Badge --}}
                            <div class="absolute top-4 right-4
                                {{ $report->type == 'SOSIAL' ? 'bg-orange-500' : '' }}
                                {{ $report->type == 'KEJAHATAN' ? 'bg-red-500' : '' }}
                                {{ $report->type == 'PEMBANGUNAN' ? 'bg-green-500' : '' }}
                                text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg backdrop-blur-sm">
                                {{ $report->type }}
                            </div>

                            {{-- Overlay on Hover --}}
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        </div>

                        {{-- Content Section --}}
                        <div class="p-6">
                            <a href="{{ route('report.article.show', $report->id) }}" class="block group-article">
                                <h3 class="text-xl font-bold text-green-500 mb-3 group-article-hover:text-green-400 transition line-clamp-2 leading-tight">
                                    {{ Str::limit($report->description, 70) }}
                                </h3>
                            </a>

                            {{-- Author Info --}}
                            <div class="flex items-center text-gray-400 text-sm mb-4 mt-4 pb-4 border-b border-white/10">
                                @if($report->user && $report->user->avatar && Storage::disk('public')->exists('avatars/' . $report->user->avatar))
                                    <img src="{{ asset('storage/avatars/' . $report->user->avatar) }}"
                                         alt="{{ $report->user->name }}"
                                         class="w-9 h-9 rounded-full mr-3 object-cover border-2 border-green-500/50"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-9 h-9 rounded-full mr-3 bg-gradient-to-br from-green-500 to-green-700 hidden items-center justify-center text-white font-bold text-sm">
                                        {{ substr($report->user->name, 0, 1) }}
                                    </div>
                                @else
                                    <div class="w-9 h-9 rounded-full mr-3 bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white font-bold text-sm border-2 border-green-500/50">
                                        {{ substr($report->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-medium text-white truncate">{{ $report->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            {{-- Stats & Vote --}}
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center hover:text-orange-400 transition cursor-help" title="Total Viewers">
                                        <i class="ri-eye-line mr-1.5 text-orange-500 text-lg"></i>
                                        <span class="text-sm font-semibold">{{ number_format($report->viewers) }}</span>
                                    </div>
                                    <div class="flex items-center hover:text-green-400 transition cursor-help" title="Total Votes">
                                        <i class="ri-heart-line mr-1.5 text-green-500 text-lg"></i>
                                        <span class="text-sm font-semibold">{{ $report->vote_count }}</span>
                                    </div>
                                </div>

                                {{-- Vote Button --}}
                                <form action="{{ route('report.article.vote', $report->id) }}" method="POST" class="inline">
                                    @csrf
                                    @php
                                        $hasVoted = $report->hasVoted(Auth::id());
                                    @endphp
                                    <button type="submit"
                                        class="flex items-center text-sm {{ $hasVoted ? 'text-green-400' : 'text-gray-400' }} hover:text-green-300 transition font-semibold px-3 py-1.5 rounded-lg {{ $hasVoted ? 'bg-green-500/20' : 'bg-white/5' }} hover:bg-green-500/30"
                                        title="{{ $hasVoted ? 'Batalkan Vote' : 'Vote Artikel' }}">
                                        <i class="ri-thumb-up-{{ $hasVoted ? 'fill' : 'line' }} mr-1.5 text-base"></i>
                                        {{ $hasVoted ? 'Voted' : 'Vote' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('style')
        <style>
            .bg-pattern {
                background-image:
                    linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                background-size: 20px 20px;
            }

            select, option {
                background-color: rgba(31, 41, 55, 0.95);
                color: white;
            }

            select:focus, option:focus {
                background-color: #1f2937;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .group-article:hover .group-article-hover\:text-green-400 {
                color: #4ade80;
            }
        </style>
    @endpush

    @push('script')
        <script>
            const apiURL = "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json";
            const provinceSelect = document.getElementById("provinceSelect");
            const searchButton = document.getElementById("searchButton");
            const resetButton = document.getElementById("resetButton");

            // Fetch provinces
            async function fetchProvinces() {
                try {
                    provinceSelect.innerHTML = '<option value="all">Memuat...</option>';
                    const response = await fetch(apiURL);

                    if (!response.ok) throw new Error("Gagal mengambil data provinsi");

                    const provinces = await response.json();
                    provinceSelect.innerHTML = '<option value="all">Semua Provinsi</option>';

                    provinces.forEach(province => {
                        const option = document.createElement("option");
                        option.value = province.name;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });

                    // Set selected province from URL if exists
                    const urlParams = new URLSearchParams(window.location.search);
                    const selectedProvince = urlParams.get('province');
                    if (selectedProvince) {
                        provinceSelect.value = selectedProvince;
                    }
                } catch (error) {
                    console.error("Terjadi kesalahan:", error);
                    provinceSelect.innerHTML = '<option value="all">Gagal memuat data</option>';
                }
            }

            // Search button handler
            searchButton.addEventListener("click", () => {
                const selectedProvince = provinceSelect.value;
                if (selectedProvince !== "all") {
                    window.location.href = `{{ route('report.article.index') }}?province=${encodeURIComponent(selectedProvince)}`;
                } else {
                    alert('Silakan pilih provinsi terlebih dahulu');
                }
            });

            // Reset button handler
            resetButton.addEventListener("click", () => {
                window.location.href = `{{ route('report.article.index') }}`;
            });

            // Initialize on page load
            document.addEventListener("DOMContentLoaded", fetchProvinces);

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        </script>
    @endpush
@endsection
