<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiPengaduan</title>
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

<body class="bg-black text-white min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 bg-pattern opacity-10"></div>

    <!-- Floating Icons -->
    <div class="absolute top-20 left-10 md:left-20 opacity-20 floating-icon hidden md:block">
        <i class="ri-shield-check-line text-4xl md:text-6xl text-orange-500"></i>
    </div>
    <div class="absolute bottom-20 right-10 md:right-20 opacity-20 floating-icon hidden md:block">
        <i class="ri-customer-service-line text-4xl md:text-6xl text-green-500"></i>
    </div>

    <div
        class="relative z-10 w-full max-w-md bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl p-8 md:p-12 shadow-2xl">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-orange-500 mb-4">SiPengaduan</h1>
            <p class="text-gray-300">Masuk ke Platform Aspirasi Masyarakat</p>
        </div>

        <form action="{{ route('postLogin') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-green-500 mb-2 text-sm font-bold">Email</label>
                <div class="relative">
                    <i class="ri-mail-line absolute left-4 top-1/2 transform -translate-y-1/2 text-orange-500"></i>
                    <input name="email" type="email" placeholder="Masukkan email Anda"
                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-white">
                </div>
            </div>

            <div>
                <label class="block text-green-500 mb-2 text-sm font-bold">Kata Sandi</label>
                <div class="relative">
                    <i class="ri-lock-line absolute left-4 top-1/2 transform -translate-y-1/2 text-orange-500"></i>
                    <input name="password" type="password" placeholder="Masukkan kata sandi"
                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-white">
                </div>
            </div>
            <div class="w-full flex justify-center gap-2">
                <button type="submit""
                    class="w-full bg-green-500 text-black py-3 rounded-lg font-bold hover:bg-green-600 transition-colors flex items-center justify-center">
                    <i class="ri-login-circle-line mr-2"></i>
                    Buat Akun
                </button>
                <button type="submit"
                    class="w-full bg-orange-500 text-black py-3 rounded-lg font-bold hover:bg-orange-600 transition-colors flex items-center justify-center">
                    <i class="ri-login-circle-line mr-2"></i>
                    Masuk
                </button>
            </div>
        </form>
    </div>
</body>

</html>
