@extends('layouts.app')

@section('content')
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="relative z-10 container mx-auto px-4 py-12">

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

        <div class="mb-12 text-center">
            <h1 class="text-4xl md:text-6xl font-black text-orange-500 mb-4">Artikel SiPengaduan</h1>
            <p class="text-gray-300 max-w-2xl mx-auto">
                Temukan informasi terkini, analisis mendalam, dan berbagai perspektif tentang pengaduan masyarakat
            </p>
        </div>

        <div class="mb-12 max-w-4xl mx-auto">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <select id="provinceSelect"
                    class="bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-green-500 flex-grow">
                    <option value="all">Semua Provinsi</option>
                </select>
                <button id="searchButton"
                    class="px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    Cari
                </button>
                <button id="resetButton"
                    class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Reset
                </button>
            </div>
        </div>


        <div class="mt-8 mb-8 flex justify-center">
            <a href="{{ route('report.article.create') }}"
                class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                Tambah Artikel
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($reports as $report)
                <div
                    class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl overflow-hidden hover:shadow-2xl transition-all">
                    <div class="relative">
                        <?php
                        // dd($report->image);
                        ?>
                        <img src="{{ asset('storage/images/' . $report->image) }}" alt="Artikel Thumbnail"
                            class="w-full h-48 object-cover">
                        <div
                            class="absolute top-4 right-4 bg-{{ $report->type == 'SOSIAL' ? 'orange' : 'green' }}-500 text-black px-3 py-1 rounded-full text-sm font-bold">
                            {{ $report->type }}
                        </div>
                    </div>
                    <div class="p-6">
                        <a href="{{ route('report.article.show', $report->id) }}">
                            <h3 class="text-xl font-bold text-green-500 mb-2 hover:underline">
                                {{ implode(' ', array_slice(explode(' ', $report->description), 0, 3)) }}...
                            </h3>
                        </a>
                        <div class="flex items-center text-gray-400 text-sm mb-4">
                            <img src="{{ asset('storage/images/' . $report->image) }}" alt="Penulis"
                                class="w-8 h-8 rounded-full mr-3">
                            <span>{{ $report->user->name }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-300">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <i class="ri-eye-line mr-2 text-orange-500"></i>
                                    <span>{{ $report->viewers }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-heart-line mr-2 text-green-500"></i>
                                    <span>Vote ({{ count(json_decode($report->voting, true)) }})</span>
                                </div>
                            </div>
                            <form action="{{ route('report.article.vote', $report->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center">
                                    <i class="ri-thumb-up-line text-green-500"></i> Vote
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('style')
        <style>
            .bg-pattern {
                background-image:
                    linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                background-size: 20px 20px;
            }

            /* Warna pada opsi dropdown */
            select,
            option {
                background-color: rgba(255, 255, 255, 0.1);
                /* Warna latar */
                color: white;
                /* Warna teks */
            }

            select:focus,
            option:focus {
                background-color: #2c2c2c;
                /* Warna latar saat fokus */
            }
        </style>
    @endpush

    @push('script')
        <script>
            const apiURL = "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json";
            const provinceSelect = document.getElementById("provinceSelect");
            const searchButton = document.getElementById("searchButton");
            const resetButton = document.getElementById("resetButton");

            async function fetchProvinces() {
                try {
                    const response = await fetch(apiURL);
                    if (!response.ok) throw new Error("Gagal mengambil data provinsi");
                    const provinces = await response.json();

                    provinces.forEach(province => {
                        const option = document.createElement("option");
                        option.value = province.name;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error("Terjadi kesalahan:", error);
                }
            }

            searchButton.addEventListener("click", () => {
                const selectedProvince = provinceSelect.value;

                if (selectedProvince !== "all") {
                    window.location.href =
                        `{{ route('report.article.index') }}?province=${encodeURIComponent(selectedProvince)}`;
                }
            });

            resetButton.addEventListener("click", () => {
                window.location.href = `{{ route('report.article.index') }}`;
            });

            document.addEventListener("DOMContentLoaded", fetchProvinces);


            document.addEventListener('DOMContentLoaded', function() {
                const voteButtons = document.querySelectorAll('.vote-button');

                voteButtons.forEach(button => {
                    button.addEventListener('click', async () => {
                        const reportId = button.getAttribute('data-report-id');
                        const voteCountSpan = button.querySelector(
                            'span');

                        try {
                            const response = await fetch(`/vote/${reportId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    action: 'upvote'
                                })
                            });

                            if (response.ok) {
                                const data = await response.json();
                                voteCountSpan.textContent =
                                    `Vote (${data.voting})`;
                            } else {
                                alert('Gagal memperbarui vote.');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
