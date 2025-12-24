<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan | TukarYuk - Platform Tukar Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        }
        
        .card-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(to right, #10b981, #059669);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #059669, #047857);
            transform: translateY(-2px);
        }
        
        .avatar-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .active-nav {
            background-color: #f0fdf4;
            color: #059669;
        }
        
        .logo-text {
            background: linear-gradient(to right, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Mobile menu styles */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.active {
            transform: translateX(0);
        }
        
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Modal styles */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 100;
        }
        
        /* Settings specific */
        .settings-tab {
            transition: all 0.2s ease;
        }
        
        .settings-tab.active {
            background-color: #f0fdf4;
            color: #059669;
            border-left: 4px solid #059669;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .settings-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="gradient-bg min-h-screen">
    <!-- Mobile Menu Overlay -->
    <div class="overlay" id="mobileOverlay"></div>
    
    <!-- Mobile Header -->
    <header class="lg:hidden bg-white shadow-sm sticky top-0 z-30">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center">
                <button id="mobileMenuButton" class="mr-3 text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full avatar-gradient flex items-center justify-center mr-2">
                        <i class="fas fa-exchange-alt text-white text-sm"></i>
                    </div>
                    <h1 class="text-xl font-bold logo-text">TukarYuk</h1>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Notifications -->
                <div class="relative">
                    <button class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                        <span class="text-xs text-white">{{ $incomingSwapsCount ?? 0 }}</span>
                    </div>
                </div>
                
                <!-- User Profile -->
                <div class="relative">
                    <button id="userMenuButton" class="w-9 h-9 rounded-full avatar-gradient flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-50 lg:hidden" id="mobileMenu">
        <!-- Menu Header -->
        <div class="p-6 border-b">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full avatar-gradient flex items-center justify-center mr-3">
                        <i class="fas fa-exchange-alt text-white text-lg"></i>
                    </div>
                    <h1 class="text-2xl font-bold logo-text">TukarYuk</h1>
                </div>
                <button id="closeMobileMenu" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- User Profile -->
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 rounded-full avatar-gradient flex items-center justify-center text-white text-lg font-bold mr-3">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 text-sm">{{ auth()->user()->name }}</h2>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="p-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-home text-gray-500 mr-3"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('barang.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-box text-gray-500 mr-3"></i>
                        <span class="font-medium">Barang Saya</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('swaps.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-exchange-alt text-gray-500 mr-3"></i>
                        <span class="font-medium">Penukaran</span>
                        <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">{{ $stats['active_swaps'] ?? 0 }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('conversations') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-comments text-gray-500 mr-3"></i>
                        <span class="font-medium">Percakapan</span>
                        <span class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">5</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('favorites') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-heart text-gray-500 mr-3"></i>
                        <span class="font-medium">Favorit</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings') }}" class="flex items-center p-3 rounded-lg active-nav">
                        <i class="fas fa-cog text-green-600 mr-3"></i>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                </li>
            </ul>
            
            <!-- Quick Actions -->
            <div class="mt-6 p-3">
                <button onclick="openAddItemModal()" class="w-full flex items-center justify-center p-3 bg-green-50 text-green-700 rounded-lg mb-2 hover:bg-green-100 transition">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="font-medium">Tambah Barang</span>
                </button>
                <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-search mr-2"></i>
                    <span class="font-medium">Cari Barang</span>
                </a>
            </div>
            
            <!-- Logout -->
            <div class="mt-6 p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full p-3 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
    
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
        <div class="flex flex-col flex-grow bg-white border-r pt-5 overflow-y-auto">
            <!-- Logo -->
            <div class="px-6 pb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full avatar-gradient flex items-center justify-center mr-3">
                        <i class="fas fa-exchange-alt text-white text-lg"></i>
                    </div>
                    <h1 class="text-2xl font-bold logo-text">TukarYuk</h1>
                </div>
                <p class="text-xs text-gray-500 mt-1">Platform tukar barang terpercaya</p>
            </div>
            
            <!-- User Profile -->
            <div class="px-6 py-4 border-t border-b">
                <div class="flex items-center">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full avatar-gradient flex items-center justify-center text-white text-lg font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="ml-4">
                        <h2 class="font-bold text-gray-800 text-sm">{{ auth()->user()->name }}</h2>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        @if(auth()->user()->location)
                        <div class="flex items-center mt-1">
                            <i class="fas fa-map-marker-alt text-green-500 text-xs mr-1"></i>
                            <span class="text-xs text-gray-600">{{ auth()->user()->location }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-home text-gray-500 mr-3"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('barang.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-box text-gray-500 mr-3"></i>
                            <span class="font-medium">Barang Saya</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('swaps.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-exchange-alt text-gray-500 mr-3"></i>
                            <span class="font-medium">Penukaran</span>
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">{{ $stats['active_swaps'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('conversations') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-comments text-gray-500 mr-3"></i>
                            <span class="font-medium">Percakapan</span>
                            <span class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">5</span>
                        </a>
                </li>
                    <li>
                        <a href="{{ route('favorites') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-heart text-gray-500 mr-3"></i>
                            <span class="font-medium">Favorit</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings') }}" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-cog text-green-600 mr-3"></i>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                    </li>
                </ul>
                
                <!-- Quick Actions -->
                <div class="mt-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3 px-3">Aksi Cepat</h3>
                    <button onclick="openAddItemModal()" class="w-full flex items-center justify-center p-3 bg-green-50 text-green-700 rounded-lg mb-2 hover:bg-green-100 transition">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="font-medium">Tambah Barang</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-search mr-2"></i>
                        <span class="font-medium">Cari Barang</span>
                    </a>
                </div>
            </nav>
            
            <!-- Logout -->
            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full p-3 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="lg:pl-64">
        <!-- Desktop Header -->
        <header class="hidden lg:block bg-white shadow-sm">
            <div class="flex justify-between items-center px-6 py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Akun</h1>
                    <p class="text-gray-600">Kelola informasi profil dan pengaturan akun Anda</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Cari barang..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                            <i class="fas fa-bell text-gray-600"></i>
                        </button>
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                            <span class="text-xs text-white">{{ $incomingSwapsCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <main class="p-4 lg:p-6">
            <!-- Success Message -->
            @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    <div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
            @endif
            
            @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <div>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Settings Content -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 settings-layout">
                <!-- Settings Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl card-shadow overflow-hidden">
                        <div class="p-5 border-b">
                            <h2 class="text-lg font-bold text-gray-800">Pengaturan</h2>
                            <p class="text-sm text-gray-500">Kelola akun Anda</p>
                        </div>
                        <div class="p-2">
                            <button id="profileTab" class="w-full flex items-center p-3 rounded-lg settings-tab active" data-tab="profile">
                                <i class="fas fa-user text-green-600 mr-3"></i>
                                <span class="font-medium">Profil</span>
                            </button>
                            <button id="securityTab" class="w-full flex items-center p-3 rounded-lg settings-tab" data-tab="security">
                                <i class="fas fa-lock text-gray-500 mr-3"></i>
                                <span class="font-medium">Keamanan</span>
                            </button>
                            <button id="notificationsTab" class="w-full flex items-center p-3 rounded-lg settings-tab" data-tab="notifications">
                                <i class="fas fa-bell text-gray-500 mr-3"></i>
                                <span class="font-medium">Notifikasi</span>
                            </button>
                            <button id="privacyTab" class="w-full flex items-center p-3 rounded-lg settings-tab" data-tab="privacy">
                                <i class="fas fa-shield-alt text-gray-500 mr-3"></i>
                                <span class="font-medium">Privasi</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Account Stats -->
                    <div class="bg-white rounded-xl card-shadow p-5 mt-6">
                        <h3 class="font-bold text-gray-800 mb-4">Statistik Akun</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Barang aktif</span>
                                <span class="font-bold text-green-600">{{ auth()->user()->items()->where('status', 'available')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Penukaran aktif</span>
                                <span class="font-bold text-green-600">{{ auth()->user()->swaps()->where('status', 'pending')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Bergabung sejak</span>
                                <span class="font-bold text-gray-700">{{ auth()->user()->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Settings Content Area -->
                <div class="lg:col-span-3">
                    <!-- Profile Settings -->
                    <div id="profileContent" class="settings-content active">
                        <div class="bg-white rounded-xl card-shadow overflow-hidden">
                            <div class="p-5 border-b">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-800">Informasi Profil</h2>
                                        <p class="text-gray-600">Kelola informasi profil dan cara orang lain melihat Anda di platform</p>
                                    </div>
                                    <div class="relative">
                                        <div class="w-16 h-16 rounded-full avatar-gradient flex items-center justify-center text-white text-2xl font-bold">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <button class="absolute -bottom-1 -right-1 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white hover:bg-green-600 transition">
                                            <i class="fas fa-camera text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Name -->
                                        <div>
                                            <label class="block text-gray-700 font-medium mb-2">
                                                <i class="fas fa-user text-green-500 mr-2"></i>Nama Lengkap
                                            </label>
                                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                required>
                                            @error('name')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Email (read-only, masked) -->
                                        <div>
                                            <label class="block text-gray-700 font-medium mb-2">
                                                <i class="fas fa-envelope text-green-500 mr-2"></i>Email
                                            </label>
                                            @php
                                                $fullEmail = old('email', auth()->user()->email);
                                                if (strpos($fullEmail, '@') !== false) {
                                                    [$local, $domain] = explode('@', $fullEmail, 2);
                                                    $maskedLocal = substr($local, 0, 2) . str_repeat('*', max(0, strlen($local) - 2));
                                                    $displayEmail = $maskedLocal . '@' . $domain;
                                                } else {
                                                    $displayEmail = substr($fullEmail, 0, 2) . str_repeat('*', max(0, strlen($fullEmail) - 2));
                                                }
                                            @endphp
                                            <input type="text" value="{{ $displayEmail }}" readonly class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed">
                                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah di sini.</p>
                                        </div>
                                        
                                        <!-- Phone -->
                                        <div>
                                            <label class="block text-gray-700 font-medium mb-2">
                                                <i class="fas fa-phone text-green-500 mr-2"></i>Nomor Telepon
                                            </label>
                                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                                placeholder="Contoh: 081234567890"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @error('phone')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Location -->
                                        <div>
                                            <label class="block text-gray-700 font-medium mb-2">
                                                <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>Lokasi
                                            </label>
                                            <input type="text" name="location" value="{{ old('location', auth()->user()->location) }}" 
                                                placeholder="Contoh: Jakarta Selatan"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @error('location')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Bio -->
                                    <div class="mt-6">
                                        <label class="block text-gray-700 font-medium mb-2">
                                            <i class="fas fa-info-circle text-green-500 mr-2"></i>Bio (Deskripsi Singkat)
                                        </label>
                                        <textarea name="bio" rows="3" placeholder="Ceritakan sedikit tentang diri Anda"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('bio', auth()->user()->bio) }}</textarea>
                                        @error('bio')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Form Actions -->
                                    <div class="mt-8 flex justify-end gap-3">
                                        <button type="button" onclick="resetProfileForm()"
                                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-6 py-3 btn-primary text-white font-medium rounded-lg focus:outline-none focus:shadow-outline">
                                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Settings (password changes handled via account recovery) -->
                    <div id="securityContent" class="settings-content hidden">
                        <div class="bg-white rounded-xl card-shadow overflow-hidden">
                            <div class="p-5 border-b">
                                <h2 class="text-xl font-bold text-gray-800">Keamanan Akun</h2>
                                <p class="text-gray-600">Pengaturan keamanan akun</p>
                            </div>

                            <div class="p-5">
                                <div class="p-6 bg-yellow-50 border border-yellow-100 rounded-lg">
                                    <h4 class="font-medium text-yellow-800 mb-2">Perubahan Kata Sandi</h4>
                                    <p class="text-sm text-yellow-700">Perubahan kata sandi tidak dilakukan di sini. Untuk mengganti kata sandi, gunakan fitur pemulihan kata sandi (Lupa Kata Sandi) atau hubungi dukungan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notifications Settings -->
                    <div id="notificationsContent" class="settings-content hidden">
                        <div class="bg-white rounded-xl card-shadow overflow-hidden">
                            <div class="p-5 border-b">
                                <h2 class="text-xl font-bold text-gray-800">Pengaturan Notifikasi</h2>
                                <p class="text-gray-600">Kelola cara Anda menerima notifikasi</p>
                            </div>
                            
                            <div class="p-5">
                                <form method="POST" action="{{ route('notifications.update') }}" id="notificationsForm">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="space-y-6">
                                        <!-- Email Notifications -->
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-800">Notifikasi Email</h4>
                                                <p class="text-sm text-gray-600">Terima pemberitahuan melalui email</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" 
                                                    {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </div>
                                        
                                        <!-- Swap Notifications -->
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-800">Notifikasi Penukaran</h4>
                                                <p class="text-sm text-gray-600">Pemberitahuan tentang penukaran barang</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="swap_notifications" value="1" class="sr-only peer" 
                                                    {{ auth()->user()->swap_notifications ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </div>
                                        
                                        <!-- Message Notifications -->
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-800">Notifikasi Pesan</h4>
                                                <p class="text-sm text-gray-600">Pemberitahuan pesan baru</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="message_notifications" value="1" class="sr-only peer" 
                                                    {{ auth()->user()->message_notifications ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </div>
                                        
                                        <!-- Marketing Emails -->
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-800">Email Marketing</h4>
                                                <p class="text-sm text-gray-600">Promo dan penawaran khusus</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="marketing_emails" value="1" class="sr-only peer" 
                                                    {{ auth()->user()->marketing_emails ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Form Actions -->
                                    <div class="mt-8 flex justify-end gap-3">
                                        <button type="button" onclick="resetNotificationsForm()"
                                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-6 py-3 btn-primary text-white font-medium rounded-lg focus:outline-none focus:shadow-outline">
                                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Privacy Settings -->
                    <div id="privacyContent" class="settings-content hidden">
                        <div class="bg-white rounded-xl card-shadow overflow-hidden">
                            <div class="p-5 border-b">
                                <h2 class="text-xl font-bold text-gray-800">Pengaturan Privasi</h2>
                                <p class="text-gray-600">Kelola siapa yang dapat melihat informasi Anda</p>
                            </div>
                            
                            <div class="p-5">
                                <form method="POST" action="{{ route('privacy.update') }}" id="privacyForm">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="space-y-6">
                                        <!-- Profile Visibility -->
                                        <div class="p-4 border border-gray-200 rounded-lg">
                                            <h4 class="font-medium text-gray-800 mb-2">Visibilitas Profil</h4>
                                            <p class="text-sm text-gray-600 mb-4">Siapa yang dapat melihat profil Anda</p>
                                            
                                            <div class="space-y-3">
                                                <div class="flex items-center">
                                                    <input type="radio" id="visibility_public" name="profile_visibility" value="public" 
                                                        class="mr-3" {{ (auth()->user()->profile_visibility ?? 'public') == 'public' ? 'checked' : '' }}>
                                                    <label for="visibility_public" class="cursor-pointer">
                                                        <span class="font-medium">Publik</span>
                                                        <p class="text-sm text-gray-600">Semua pengguna dapat melihat profil Anda</p>
                                                    </label>
                                                </div>
                                                
                                                <div class="flex items-center">
                                                    <input type="radio" id="visibility_private" name="profile_visibility" value="private" 
                                                        class="mr-3" {{ (auth()->user()->profile_visibility ?? 'public') == 'private' ? 'checked' : '' }}>
                                                    <label for="visibility_private" class="cursor-pointer">
                                                        <span class="font-medium">Privat</span>
                                                        <p class="text-sm text-gray-600">Hanya pengguna yang Anda setujui yang dapat melihat profil Anda</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Show Email -->
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-800">Tampilkan Email</h4>
                                                <p class="text-sm text-gray-600">Izinkan pengguna lain melihat alamat email Anda</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="show_email" value="1" class="sr-only peer" 
                                                    {{ auth()->user()->show_email ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </div>
                                        
                                        <!-- Show Phone -->
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-800">Tampilkan Nomor Telepon</h4>
                                                <p class="text-sm text-gray-600">Izinkan pengguna lain melihat nomor telepon Anda</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="show_phone" value="1" class="sr-only peer" 
                                                    {{ auth()->user()->show_phone ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </div>
                                        
                                        <!-- Show Location -->
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div>
                                                <h4 class="font-medium text-gray-800">Tampilkan Lokasi</h4>
                                                <p class="text-sm text-gray-600">Izinkan pengguna lain melihat lokasi Anda</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="show_location" value="1" class="sr-only peer" 
                                                    {{ auth()->user()->show_location ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Account Deletion -->
                                    <div class="mt-8 p-4 border border-red-200 bg-red-50 rounded-lg">
                                        <h4 class="font-medium text-red-800 mb-2">Hapus Akun</h4>
                                        <p class="text-sm text-red-600 mb-3">Setelah menghapus akun, semua data Anda akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                                        <button type="button" onclick="openDeleteAccountModal()"
                                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                                            <i class="fas fa-trash mr-2"></i>Hapus Akun Saya
                                        </button>
                                    </div>
                                    
                                    <!-- Form Actions -->
                                    <div class="mt-8 flex justify-end gap-3">
                                        <button type="button" onclick="resetPrivacyForm()"
                                            class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-6 py-3 btn-primary text-white font-medium rounded-lg focus:outline-none focus:shadow-outline">
                                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} TukarYuk. Semua hak dilindungi. • 
                   <a href="#" class="text-green-600 hover:text-green-800 font-medium">Bantuan</a> • 
                   <a href="#" class="text-green-600 hover:text-green-800 font-medium">Kebijakan Privasi</a> • 
                   <a href="#" class="text-green-600 hover:text-green-800 font-medium">Syarat Layanan</a>
                </p>
                <p class="mt-2 text-xs">incs@gmail.com</p>
            </div>
        </main>
    </div>
    
    <!-- Modal Delete Account -->
    <div id="deleteAccountModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white w-full max-w-md rounded-2xl card-shadow overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-red-50 p-6 border-b border-red-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-red-800">Hapus Akun</h2>
                        <p class="text-sm text-red-600 mt-1">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus akun Anda? Semua data termasuk profil, barang, dan riwayat penukaran akan dihapus permanen.</p>
                
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 mb-2">Untuk mengonfirmasi, ketik <span class="font-bold">"HAPUS AKUN"</span> di bawah ini:</p>
                    <input type="text" id="deleteConfirmation" placeholder='Ketik "HAPUS AKUN"' 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                
                <form method="POST" action="{{ route('account.delete') }}" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteAccountModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" id="deleteAccountBtn" disabled
                            class="flex-1 px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-trash mr-2"></i>Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const closeMobileMenu = document.getElementById('closeMobileMenu');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            function openMobileMenu() {
                mobileMenu.classList.add('active');
                mobileOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeMobileMenuFunc() {
                mobileMenu.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            mobileMenuButton.addEventListener('click', openMobileMenu);
            closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
            mobileOverlay.addEventListener('click', closeMobileMenuFunc);
            
            // Close mobile menu when clicking on a link
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenuFunc);
            });
            
            // Settings tab functionality
            const tabButtons = document.querySelectorAll('.settings-tab');
            const contentSections = document.querySelectorAll('.settings-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Update active tab
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.querySelector('i').classList.remove('text-green-600');
                        btn.querySelector('i').classList.add('text-gray-500');
                    });
                    
                    this.classList.add('active');
                    this.querySelector('i').classList.remove('text-gray-500');
                    this.querySelector('i').classList.add('text-green-600');
                    
                    // Show active content
                    contentSections.forEach(section => {
                        section.classList.add('hidden');
                        section.classList.remove('active');
                    });
                    
                    const activeContent = document.getElementById(`${tabId}Content`);
                    if (activeContent) {
                        activeContent.classList.remove('hidden');
                        activeContent.classList.add('active');
                    }
                });
            });
            
            // Password strength indicator
            const newPasswordInput = document.getElementById('newPassword');
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                });
            }
            
            // Delete account confirmation
            const deleteConfirmationInput = document.getElementById('deleteConfirmation');
            const deleteAccountBtn = document.getElementById('deleteAccountBtn');
            
            if (deleteConfirmationInput && deleteAccountBtn) {
                deleteConfirmationInput.addEventListener('input', function() {
                    if (this.value === 'HAPUS AKUN') {
                        deleteAccountBtn.disabled = false;
                    } else {
                        deleteAccountBtn.disabled = true;
                    }
                });
            }
        });
        
        // Settings form reset functions
        function resetProfileForm() {
            if (confirm('Batalkan perubahan pada profil?')) {
                document.getElementById('profileForm').reset();
            }
        }
        
        function resetPasswordForm() {
            if (confirm('Batalkan perubahan kata sandi?')) {
                document.getElementById('passwordForm').reset();
                document.getElementById('passwordStrength').style.width = '0%';
                document.getElementById('passwordStrengthText').textContent = 'Lemah';
                document.getElementById('passwordStrengthText').className = 'text-sm font-medium text-red-500';
                
                // Reset checkmarks
                ['lengthCheck', 'numberCheck', 'letterCheck'].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.querySelector('i').className = 'fas fa-times text-red-400 mr-2';
                    }
                });
            }
        }
        
        function resetNotificationsForm() {
            if (confirm('Batalkan perubahan notifikasi?')) {
                document.getElementById('notificationsForm').reset();
            }
        }
        
        function resetPrivacyForm() {
            if (confirm('Batalkan perubahan privasi?')) {
                document.getElementById('privacyForm').reset();
            }
        }
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let lengthCheck = false;
            let numberCheck = false;
            let letterCheck = false;
            
            // Length check
            if (password.length >= 8) {
                strength += 30;
                lengthCheck = true;
                document.getElementById('lengthCheck').querySelector('i').className = 'fas fa-check text-green-500 mr-2';
            } else {
                document.getElementById('lengthCheck').querySelector('i').className = 'fas fa-times text-red-400 mr-2';
            }
            
            // Number check
            if (/\d/.test(password)) {
                strength += 30;
                numberCheck = true;
                document.getElementById('numberCheck').querySelector('i').className = 'fas fa-check text-green-500 mr-2';
            } else {
                document.getElementById('numberCheck').querySelector('i').className = 'fas fa-times text-red-400 mr-2';
            }
            
            // Letter check
            if (/[a-zA-Z]/.test(password)) {
                strength += 30;
                letterCheck = true;
                document.getElementById('letterCheck').querySelector('i').className = 'fas fa-check text-green-500 mr-2';
            } else {
                document.getElementById('letterCheck').querySelector('i').className = 'fas fa-times text-red-400 mr-2';
            }
            
            // Special character check
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                strength += 10;
            }
            
            // Update strength bar
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');
            
            strengthBar.style.width = `${strength}%`;
            
            if (strength < 30) {
                strengthBar.className = 'h-full bg-red-500 transition-all duration-300';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-sm font-medium text-red-500';
            } else if (strength < 70) {
                strengthBar.className = 'h-full bg-yellow-500 transition-all duration-300';
                strengthText.textContent = 'Sedang';
                strengthText.className = 'text-sm font-medium text-yellow-500';
            } else {
                strengthBar.className = 'h-full bg-green-500 transition-all duration-300';
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-sm font-medium text-green-500';
            }
        }
        
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggleButton = input.nextElementSibling;
            const icon = toggleButton.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'far fa-eye-slash text-gray-400';
            } else {
                input.type = 'password';
                icon.className = 'far fa-eye text-gray-400';
            }
        }
        
        // Delete account modal functions
        function openDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeDeleteAccountModal() {
            document.getElementById('deleteAccountModal').classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('deleteConfirmation').value = '';
            document.getElementById('deleteAccountBtn').disabled = true;
        }
        
        // Close modal when clicking outside
        const deleteModal = document.getElementById('deleteAccountModal');
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteAccountModal();
                }
            });
        }
        
        // Handle form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const forms = ['profileForm', 'passwordForm', 'notificationsForm', 'privacyForm', 'deleteAccountForm'];
            
            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            const originalText = submitBtn.innerHTML;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                            submitBtn.disabled = true;
                            
                            // Reset button after 5 seconds if form fails
                            setTimeout(() => {
                                submitBtn.innerHTML = originalText;
                                submitBtn.disabled = false;
                            }, 5000);
                        }
                    });
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            // Close mobile menu on resize to desktop
            if (window.innerWidth >= 1024) {
                const mobileMenu = document.getElementById('mobileMenu');
                const mobileOverlay = document.getElementById('mobileOverlay');
                
                if (mobileMenu && mobileOverlay) {
                    mobileMenu.classList.remove('active');
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });
    </script>
</body>
</html>