<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiPengaduan - Suara Masyarakat</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
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

        .bg-pattern {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="bg-black text-white">
    <div class="min-h-screen relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>

        <div class="absolute top-20 left-10 md:left-20 opacity-20 floating-icon hidden md:block">
            <i class="ri-shield-check-line text-4xl md:text-6xl text-orange-500"></i>
        </div>
        <div class="absolute bottom-20 right-10 md:right-20 opacity-20 floating-icon hidden md:block">
            <i class="ri-customer-service-line text-4xl md:text-6xl text-green-500"></i>
        </div>

        <div class="container mx-auto px-4 relative z-10 flex flex-col justify-center items-center min-h-screen">
            <div class="text-center max-w-4xl">
                <h1 class="text-4xl md:text-7xl font-black mb-6 text-orange-500 tracking-tight leading-tight">
                    SUARA RAKYAT
                    <br />
                    <span class="text-white">ADALAH KEKUATAN</span>
                </h1>
                <p class="text-lg md:text-2xl text-gray-300 mb-12 px-4 md:px-12">
                    Platform pengaduan masyarakat yang transparan, aman, dan responsif.
                    Bergabunglah untuk menciptakan perubahan nyata di lingkungan Anda.
                </p>

                <div class="grid md:grid-cols-3 gap-4 md:gap-8 mb-12 md:mb-16">
                    <div
                        class="bg-white/10 backdrop-blur-sm border border-white/20 p-4 md:p-6 rounded-xl hover:bg-white/20 transition-all group">
                        <i class="ri-lock-line text-4xl md:text-5xl text-orange-500 mb-2 md:mb-4 block text-center"></i>
                        <h3 class="text-lg md:text-xl font-bold mb-1 md:mb-2 text-green-500">Keamanan Terjamin</h3>
                        <p class="text-xs md:text-sm text-gray-300">Sistem keamanan mutakhir melindungi setiap data
                            pengaduan Anda.</p>
                    </div>
                    <div
                        class="bg-white/10 backdrop-blur-sm border border-white/20 p-4 md:p-6 rounded-xl hover:bg-white/20 transition-all group">
                        <i
                            class="ri-speed-line text-4xl md:text-5xl text-orange-500 mb-2 md:mb-4 block text-center"></i>
                        <h3 class="text-lg md:text-xl font-bold mb-1 md:mb-2 text-green-500">Proses Cepat</h3>
                        <p class="text-xs md:text-sm text-gray-300">Pengaduan Anda akan segera ditindaklanjuti oleh tim
                            profesional.</p>
                    </div>
                    <div
                        class="bg-white/10 backdrop-blur-sm border border-white/20 p-4 md:p-6 rounded-xl hover:bg-white/20 transition-all group">
                        <i
                            class="ri-message-3-line text-4xl md:text-5xl text-orange-500 mb-2 md:mb-4 block text-center"></i>
                        <h3 class="text-lg md:text-xl font-bold mb-1 md:mb-2 text-green-500">Transparansi Penuh</h3>
                        <p class="text-xs md:text-sm text-gray-300">Lacak status pengaduan Anda secara real-time dengan
                            mudah.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-6">
                    <a href="{{ route('login') }}"
                        class="bg-orange-500 text-black px-6 md:px-10 py-3 md:py-4 rounded-full font-bold hover:bg-orange-600 transition-colors flex items-center justify-center">
                        <i class="ri-login-circle-line mr-2"></i>
                        Bergabung Sekarang
                    </a>
                </div>
            </div>

            <footer class=" p-2 md:p-4 text-center text-gray-500 text-xs md:text-sm">
                © 2024 SiPengaduan - Platform Aspirasi Masyarakat
            </footer>
        </div>

    </div>
</body>

</html>
