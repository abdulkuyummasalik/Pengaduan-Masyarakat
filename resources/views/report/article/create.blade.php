@extends('layouts.app')
@section('content')
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="relative z-10 container mx-auto px-4 py-12 space-y-8">
        <div
            class="max-w-3xl mx-auto bg-white/10 backdrop-blur-sm border border-orange-500 rounded-2xl p-10 shadow-2xl m-10">

            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            @endif

            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-orange-500 mb-4">
                    <i class="ri-add-circle-line mr-3 text-green-500"></i>Buat Artikel Baru
                </h1>
                <p class="text-gray-300 max-w-xl mx-auto">
                    Bagikan pengalaman dan informasi penting melalui artikel Anda {{ Auth::user()->name }}
                </p>
            </div>

            <form id="articleForm" method="POST" action="{{ route('report.article.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="text-gray-300 mb-2 flex items-center">
                            <i class="ri-list-check-2 mr-2 text-green-500"></i>Kategori
                        </label>
                        <select name="type" required
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white">
                            <option value="">Pilih Kategori</option>
                            <option value="KEJAHATAN">KEJAHATAN</option>
                            <option value="PEMBANGUNAN">PEMBANGUNAN</option>
                            <option value="SOSIAL">SOSIAL</option>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="text-gray-300 mb-2 flex items-center">
                            <i class="ri-map-pin-line mr-2 text-green-500"></i>Provinsi
                        </label>
                        <select name="province" id="provinceSelect"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                            required onchange="hangleProvinceId(this)">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($dataProvince as $item)
                                <option value="{{ $item['name'] }}" data-id="{{ $item['id'] }}">
                                    {{ $item['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-gray-300 mb-2 flex items-center">
                            <i class="ri-road-map-line mr-2 text-green-500"></i>Kabupaten
                        </label>
                        <select name="regency" id="kabupatenSelect"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                            disabled>
                            <option value="">Pilih Kabupaten</option>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="text-gray-300 mb-2 flex items-center">
                            <i class="ri-community-line mr-2 text-green-500"></i>Kecamatan
                        </label>
                        <select name="subdistrict" id="kecamatanSelect"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                            disabled>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-gray-300 mb-2 flex items-center">
                            <i class="ri-home-4-line mr-2 text-green-500"></i>Desa
                        </label>
                        <select name="village" id="desaSelect"
                            class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                            disabled>
                            <option value="">Pilih Desa</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="text-gray-300 mb-2 flex items-center">
                        <i class="ri-file-edit-line mr-2 text-green-500"></i>Konten Artikel
                    </label>
                    <textarea name="description" rows="6"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white custom-scrollbar"
                        placeholder="Tulis artikel Anda di sini..."></textarea>
                </div>

                <div class="mt-6">
                    <label class="text-gray-300 mb-2 flex items-center">
                        <i class="ri-upload-cloud-line mr-2 text-green-500"></i>Unggah Gambar
                    </label>
                    <input name="image" type="file" accept="image/*"
                        class="w-full bg-transparent text-white border border-white rounded-lg px-3 py-2">
                </div>

                <div class="mt-6">
                    <label class="text-gray-300 flex items-center">
                        <input name="statement" type="checkbox" value="1"
                            class="mr-2 bg-transparent border border-white rounded">
                        <span>Pernyataan Saya Benar</span>
                    </label>
                </div>


                <div class="flex justify-center space-x-6 mt-10">
                    <button type="reset"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-800 transition">Batal</button>
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-800 transition">Publikasikan
                        Artikel</button>
                </div>
            </form>
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

            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #FF6B00;
                border-radius: 4px;
            }

            @keyframes float {
                0% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-15px);
                }

                100% {
                    transform: translateY(0px);
                }
            }

            .floating-icon {
                animation: float 3s ease-in-out infinite;
            }

            select,
            option {
                background-color: rgba(255, 255, 255, 0.1);
                color: white;
            }

            select:focus,
            option:focus {
                background-color: #2c2c2c;
            }
        </style>
    @endpush

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            let provinceId = "";
            const hangleProvinceId = (selectElement) => {
                const selectedOption = selectElement.options[selectElement.selectedIndex];

                const provinceData = {
                    name: selectedOption.value,
                    id: selectedOption.getAttribute('data-id'),
                };
                provinceId =
                    console.log(provinceData);


            }
            document.addEventListener('DOMContentLoaded', () => {
                const provinceSelect = document.getElementById('provinceSelect');
                const kabupatenSelect = document.getElementById('kabupatenSelect');
                const kecamatanSelect = document.getElementById('kecamatanSelect');
                const desaSelect = document.getElementById('desaSelect');

                // Helper function to reset dropdown
                const resetDropdown = (dropdown, placeholder) => {
                    dropdown.innerHTML = `<option value="">${placeholder}</option>`;
                    dropdown.disabled = true;
                };

                // Fetch Kabupaten when Province is selected
                provinceSelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const provinceId = selectedOption.getAttribute('data-id');
                    resetDropdown(kabupatenSelect, 'Pilih Kabupaten');
                    resetDropdown(kecamatanSelect, 'Pilih Kecamatan');
                    resetDropdown(desaSelect, 'Pilih Desa');

                    if (provinceId) {
                        try {
                            const response = await axios.get(
                                `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`
                            );
                            const kabupatenData = response.data;

                            kabupatenSelect.disabled = false;
                            kabupatenData.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.name;
                                option.textContent = item.name;
                                option.setAttribute('data-id', item.id);
                                kabupatenSelect.appendChild(option);
                            });
                        } catch (error) {
                            console.error('Error fetching Kabupaten:', error);
                        }
                    }
                });

                // Fetch Kecamatan when Kabupaten is selected
                kabupatenSelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const kabupatenId = selectedOption.getAttribute('data-id');
                    resetDropdown(kecamatanSelect, 'Pilih Kecamatan');
                    resetDropdown(desaSelect, 'Pilih Desa');

                    if (kabupatenId) {
                        try {
                            const response = await axios.get(
                                `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupatenId}.json`
                            );
                            const kecamatanData = response.data;

                            kecamatanSelect.disabled = false;
                            kecamatanData.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.name;
                                option.textContent = item.name;
                                option.setAttribute('data-id', item.id);
                                kecamatanSelect.appendChild(option);
                            });
                        } catch (error) {
                            console.error('Error fetching Kecamatan:', error);
                        }
                    }
                });

                // Fetch Desa when Kecamatan is selected
                kecamatanSelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const kecamatanId = selectedOption.getAttribute('data-id');
                    resetDropdown(desaSelect, 'Pilih Desa');

                    if (kecamatanId) {
                        try {
                            const response = await axios.get(
                                `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecamatanId}.json`
                            );
                            const desaData = response.data;

                            desaSelect.disabled = false;
                            desaData.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.name;
                                option.textContent = item.name;
                                option.setAttribute('data-id', item.id);
                                desaSelect.appendChild(option);
                            });
                        } catch (error) {
                            console.error('Error fetching Desa:', error);
                        }
                    }
                });
            });
        </script>
    @endpush
