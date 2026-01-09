@extends('layouts.app')
@section('content')
    <div class="absolute inset-0 bg-pattern opacity-10"></div>
    <div class="relative z-10 container mx-auto px-4 py-12 space-y-8">
        <div
            class="max-w-3xl mx-auto bg-white/10 backdrop-blur-sm border border-orange-500 rounded-2xl p-10 shadow-2xl m-10">

            @if ($errors->any())
                <div class="bg-red-500/90 text-white p-4 rounded-lg mb-6 border border-red-600">
                    <div class="flex items-start">
                        <i class="ri-error-warning-line text-2xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <h3 class="font-bold mb-2">Terdapat kesalahan:</h3>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-orange-500 mb-4">
                    <i class="ri-add-circle-line mr-3 text-green-500"></i>Buat Artikel Baru
                </h1>
                <p class="text-gray-300 max-w-xl mx-auto">
                    Bagikan pengalaman dan informasi penting melalui artikel Anda, {{ Auth::user()->name }}
                </p>
            </div>

            <form id="articleForm" method="POST" action="{{ route('report.article.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Kategori -->
                <div class="mb-6">
                    <label class="text-gray-300 mb-2 flex items-center font-semibold">
                        <i class="ri-list-check-2 mr-2 text-green-500"></i>Kategori <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select name="type" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white">
                        <option value="">Pilih Kategori</option>
                        <option value="KEJAHATAN" {{ old('type') == 'KEJAHATAN' ? 'selected' : '' }}>KEJAHATAN</option>
                        <option value="PEMBANGUNAN" {{ old('type') == 'PEMBANGUNAN' ? 'selected' : '' }}>PEMBANGUNAN</option>
                        <option value="SOSIAL" {{ old('type') == 'SOSIAL' ? 'selected' : '' }}>SOSIAL</option>
                    </select>
                    @error('type')
                        <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div class="mb-6">
                    <h3 class="text-white font-semibold mb-3 flex items-center">
                        <i class="ri-map-2-line mr-2 text-orange-500"></i>Lokasi Kejadian
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Provinsi -->
                        <div>
                            <label class="text-gray-300 mb-2 flex items-center text-sm">
                                <i class="ri-map-pin-line mr-2 text-green-500"></i>Provinsi <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="province" id="provinceSelect"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                                required onchange="handleProvinceChange(this)">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($dataProvince as $item)
                                    <option value="{{ $item['name'] }}" data-id="{{ $item['id'] }}"
                                        {{ old('province') == $item['name'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kabupaten -->
                        <div>
                            <label class="text-gray-300 mb-2 flex items-center text-sm">
                                <i class="ri-road-map-line mr-2 text-green-500"></i>Kabupaten <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="regency" id="kabupatenSelect"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                                required disabled>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                            @error('regency')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Kecamatan -->
                        <div>
                            <label class="text-gray-300 mb-2 flex items-center text-sm">
                                <i class="ri-community-line mr-2 text-green-500"></i>Kecamatan <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="subdistrict" id="kecamatanSelect"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                                required disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            @error('subdistrict')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Desa -->
                        <div>
                            <label class="text-gray-300 mb-2 flex items-center text-sm">
                                <i class="ri-home-4-line mr-2 text-green-500"></i>Desa <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="village" id="desaSelect"
                                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white"
                                required disabled>
                                <option value="">Pilih Desa</option>
                            </select>
                            @error('village')
                                <span class="text-red-400 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Konten Artikel -->
                <div class="mb-6">
                    <label class="text-gray-300 mb-2 flex items-center font-semibold">
                        <i class="ri-file-edit-line mr-2 text-green-500"></i>Konten Artikel <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea name="description" rows="6" required
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white custom-scrollbar"
                        placeholder="Tulis artikel Anda di sini... (minimal 20 karakter)">{{ old('description') }}</textarea>
                    <div class="flex justify-between items-center mt-2">
                        @error('description')
                            <span class="text-red-400 text-sm">{{ $message }}</span>
                        @enderror
                        <span id="charCount" class="text-gray-400 text-sm ml-auto">0 karakter</span>
                    </div>
                </div>

                <!-- Upload Gambar -->
                <div class="mb-6">
                    <label class="text-gray-300 mb-2 flex items-center font-semibold">
                        <i class="ri-upload-cloud-line mr-2 text-green-500"></i>Unggah Gambar <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input name="image" type="file" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                            id="imageInput" required
                            class="w-full bg-white/10 text-white border border-white/20 rounded-lg px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-500 file:text-white hover:file:bg-green-600 file:cursor-pointer cursor-pointer">
                        <p class="text-gray-400 text-xs mt-2">Format: JPG, PNG, GIF, SVG. Maksimal 2MB</p>
                    </div>
                    @error('image')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <div class="relative inline-block">
                            <img id="preview" src="" alt="Preview" class="max-w-full max-h-64 rounded-lg border-2 border-green-500 shadow-lg">
                            <button type="button" onclick="removeImage()"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                        <p id="imageName" class="text-gray-300 text-sm mt-2"></p>
                        <p id="imageSize" class="text-gray-400 text-xs"></p>
                    </div>
                </div>

                <!-- Pernyataan -->
                <div class="mb-8">
                    <label class="text-gray-300 flex items-start cursor-pointer group">
                        <input name="statement" type="checkbox" value="1" required
                            class="mt-1 mr-3 w-5 h-5 bg-transparent border-2 border-white/50 rounded focus:ring-2 focus:ring-green-500 checked:bg-green-500 checked:border-green-500">
                        <span class="flex-1 group-hover:text-white transition">
                            <span class="font-semibold">Pernyataan Kebenaran <span class="text-red-500">*</span></span><br>
                            <span class="text-sm text-gray-400">Saya menyatakan bahwa informasi yang saya berikan adalah benar dan dapat dipertanggungjawabkan</span>
                        </span>
                    </label>
                    @error('statement')
                        <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-center space-x-4 pt-6 border-t border-white/20">
                    <button type="reset" onclick="return confirm('Yakin ingin mereset form?')"
                        class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition duration-300 flex items-center font-semibold shadow-lg">
                        <i class="ri-close-circle-line mr-2"></i>Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition duration-300 flex items-center font-semibold shadow-lg">
                        <i class="ri-send-plane-fill mr-2"></i>Publikasikan Artikel
                    </button>
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
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #FF6B00;
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #ff8533;
            }

            select, option {
                background-color: rgba(31, 41, 55, 0.9);
                color: white;
            }

            select:focus, option:focus {
                background-color: #1f2937;
            }

            /* Loading animation */
            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            .loading {
                pointer-events: none;
                opacity: 0.6;
            }

            .loading::after {
                content: "";
                position: absolute;
                width: 16px;
                height: 16px;
                top: 50%;
                left: 50%;
                margin-left: -8px;
                margin-top: -8px;
                border: 2px solid #ffffff;
                border-radius: 50%;
                border-top-color: transparent;
                animation: spin 0.6s linear infinite;
            }
        </style>
    @endpush

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            // Character counter untuk textarea
            const descriptionTextarea = document.querySelector('textarea[name="description"]');
            const charCount = document.getElementById('charCount');

            descriptionTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = `${count} karakter`;
                charCount.style.color = count < 20 ? '#ef4444' : '#10b981';
            });

            // Preview dan validasi gambar
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const preview = document.getElementById('preview');
            const imageName = document.getElementById('imageName');
            const imageSize = document.getElementById('imageSize');

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    // Validasi ukuran file (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB');
                        this.value = '';
                        imagePreview.classList.add('hidden');
                        return;
                    }

                    // Validasi tipe file
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak didukung! Gunakan JPG, PNG, GIF, atau SVG');
                        this.value = '';
                        imagePreview.classList.add('hidden');
                        return;
                    }

                    // Preview gambar
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        imageName.innerHTML = `<i class="ri-file-image-line mr-1"></i>${file.name}`;
                        imageSize.textContent = `Ukuran: ${(file.size / 1024).toFixed(2)} KB`;
                        imagePreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Fungsi hapus gambar
            function removeImage() {
                imageInput.value = '';
                imagePreview.classList.add('hidden');
                preview.src = '';
            }

            // API Wilayah Indonesia
            function handleProvinceChange(selectElement) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const provinceId = selectedOption.getAttribute('data-id');

                if (provinceId) {
                    loadKabupaten(provinceId);
                }
            }

            // Helper function to reset dropdown
            const resetDropdown = (dropdown, placeholder) => {
                dropdown.innerHTML = `<option value="">${placeholder}</option>`;
                dropdown.disabled = true;
            };

            // Load Kabupaten
            async function loadKabupaten(provinceId) {
                const kabupatenSelect = document.getElementById('kabupatenSelect');
                const kecamatanSelect = document.getElementById('kecamatanSelect');
                const desaSelect = document.getElementById('desaSelect');

                resetDropdown(kabupatenSelect, 'Memuat...');
                resetDropdown(kecamatanSelect, 'Pilih Kecamatan');
                resetDropdown(desaSelect, 'Pilih Desa');

                try {
                    const response = await axios.get(
                        `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`
                    );
                    const kabupatenData = response.data;

                    kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
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
                    kabupatenSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                }
            }

            // Event listeners untuk dropdown wilayah
            document.addEventListener('DOMContentLoaded', () => {
                const provinceSelect = document.getElementById('provinceSelect');
                const kabupatenSelect = document.getElementById('kabupatenSelect');
                const kecamatanSelect = document.getElementById('kecamatanSelect');
                const desaSelect = document.getElementById('desaSelect');

                // Fetch Kecamatan when Kabupaten is selected
                kabupatenSelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const kabupatenId = selectedOption.getAttribute('data-id');

                    resetDropdown(kecamatanSelect, 'Memuat...');
                    resetDropdown(desaSelect, 'Pilih Desa');

                    if (kabupatenId) {
                        try {
                            const response = await axios.get(
                                `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupatenId}.json`
                            );
                            const kecamatanData = response.data;

                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
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
                            kecamatanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                        }
                    }
                });

                // Fetch Desa when Kecamatan is selected
                kecamatanSelect.addEventListener('change', async function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const kecamatanId = selectedOption.getAttribute('data-id');

                    resetDropdown(desaSelect, 'Memuat...');

                    if (kecamatanId) {
                        try {
                            const response = await axios.get(
                                `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecamatanId}.json`
                            );
                            const desaData = response.data;

                            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
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
                            desaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                        }
                    }
                });

                // Form submission handling
                const form = document.getElementById('articleForm');
                const submitBtn = document.getElementById('submitBtn');

                form.addEventListener('submit', function(e) {
                    // Validasi tambahan
                    if (descriptionTextarea.value.length < 20) {
                        e.preventDefault();
                        alert('Konten artikel minimal 20 karakter!');
                        descriptionTextarea.focus();
                        return;
                    }

                    // Loading state
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="ri-loader-4-line mr-2 animate-spin"></i>Memproses...';
                });
            });
        </script>
    @endpush
@endsection
