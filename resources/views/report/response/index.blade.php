@extends('layouts.app')

@section('content')
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="relative z-10 container mx-auto px-4 py-12 space-y-8">
        <div class="flex justify-between items-center mb-6">
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
            <h1 class="text-2xl font-bold text-orange-500">Daftar Pengaduan</h1>
            <div class="relative">
                <button id="downloadButton"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors"
                    onclick="toggleDropdown()">
                    <i class="fas fa-file-excel mr-2"></i>
                    Download Excel
                </button>
                <div id="dropdownContent" class="absolute hidden z-10 bg-white shadow-lg rounded-lg mt-2 w-48">
                    <a href="{{ route('response.exportExcel') }}" onclick="hideDownloadButton()"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Download Semua Data</a>
                    <a href="javascript:void(0)" onclick="showDateForm(event)"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Download Berdasarkan Tanggal</a>
                </div>
            </div>

            <form id="dateForm" action="{{ route('response.exportByDate') }}" method="GET"
                class="hidden mt-4 bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col space-y-4">
                    <div>
                        <label for="start_date" class="block text-gray-700">Dari Tanggal:</label>
                        <input type="date" id="start_date" name="start_date" required
                            class="border-gray-300 rounded-lg shadow-sm text-black focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700">Sampai Tanggal:</label>
                        <input type="date" id="end_date" name="end_date" required
                            class="border-gray-300 rounded-lg shadow-sm text-black focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm">
                    </div>
                    <div class="flex justify-between gap-2">
                        <button type="button" onclick="cancelDateForm()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                            Pilih Ulang Opsi
                        </button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            Download
                        </button>
                    </div>
                </div>
            </form>


        </div>
        <div class="bg-green-900/10  shadow-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse border border-orange-500">
                <thead class="bg-white/10">
                    <tr>
                        <th class="px-6 py-3 border-b text-left text-sm font-semibold text-white">Gambar & Pengirim</th>
                        <th class="px-6 py-3 border-b text-left text-sm font-semibold text-white">Lokasi & Tanggal</th>
                        <th class="px-6 py-3 border-b text-left text-sm font-semibold text-white">Deskripsi</th>
                        <th class="px-6 py-3 border-b text-left text-sm font-semibold text-white">
                            Jumlah Vote
                            <span class="ml-2 cursor-pointer hover:underline text-white">⬆️</span>
                        </th>
                        <th class="px-6 py-3 border-b text-left text-sm font-semibold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 border-b flex items-center space-x-4">
                                <img src="{{ asset('user-icon.png') }}" alt="Foto Profil"
                                    class="w-10 h-10 rounded-full border">
                                <span class="text-sm text-white">{{ $report->user->email }}</span>
                            </td>
                            <td class="px-6 py-4 border-b text-sm text-white">
                                <div>{{ strtolower($report->village) }}, {{ strtolower($report->subdistrict) }},
                                    {{ strtolower($report->regency) }},
                                    {{ strtolower($report->province) }}</div>
                                <div class="text-white/50">{{ $report->created_at->translatedFormat('j F Y') }}</div>
                            </td>
                            <td class="px-6 py-4 border-b text-sm text-white">
                                {{ implode(' ', array_slice(explode(' ', $report->description), 0, 5)) }}...
                            </td>
                            <td class="px-6 py-4 border-b text-center text-sm text-white">
                                {{ is_array($report->voting) ? count($report->voting) : 0 }}</td>
                            <td class="px-6 py-4 border-b text-center">
                                @php
                                    $response = $report->responses->first();
                                @endphp

                                {{-- @if (!$response || empty($response->response_status)) --}}
                                <div class="flex space-x-4">
                                    <a href="{{ route('response.response', $report->id) }}"
                                        class="text-green-500 hover:text-green-600">Proses</a>
                                    <form action="{{ route('response.reject', $report->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menolak laporan ini?')">
                                        @csrf
                                        <button type="submit" class="text-orange-500 hover:text-orange-600">Tolak</button>
                                    </form>
                                </div>
                                {{-- @endif --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('script')
        <script>
            function toggleDropdown() {
                const dropdownContent = document.getElementById('dropdownContent');
                dropdownContent.classList.toggle('hidden');
            }

            function showDateForm(event) {
                event.preventDefault();
                document.getElementById('dateForm').classList.remove('hidden');
                document.getElementById('dropdownContent').classList.add('hidden');
                hideDownloadButton();
            }

            function cancelDateForm() {
                document.getElementById('dateForm').classList.add('hidden');
                document.getElementById('dropdownContent').classList.remove('hidden');
                showDownloadButton();
            }

            function hideDownloadButton() {
                document.getElementById('downloadButton').classList.add('hidden');
            }

            function showDownloadButton() {
                document.getElementById('downloadButton').classList.remove('hidden');
            }

            window.onclick = function(event) {
                const dropdownContent = document.getElementById('dropdownContent');
                if (!event.target.closest('.relative') && !dropdownContent.contains(event.target)) {
                    dropdownContent.classList.add('hidden');
                }
            };
        </script>
    @endpush
@endsection
