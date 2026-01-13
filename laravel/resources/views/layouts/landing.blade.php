<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SAPA-BBL - Sistem Skrining Bayi Baru Lahir Laboratorium Kesehatan Provinsi Kalimantan Selatan untuk deteksi dini kelainan bawaan">
    <title>SAPA-BBL | LABKESPROV KALSEL</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#00b4ab',
                            light: '#00d4c9',
                            dark: '#006962',
                        },
                        accent: '#d0dd27',
                        teal: {
                            50: '#f0fdfc',
                            100: '#ccfbf6',
                            200: '#99f6ec',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#00b4ab',
                            600: '#009990',
                            700: '#006962',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .gradient-hero {
            background: linear-gradient(135deg, #006962 0%, #00b4ab 50%, #d0dd27 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #00b4ab, #d0dd27);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 105, 98, 0.1), 0 10px 10px -5px rgba(0, 105, 98, 0.04);
        }
        
        .leaflet-container {
            font-family: 'Inter', sans-serif;
        }
        
        .custom-marker {
            background: #00b4ab;
            border: 3px solid #006962;
            border-radius: 50%;
            width: 24px;
            height: 24px;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .scroll-smooth {
            scroll-behavior: smooth;
        }
    </style>
    
    @livewireStyles
</head>
<body class="bg-white font-sans antialiased scroll-smooth">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-linear-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-primary-dark">SAPA-BBL</span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#tentang" class="text-gray-600 hover:text-primary-dark transition-colors font-medium">Tentang</a>
                    <a href="#program" class="text-gray-600 hover:text-primary-dark transition-colors font-medium">Program</a>
                    <a href="#peta" class="text-gray-600 hover:text-primary-dark transition-colors font-medium">Peta Distribusi</a>
                    <a href="#capaian" class="text-gray-600 hover:text-primary-dark transition-colors font-medium">Capaian</a>
                    <a href="https://app.sapa-bbl.id" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div x-show="mobileMenu" x-cloak x-transition class="md:hidden pb-4">
                <a href="#tentang" class="block py-2 text-gray-600 hover:text-primary-dark">Tentang</a>
                <a href="#program" class="block py-2 text-gray-600 hover:text-primary-dark">Program</a>
                <a href="#peta" class="block py-2 text-gray-600 hover:text-primary-dark">Peta Distribusi</a>
                <a href="#capaian" class="block py-2 text-gray-600 hover:text-primary-dark">Capaian</a>
                <a href="https://apps.sapa-bbl.id" target="_blank" class="mt-3 flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-primary-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">SAPA-BBL</span>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed">
                        Sistem Skrining Bayi Baru Lahir oleh Laboratorium Kesehatan Provinsi Kalimantan Selatan untuk deteksi dini kelainan bawaan demi generasi Banua yang lebih sehat.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-white/80 text-sm">
                        <li><a href="#tentang" class="hover:text-accent transition-colors">Tentang Program</a></li>
                        <li><a href="#program" class="hover:text-accent transition-colors">Jenis Skrining</a></li>
                        <li><a href="#peta" class="hover:text-accent transition-colors">Peta Distribusi</a></li>
                        <li><a href="#capaian" class="hover:text-accent transition-colors">Capaian Program</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-white/80 text-sm">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Jl. Belitung Darat No.119, Banjarmasin</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>labkesda@kalselprov.go.id</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>(0511) 3352105</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/20 mt-8 pt-8 text-center text-white/60 text-sm">
                <p>&copy; {{ date('Y') }} SAPA-BBL. Laboratorium Kesehatan Provinsi Kalimantan Selatan.</p>
            </div>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @livewireScripts
    
    @stack('scripts')
</body>
</html>
