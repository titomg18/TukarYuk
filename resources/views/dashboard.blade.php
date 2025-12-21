<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TukarYuk - Platform Tukar Barang</title>
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
        
        .status-available {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-in_swap {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
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
        
        /* Line clamp for descriptions */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .filter-grid {
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
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg active-nav">
                        <i class="fas fa-home text-green-600 mr-3"></i>
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
                    <a href="{{ route('settings') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-cog text-gray-500 mr-3"></i>
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
                        <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-home text-green-600 mr-3"></i>
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
                        <a href="{{ route('settings') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-cog text-gray-500 mr-3"></i>
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
                    <h1 class="text-2xl font-bold text-gray-800">Selamat datang, <span class="logo-text">{{ auth()->user()->name }}</span>!</h1>
                    <p class="text-gray-600">Jelajahi barang-barang menarik untuk ditukar</p>
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

            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 mb-6 card-shadow">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Jelajahi Barang TukarYuk</h2>
                        <p class="text-gray-600 mb-4 md:mb-0">Temukan barang menarik dari seluruh pengguna TukarYuk</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="openAddItemModal()" class="btn-primary text-white font-medium py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline">
                            <i class="fas fa-plus mr-2"></i>Tambah Barang
                        </button>
                        <a href="{{ route('dashboard') }}" class="bg-white text-green-700 border border-green-200 font-medium py-2 px-4 rounded-lg hover:bg-green-50 transition flex items-center">
                            <i class="fas fa-search mr-2"></i>Cari Barang
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Filter Section -->
            <div class="bg-white rounded-xl card-shadow p-5 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 filter-grid">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input type="text" placeholder="Cari barang..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Category Filter -->
                    <div>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="elektronik">Elektronik</option>
                            <option value="pakaian">Pakaian</option>
                            <option value="buku">Buku & Alat Tulis</option>
                            <option value="rumah_tangga">Rumah Tangga</option>
                            <option value="olahraga">Olahraga</option>
                            <option value="hobi">Hobi & Koleksi</option>
                        </select>
                    </div>
                    
                    <!-- Type Filter -->
                    <div>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Tipe</option>
                            <option value="swap">Tukar Barang</option>
                            <option value="free">Gratis</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- All Items Grid -->
            <div class="bg-white rounded-xl card-shadow p-5 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Barang Tersedia</h2>
                        <p class="text-sm text-gray-500 mt-1">Temukan barang yang sesuai dengan keinginan Anda</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700">{{ $items->count() }} barang</span>
                        <div class="relative">
                            <select class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full px-3 py-1.5 pr-8">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="popular">Populer</option>
                                <option value="nearby">Terdekat</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($items->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($items as $item)
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <!-- Item Image -->
                        <div class="w-full h-48 overflow-hidden bg-gray-100 relative">
                            @if($item->images && $item->images->first())
                                <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" 
                                     alt="{{ $item->title }}"
                                     class="w-full h-full object-cover hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <i class="fas fa-box text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Item Type Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item->type == 'swap' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                                    {{ $item->type == 'swap' ? 'TUKAR' : 'GRATIS' }}
                                </span>
                            </div>
                            
                            <!-- User Profile -->
                            <div class="absolute top-3 right-3">
                                <div class="w-8 h-8 rounded-full avatar-gradient flex items-center justify-center text-white text-sm font-bold shadow-md hover:shadow-lg transition" title="{{ $item->user->name }}">
                                    {{ substr($item->user->name, 0, 1) }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Item Info -->
                        <div class="p-5">
                            <h3 class="font-bold text-gray-800 mb-2 text-base truncate">{{ $item->title }}</h3>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2 min-h-[2.5rem]">{{ $item->description ?? 'Tidak ada deskripsi' }}</p>
                            
                            <!-- Item Details -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full border border-gray-200">{{ $item->condition }}</span>
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full border border-gray-200">{{ $item->category }}</span>
                                @if($item->location)
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full border border-gray-200 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1 text-xs"></i>{{ Str::limit($item->location, 15) }}
                                </span>
                                @endif
                            </div>
                            
                            <!-- User & Time -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center text-white text-xs font-bold mr-2">
                                        {{ substr($item->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-medium text-gray-700 truncate max-w-[100px]">{{ $item->user->name }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <!-- Action Button -->
                            @if($item->user_id == auth()->id())
                                <a href="{{ route('barang.index') }}" class="block w-full py-2.5 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition font-medium">
                                    <i class="fas fa-box mr-2"></i>Barang Anda
                                </a>
                            @else
                                <button onclick="openSwapModal(
                                    {{ $item->id }}, 
                                    '{{ addslashes($item->title) }}', 
                                    {{ $item->user_id }}, 
                                    '{{ addslashes($item->user->name) }}', 
                                    '{{ $item->images->first() ? asset('storage/' . $item->images->first()->image_path) : '' }}'
                                )" 
                                   class="w-full py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-center rounded-lg hover:from-green-600 hover:to-green-700 transition font-medium shadow-md hover:shadow-lg">
                                    <i class="fas fa-exchange-alt mr-2"></i>Ajukan Tukar
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination (if needed) -->
                @if($items->hasPages())
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-center">
                        {{ $items->links() }}
                    </div>
                </div>
                @endif
                
                @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada barang tersedia</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Tambahkan barang pertama Anda untuk mulai bertukar atau tunggu pengguna lain menambahkan barang mereka</p>
                    <button onclick="openAddItemModal()" class="btn-primary text-white font-medium py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Tambah Barang Pertama
                    </button>
                </div>
                @endif
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
    
    <!-- Modal Tambah Barang -->
    <div id="addItemModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white w-full max-w-lg rounded-2xl card-shadow overflow-hidden max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Tambah Barang Baru</h2>
                        <p class="text-sm text-gray-600 mt-1">Lengkapi informasi barang Anda</p>
                    </div>
                    <button onclick="closeAddItemModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Form -->
            <div class="p-6">
                <form method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data" id="addItemForm">
                    @csrf
                    
                    <!-- Title -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-heading text-green-500 mr-2"></i>Judul Barang
                        </label>
                        <input type="text" name="title" placeholder="Contoh: Headphone Bluetooth Sony"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            required>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-align-left text-green-500 mr-2"></i>Deskripsi
                        </label>
                        <textarea name="description" rows="3" placeholder="Deskripsikan barang Anda secara detail..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-tag text-green-500 mr-2"></i>Kategori
                        </label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="elektronik">Elektronik</option>
                            <option value="pakaian">Pakaian</option>
                            <option value="buku">Buku & Alat Tulis</option>
                            <option value="rumah_tangga">Rumah Tangga</option>
                            <option value="olahraga">Olahraga</option>
                            <option value="hobi">Hobi & Koleksi</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <!-- Condition -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-star text-green-500 mr-2"></i>Kondisi Barang
                        </label>
                        <select name="condition" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" required>
                            <option value="baru">Baru</option>
                            <option value="bekas_layak">Bekas Layak</option>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-exchange-alt text-green-500 mr-2"></i>Jenis Penawaran
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="radio" id="type_swap" name="type" value="swap" class="hidden peer" checked>
                                <label for="type_swap" class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                    <i class="fas fa-exchange-alt text-green-600 text-xl mb-2"></i>
                                    <span class="font-medium">Tukar Barang</span>
                                    <span class="text-xs text-gray-500 mt-1">Tukar dengan barang lain</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="type_free" name="type" value="free" class="hidden peer">
                                <label for="type_free" class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                    <i class="fas fa-gift text-green-600 text-xl mb-2"></i>
                                    <span class="font-medium">Gratis</span>
                                    <span class="text-xs text-gray-500 mt-1">Berikan secara gratis</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Photos -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-images text-green-500 mr-2"></i>Foto Barang
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-600 mb-2">Drag & drop foto atau klik untuk memilih</p>
                            <p class="text-sm text-gray-500">Maksimal 5 foto (JPEG, PNG, JPG)</p>
                            <input type="file" name="photos[]" multiple accept="image/*" class="hidden" id="fileInput">
                            <button type="button" onclick="document.getElementById('fileInput').click()" class="mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                Pilih Foto
                            </button>
                        </div>
                        <div id="fileList" class="mt-3 space-y-2"></div>
                    </div>
                    
                    <!-- Location -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>Lokasi
                        </label>
                        <input type="text" name="location" value="{{ auth()->user()->location ?? '' }}" placeholder="Contoh: Jakarta Selatan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan lokasi profil Anda</p>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="closeAddItemModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3 btn-primary text-white font-medium rounded-lg focus:outline-none focus:shadow-outline">
                            <i class="fas fa-save mr-2"></i>Simpan Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal Ajukan Tukar -->
    <div id="swapModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-overlay">
        <div class="bg-white w-full max-w-lg rounded-2xl card-shadow overflow-hidden max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Ajukan Penukaran Barang</h2>
                        <p class="text-sm text-gray-600 mt-1">Tawarkan barang Anda untuk ditukar</p>
                    </div>
                    <button onclick="closeSwapModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Form -->
            <div class="p-6">
                <!-- Info barang yang ingin ditukar -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-medium text-gray-800 mb-2">Barang yang ingin Anda tukar:</h3>
                    <div class="flex items-center">
                        <img id="swapItemImage" src="/placeholder.svg" alt="" class="w-16 h-16 object-cover rounded mr-3 hidden">
                        <div id="swapItemImagePlaceholder" class="w-16 h-16 bg-gray-200 rounded mr-3 flex items-center justify-center">
                            <i class="fas fa-box text-gray-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 id="swapItemTitle" class="font-bold text-gray-800"></h4>
                            <p id="swapItemOwner" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                </div>
                
                <form id="swapForm">
                    @csrf
                    <input type="hidden" name="item_id" id="swapItemId">
                    <input type="hidden" name="type" value="swap">
                    
                    <!-- Pilih barang yang akan ditawarkan -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-3">
                            <i class="fas fa-box text-green-500 mr-2"></i>Pilih barang Anda yang akan ditukarkan:
                        </label>
                        
                        <div id="userItemsContainer">
                            <!-- Data akan di-load via JavaScript -->
                            <div class="text-center p-4">
                                <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                                <p class="text-gray-500 mt-2">Memuat data barang...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Input field untuk barang custom ketika pilih "Lain-lain" -->
                    <div id="otherItemInputContainer" class="mb-6 hidden">
                        <label class="block text-gray-700 font-medium mb-3">
                            <i class="fas fa-keyboard text-green-500 mr-2"></i>Nama barang Anda:
                        </label>
                        <input type="text" name="other_item_name" id="otherItemName" 
                               placeholder="Contoh: Jam tangan, Sepatu, Buku, dll."
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    
                    <!-- Pesan (opsional) -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            <i class="fas fa-comment text-green-500 mr-2"></i>Pesan (opsional)
                        </label>
                        <textarea name="message" rows="3" placeholder="Sampaikan alasan atau detail penukaran..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <!-- Action buttons -->
                    <div class="flex gap-3">
                        <button type="button" onclick="closeSwapModal()"
                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" id="swapSubmitBtn"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-green-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i>Ajukan Penukaran
                        </button>
                    </div>
                    
                    <!-- Tambah error message container -->
                    <div id="swapFormError" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-600 text-sm"><i class="fas fa-exclamation-circle mr-2"></i><span id="swapFormErrorMessage"></span></p>
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
            
            // File input preview
            const fileInput = document.getElementById('fileInput');
            const fileList = document.getElementById('fileList');
            
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    fileList.innerHTML = '';
                    const files = Array.from(this.files).slice(0, 5); // Limit to 5 files
                    
                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const fileItem = document.createElement('div');
                            fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg';
                            fileItem.innerHTML = `
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded overflow-hidden mr-3">
                                        <img src="${e.target.result}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium truncate max-w-[150px]">${file.name}</p>
                                        <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
                                    </div>
                                </div>
                                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            fileList.appendChild(fileItem);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });
        
        // Function to remove file from preview
        function removeFile(index) {
            const fileInput = document.getElementById('fileInput');
            const dt = new DataTransfer();
            const files = Array.from(fileInput.files);
            
            files.forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            fileInput.files = dt.files;
            
            // Trigger change event to update preview
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
        
        // Modal functions untuk tambah barang
        function openAddItemModal() {
            const modal = document.getElementById('addItemModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeAddItemModal() {
            const modal = document.getElementById('addItemModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                // Reset form
                const form = document.getElementById('addItemForm');
                if (form) form.reset();
                const fileList = document.getElementById('fileList');
                if (fileList) fileList.innerHTML = '';
            }
        }
        
        // Close modal when clicking outside
        const modal = document.getElementById('addItemModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddItemModal();
                }
            });
        }
        
        // Form submission handling untuk tambah barang
        const form = document.getElementById('addItemForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Add loading state
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
        
        // Swap Modal Functions
        let currentSwapItemId = null;
        
        function openSwapModal(itemId, itemTitle, ownerId, ownerName, itemImage) {
            currentSwapItemId = itemId;
            
            // Set data barang yang ingin ditukar
            document.getElementById('swapItemId').value = itemId;
            document.getElementById('swapItemTitle').textContent = itemTitle;
            document.getElementById('swapItemOwner').textContent = 'Milik: ' + ownerName;
            
            // Set gambar atau tampilkan placeholder
            const imgElement = document.getElementById('swapItemImage');
            const imgPlaceholder = document.getElementById('swapItemImagePlaceholder');
            if (itemImage && itemImage !== '') {
                imgElement.src = itemImage;
                imgElement.classList.remove('hidden');
                imgPlaceholder.classList.add('hidden');
            } else {
                imgElement.classList.add('hidden');
                imgPlaceholder.classList.remove('hidden');
            }
            
            // Tampilkan modal
            document.getElementById('swapModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Load barang milik user
            loadUserItems(itemId);
        }
        
        function closeSwapModal() {
            document.getElementById('swapModal').classList.add('hidden');
            document.body.style.overflow = '';
            
            // Reset form
            document.getElementById('swapForm').reset();
            document.getElementById('swapItemId').value = '';
            
            // Reset container
            document.getElementById('userItemsContainer').innerHTML = `
                <div class="text-center p-4">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                    <p class="text-gray-500 mt-2">Memuat data barang...</p>
                </div>
            `;
            // Hide error message
            document.getElementById('swapFormError').classList.add('hidden');
        }
        
        // Perbaiki bagian loadUserItems function untuk error handling yang lebih baik
        async function loadUserItems(excludeItemId) {
            const container = document.getElementById('userItemsContainer');
            const submitBtn = document.getElementById('swapSubmitBtn');
            const otherItemInputContainer = document.getElementById('otherItemInputContainer');
            const otherItemNameInput = document.getElementById('otherItemName');
            const swapFormError = document.getElementById('swapFormError');
            const swapFormErrorMessage = document.getElementById('swapFormErrorMessage');
            
            // Hide other item input initially
            if (otherItemInputContainer) otherItemInputContainer.classList.add('hidden');
            if (otherItemNameInput) otherItemNameInput.value = '';
            if (swapFormError) swapFormError.classList.add('hidden');
            
            // Show loading indicator
            container.innerHTML = `
                <div class="text-center p-4">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                    <p class="text-gray-500 mt-2">Memuat data barang...</p>
                </div>
            `;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token tidak ditemukan');
                }
                
                const response = await fetch(`/api/user-items?exclude=${excludeItemId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({})); // Try to parse JSON error
                    throw new Error(`HTTP Error: ${response.status} ${response.statusText}. ${errorData.message || ''}`);
                }
                
                const items = await response.json();
                
                if (items && items.length > 0) {
                    let html = '<div class="space-y-3 max-h-60 overflow-y-auto pr-2">';
                    items.forEach((item, index) => {
                        html += `
                            <div class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="offered_item_id" value="${item.id}" 
                                       id="user_item_${index}" class="mr-3" required>
                                <label for="user_item_${index}" class="flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        ${item.has_image ? 
                                            `<img src="${item.image_url}" alt="${item.title}" class="w-12 h-12 object-cover rounded mr-3">` :
                                            `<div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>`
                                        }
                                        <div>
                                            <h4 class="font-medium text-gray-800">${item.title}</h4>
                                            <p class="text-xs text-gray-500">${item.condition} • ${item.category}</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        `;
                    });
                    
                    // Add the 'Other' option
                    html += `
                        <div class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                            <input type="radio" name="offered_item_id" value="other" 
                                   id="user_item_other" class="mr-3" required>
                            <label for="user_item_other" class="flex-1 cursor-pointer">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                        <i class="fas fa-plus text-gray-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Lain-lain</h4>
                                        <p class="text-xs text-gray-500">Barang yang tidak ada di atas</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    `;
                    
                    html += '</div>';
                    container.innerHTML = html;
                    
                    // Enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Ajukan Penukaran';
                } else {
                    // No items found
                    container.innerHTML = `
                        <div class="text-center p-6 border-2 border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-box-open text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-500 mb-3">Anda belum memiliki barang yang bisa ditawarkan</p>
                            <a href="{{ route('barang.index') }}" class="text-green-600 hover:text-green-800 font-medium">
                                <i class="fas fa-plus mr-1"></i> Tambah Barang
                            </a>
                        </div>
                    `;
                    
                    // Disable submit button if no items
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Tidak ada barang';
                }
                
                // Add event listener for radio buttons to show/hide other item input
                const radioButtons = container.querySelectorAll('input[name="offered_item_id"]');
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'other') {
                            if (otherItemInputContainer) otherItemInputContainer.classList.remove('hidden');
                            if (otherItemNameInput) otherItemNameInput.required = true;
                        } else {
                            if (otherItemInputContainer) otherItemInputContainer.classList.add('hidden');
                            if (otherItemNameInput) otherItemNameInput.required = false;
                            if (otherItemNameInput) otherItemNameInput.value = ''; // Clear input when hiding
                        }
                    });
                });
                
            } catch (error) {
                console.error('Error loading items:', error);
                if (swapFormError && swapFormErrorMessage) {
                    swapFormErrorMessage.textContent = `Gagal memuat data barang: ${error.message}`;
                    swapFormError.classList.remove('hidden');
                }
                
                container.innerHTML = `
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <p class="text-red-600">Gagal memuat data barang.</p>
                    </div>
                `;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Terjadi kesalahan';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const swapForm = document.getElementById('swapForm');
            const swapFormError = document.getElementById('swapFormError');
            const swapFormErrorMessage = document.getElementById('swapFormErrorMessage');

            if (swapForm) {
                swapForm.addEventListener('submit', async function(e) {
                    e.preventDefault(); // Prevent default form submission

                    // Hide previous errors
                    if (swapFormError) swapFormError.classList.add('hidden');

                    // Validation
                    const selectedItem = document.querySelector('input[name="offered_item_id"]:checked');
                    if (!selectedItem) {
                        if (swapFormError && swapFormErrorMessage) {
                            swapFormErrorMessage.textContent = 'Silakan pilih barang yang akan Anda tawarkan terlebih dahulu!';
                            swapFormError.classList.remove('hidden');
                        }
                        alert('Silakan pilih barang yang akan Anda tawarkan terlebih dahulu!'); // Fallback alert
                        return false;
                    }

                    if (selectedItem.value === 'other') {
                        const otherItemNameInput = document.getElementById('otherItemName');
                        const otherItemName = otherItemNameInput.value.trim();
                        if (!otherItemName) {
                             if (swapFormError && swapFormErrorMessage) {
                                swapFormErrorMessage.textContent = 'Silakan masukkan nama barang yang akan Anda tawarkan!';
                                swapFormError.classList.remove('hidden');
                            }
                            alert('Silakan masukkan nama barang yang akan Anda tawarkan!'); // Fallback alert
                            return false;
                        }
                    }
                    
                    // Prepare form data
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    const originalDisabled = submitBtn.disabled; // Store original disabled state
                    
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                    submitBtn.disabled = true;
                    
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const response = await fetch('{{ route("swaps.store") }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Success
                            // Display success message before closing modal
                            alert('Penukaran berhasil diajukan!');
                            closeSwapModal(); // Close modal

                            // Reload page or list swap after a short delay to ensure alert is dismissed
                            setTimeout(() => {
                                window.location.reload();
                            }, 500); // Adjust delay as needed
                        } else {
                            // Handle validation errors or other server errors
                            if (swapFormError && swapFormErrorMessage) {
                                if (result.errors) {
                                    // Display specific validation errors
                                    let errorMessages = Object.values(result.errors).flat().join('<br>');
                                    swapFormErrorMessage.innerHTML = errorMessages;
                                } else if (result.message) {
                                    // Display a general error message
                                    swapFormErrorMessage.textContent = result.message;
                                } else {
                                    swapFormErrorMessage.textContent = 'Terjadi kesalahan saat mengajukan penukaran. Silakan coba lagi.';
                                }
                                swapFormError.classList.remove('hidden');
                            }
                            console.error('Submission error:', result);
                            // Fallback alert if error div is not visible or for immediate feedback
                            if (swapFormError.classList.contains('hidden')) {
                                alert('Gagal mengajukan penukaran. Silakan periksa pesan error di bawah atau coba lagi.');
                            }
                        }
                    } catch (error) {
                        console.error('Network or unexpected error:', error);
                        if (swapFormError && swapFormErrorMessage) {
                            swapFormErrorMessage.textContent = 'Terjadi kesalahan jaringan atau tak terduga. Silakan coba lagi.';
                            swapFormError.classList.remove('hidden');
                        }
                         alert('Terjadi kesalahan jaringan atau tak terduga. Silakan coba lagi.'); // Fallback alert
                    } finally {
                        // Restore button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = originalDisabled;
                    }
                });
            }
            
            // Close modal when clicking outside untuk swap modal
            const swapModal = document.getElementById('swapModal');
            if (swapModal) {
                swapModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeSwapModal();
                    }
                });
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.name === 'offered_item_id') {
                const otherItemInputContainer = document.getElementById('otherItemInputContainer');
                const otherItemName = document.getElementById('otherItemName');
                
                if (e.target.value === 'other') {
                    otherItemInputContainer.classList.remove('hidden');
                    if(otherItemName) otherItemName.focus();
                    if(otherItemName) otherItemName.required = true;
                } else {
                    otherItemInputContainer.classList.add('hidden');
                    if(otherItemName) otherItemName.required = false;
                    if(otherItemName) otherItemName.value = '';
                }
            }
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
