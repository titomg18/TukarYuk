<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swap System | TukarYuk - Platform Tukar Barang</title>
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
        
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-accepted { background-color: #d1fae5; color: #065f46; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        .status-completed { background-color: #dbeafe; color: #1e40af; }
        .status-cancelled { background-color: #e5e7eb; color: #374151; }
        
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
        
        /* Tab styles */
        .tab-button {
            position: relative;
            padding-bottom: 8px;
        }
        
        .tab-button.active {
            color: #059669;
        }
        
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #059669;
        }
    </style>
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
                        <span class="text-xs text-white">{{ $incomingSwaps->where('status', 'pending')->count() }}</span>
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
                    <a href="{{ route('swaps.index') }}" class="flex items-center p-3 rounded-lg active-nav">
                        <i class="fas fa-exchange-alt text-green-600 mr-3"></i>
                        <span class="font-medium">Penukaran</span>
                        <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">{{ $incomingSwaps->where('status', 'pending')->count() }}</span>
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
                        <a href="{{ route('swaps.index') }}" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-exchange-alt text-green-600 mr-3"></i>
                            <span class="font-medium">Penukaran</span>
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">{{ $incomingSwaps->where('status', 'pending')->count() }}</span>
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
                    <h1 class="text-2xl font-bold text-gray-800">Penukaran</h1> <!-- Judul header diperbaiki -->
                    <p class="text-gray-600">Kelola penawaran tukar barang Anda</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Cari barang untuk ditukar..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                            <i class="fas fa-bell text-gray-600"></i>
                        </button>
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                            <span class="text-xs text-white">{{ $incomingSwaps->where('status', 'pending')->count() }}</span>
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

            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 mb-6 card-shadow">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Swap / Share System</h2>
                        <p class="text-gray-600 mb-4 md:mb-0">Kelola semua penawaran tukar barang Anda di satu tempat</p>
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
            
            <!-- Tabs Navigation -->
            <div class="mb-6 bg-white rounded-xl card-shadow">
                <div class="flex border-b border-gray-200">
                    <button id="tab-incoming" class="tab-button flex-1 py-4 px-6 font-medium text-gray-500 hover:text-green-500 transition active">
                        Penawaran Masuk
                        @if($incomingSwaps->where('status', 'pending')->count() > 0)
                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">
                            {{ $incomingSwaps->where('status', 'pending')->count() }}
                        </span>
                        @endif
                    </button>
                    <button id="tab-outgoing" class="tab-button flex-1 py-4 px-6 font-medium text-gray-500 hover:text-green-500 transition">
                        Penawaran Keluar
                        @if($outgoingSwaps->where('status', 'pending')->count() > 0)
                        <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">
                            {{ $outgoingSwaps->where('status', 'pending')->count() }}
                        </span>
                        @endif
                    </button>
                    <button id="tab-history" class="tab-button flex-1 py-4 px-6 font-medium text-gray-500 hover:text-green-500 transition">
                        Riwayat Swap
                    </button>
                </div>
                
                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Tab 1: Penawaran Masuk -->
                    <div id="content-incoming" class="tab-content">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Penawaran Masuk</h3>
                            <p class="text-sm text-gray-500">{{ $incomingSwaps->count() }} total penawaran</p>
                        </div>
                        
                        @if($incomingSwaps->count() > 0)
                        <div class="space-y-4">
                            @foreach($incomingSwaps as $swap)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="flex-1 mb-4 md:mb-0">
                                        <!-- User Info -->
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                                {{ substr($swap->requester->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-800">{{ $swap->requester->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $swap->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Swap Details -->
                                        <div class="ml-13">
                                            <div class="flex items-center mb-2">
                                                <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                                                <span class="text-gray-700">Ingin menukar <strong>"{{ $swap->item->title }}"</strong></span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                                                <span class="text-gray-700">Dengan <strong>"{{ $swap->offeredItem->title }}"</strong></span>
                                            </div>
                                            
                                            @if($swap->message)
                                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                <p class="text-sm text-gray-600"><i class="fas fa-comment mr-2"></i>{{ $swap->message }}</p>
                                            </div>
                                            @endif
                                            
                                            <!-- Type Badge -->
                                            <div class="mt-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs 
                                                    @if($swap->type == 'swap') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    <i class="fas fa-{{ $swap->type == 'swap' ? 'exchange-alt' : 'gift' }} mr-1"></i>
                                                    {{ $swap->type == 'swap' ? 'Tukar Barang' : 'Gratis/Donasi' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end">
                                        <!-- Status Badge -->
                                        <span class="px-3 py-1 text-xs rounded-full mb-3 
                                            @if($swap->status == 'pending') status-pending
                                            @elseif($swap->status == 'accepted') status-accepted
                                            @elseif($swap->status == 'rejected') status-rejected
                                            @elseif($swap->status == 'completed') status-completed
                                            @else status-cancelled @endif">
                                            {{ ucfirst($swap->status) }}
                                        </span>
                                        
                                        <!-- Action Buttons -->
                                        @if($swap->status == 'pending')
                                        <div class="flex space-x-2">
                                            <form action="{{ route('swaps.accept', $swap->id) }}" method="POST"> <!-- Diperbaiki: route('swaps.accept') -->
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                                    <i class="fas fa-check mr-1"></i> Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('swaps.reject', $swap->id) }}" method="POST"> <!-- Diperbaiki: route('swaps.reject') -->
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                                    <i class="fas fa-times mr-1"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                        @elseif($swap->status == 'accepted')
                                        <div class="flex space-x-2">
                                            <form action="{{ route('swaps.complete', $swap->id) }}" method="POST"> <!-- Diperbaiki: route('swaps.complete') -->
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                                    <i class="fas fa-check-double mr-1"></i> Tandai Selesai
                                                </button>
                                            </form>
                                            <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                                <i class="fas fa-comment mr-1"></i> Chat
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exchange-alt text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg mb-2">Belum ada penawaran masuk</p>
                            <p class="text-gray-400">Penawaran dari pengguna lain akan muncul di sini</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Tab 2: Penawaran Keluar -->
                    <div id="content-outgoing" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Penawaran Keluar</h3>
                            <p class="text-sm text-gray-500">{{ $outgoingSwaps->count() }} total penawaran</p>
                        </div>
                        
                        @if($outgoingSwaps->count() > 0)
                        <div class="space-y-4">
                            @foreach($outgoingSwaps as $swap)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="flex-1 mb-4 md:mb-0">
                                        <!-- User Info -->
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold mr-3">
                                                {{ substr($swap->owner->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-800">{{ $swap->owner->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $swap->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Swap Details -->
                                        <div class="ml-13">
                                            <div class="flex items-center mb-2">
                                                <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                                                <span class="text-gray-700">Anda menawarkan <strong>"{{ $swap->offeredItem->title }}"</strong></span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                                                <span class="text-gray-700">Untuk <strong>"{{ $swap->item->title }}"</strong></span>
                                            </div>
                                            
                                            <!-- Type Badge -->
                                            <div class="mt-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs 
                                                    @if($swap->type == 'swap') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    <i class="fas fa-{{ $swap->type == 'swap' ? 'exchange-alt' : 'gift' }} mr-1"></i>
                                                    {{ $swap->type == 'swap' ? 'Tukar Barang' : 'Gratis/Donasi' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end">
                                        <!-- Status Badge -->
                                        <span class="px-3 py-1 text-xs rounded-full mb-3 
                                            @if($swap->status == 'pending') status-pending
                                            @elseif($swap->status == 'accepted') status-accepted
                                            @elseif($swap->status == 'rejected') status-rejected
                                            @elseif($swap->status == 'completed') status-completed
                                            @else status-cancelled @endif">
                                            {{ ucfirst($swap->status) }}
                                        </span>
                                        
                                        <!-- Action Buttons -->
                                        @if($swap->status == 'pending')
                                        <form action="{{ route('swaps.cancel', $swap->id) }}" method="POST"> <!-- Diperbaiki: route('swaps.cancel') -->
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                                <i class="fas fa-times mr-1"></i> Batalkan
                                            </button>
                                        </form>
                                        @elseif($swap->status == 'accepted')
                                        <div class="flex space-x-2">
                                            <form action="{{ route('swaps.complete', $swap->id) }}" method="POST"> <!-- Diperbaiki: route('swaps.complete') -->
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                                    <i class="fas fa-check-double mr-1"></i> Tandai Selesai
                                                </button>
                                            </form>
                                            <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                                <i class="fas fa-comment mr-1"></i> Chat
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-paper-plane text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg mb-2">Belum ada penawaran keluar</p>
                            <p class="text-gray-400 mb-4">Mulai ajukan penukaran dengan barang orang lain</p>
                            <a href="{{ route('dashboard') }}" class="text-green-600 hover:text-green-800 font-medium">
                                <i class="fas fa-search mr-1"></i> Jelajahi Barang
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Tab 3: Riwayat Swap -->
                    <div id="content-history" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Riwayat Swap</h3>
                            <p class="text-sm text-gray-500">{{ $completedSwaps->count() }} swap selesai</p>
                        </div>
                        
                        @if($completedSwaps->count() > 0)
                        <div class="space-y-4">
                            @foreach($completedSwaps as $swap)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center text-white font-bold mr-4 flex-shrink-0">
                                        {{ $swap->requester_id == auth()->id() ? 
                                            substr($swap->owner->name, 0, 1) : 
                                            substr($swap->requester->name, 0, 1) }}
                                    </div>
                                    
                                    <div class="flex-1">
                                        <!-- Header -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-medium text-gray-800">
                                                    @if($swap->requester_id == auth()->id())
                                                        Anda menukar dengan {{ $swap->owner->name }}
                                                    @else
                                                        {{ $swap->requester->name }} menukar dengan Anda
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-500">{{ $swap->completed_at->diffForHumans() }}</p>
                                            </div>
                                            <span class="px-3 py-1 text-xs rounded-full status-completed">
                                                <i class="fas fa-check mr-1"></i>Selesai
                                            </span>
                                        </div>
                                        
                                        <!-- Items Summary -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div class="p-3 bg-green-50 rounded-lg">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Anda Mendapatkan:</p>
                                                <p class="font-medium text-green-700">
                                                    @if($swap->requester_id == auth()->id())
                                                        {{ $swap->item->title }}
                                                    @else
                                                        {{ $swap->offeredItem->title }}
                                                    @endif
                                                </p>
                                            </div>
                                            
                                            <div class="p-3 bg-blue-50 rounded-lg">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Anda Memberikan:</p>
                                                <p class="font-medium text-blue-700">
                                                    @if($swap->requester_id == auth()->id())
                                                        {{ $swap->offeredItem->title }}
                                                    @else
                                                        {{ $swap->item->title }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Type Badge -->
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs 
                                                @if($swap->type == 'swap') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">
                                                <i class="fas fa-{{ $swap->type == 'swap' ? 'exchange-alt' : 'gift' }} mr-1"></i>
                                                {{ $swap->type == 'swap' ? 'Tukar Barang' : 'Gratis/Donasi' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg mb-2">Belum ada riwayat swap</p>
                            <p class="text-gray-400">Swap yang telah selesai akan muncul di sini</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Info Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Jenis Listing -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Jenis Listing</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 border border-yellow-200 rounded-lg hover:bg-yellow-50 transition">
                            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-exchange-alt text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Tukar Barang</h4>
                                <p class="text-sm text-gray-600">Tukar barang Anda dengan barang milik pengguna lain</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 border border-green-200 rounded-lg hover:bg-green-50 transition">
                            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-gift text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Gratis / Donasi</h4>
                                <p class="text-sm text-gray-600">Berikan barang Anda secara gratis kepada yang membutuhkan</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Proses Swap -->
                <div class="bg-white rounded-xl card-shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Proses Swap</h3>
                    <ol class="space-y-3">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-sm mr-3 mt-1">1</span>
                            <div>
                                <p class="font-medium text-gray-800">Ajukan Penukaran</p>
                                <p class="text-sm text-gray-600">Pilih barang yang ingin Anda tukar dan ajukan penawaran</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-sm mr-3 mt-1">2</span>
                            <div>
                                <p class="font-medium text-gray-800">Terima / Tolak Tawaran</p>
                                <p class="text-sm text-gray-600">Pemilik barang dapat menerima atau menolak tawaran Anda</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-sm mr-3 mt-1">3</span>
                            <div>
                                <p class="font-medium text-gray-800">Koordinasi Penukaran</p>
                                <p class="text-sm text-gray-600">Atur waktu dan lokasi untuk bertukar barang</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-sm mr-3 mt-1">4</span>
                            <div>
                                <p class="font-medium text-gray-800">Tandai Selesai</p>
                                <p class="text-sm text-gray-600">Setelah bertukar, tandai swap sebagai selesai</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-6 pt-5 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} TukarYuk. Semua hak dilindungi. • 
                   <a href="#" class="text-green-600 hover:text-green-800">Bantuan</a> • 
                   <a href="#" class="text-green-600 hover:text-green-800">Kebijakan Privasi</a> • 
                   <a href="#" class="text-green-600 hover:text-green-800">Syarat Layanan</a>
                </p>
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
                <form method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data" id="addItemForm"> <!-- Diperbaiki: route('barang.store') -->
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
        
        // Modal functions
        function openAddItemModal() {
            document.getElementById('addItemModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeAddItemModal() {
            document.getElementById('addItemModal').classList.add('hidden');
            document.body.style.overflow = '';
            // Reset form
            document.getElementById('addItemForm').reset();
            document.getElementById('fileList').innerHTML = '';
        }
        
        // Close modal when clicking outside
        document.getElementById('addItemModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddItemModal();
            }
        });
        
        // Form submission handling
        document.getElementById('addItemForm').addEventListener('submit', function(e) {
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;
            
            // Submit form
            // The form will be submitted normally, and the page will refresh with the new item
        });
        
        // Tab Switching Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');
            
            // Show first tab by default
            contents[0].classList.remove('hidden');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.id;
                    
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active');
                        t.classList.remove('text-green-500');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    this.classList.add('text-green-500');
                    
                    // Hide all contents
                    contents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show selected content
                    const contentId = 'content-' + tabId.split('-')[1];
                    document.getElementById(contentId).classList.remove('hidden');
                });
            });
            
            // Form submission confirmation
            document.querySelectorAll('form[action*="accept"], form[action*="reject"], form[action*="cancel"]').forEach(form => {
                form.addEventListener('submit', (e) => {
                    const action = form.getAttribute('action');
                    let message = '';
                    
                    if (action.includes('accept')) {
                        message = 'Apakah Anda yakin ingin menerima tawaran ini?';
                    } else if (action.includes('reject')) {
                        message = 'Apakah Anda yakin ingin menolak tawaran ini?';
                    } else if (action.includes('cancel')) {
                        message = 'Apakah Anda yakin ingin membatalkan penukaran ini?';
                    }
                    
                    if (message && !confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            // Close mobile menu on resize to desktop
            if (window.innerWidth >= 1024) {
                const mobileMenu = document.getElementById('mobileMenu');
                const mobileOverlay = document.getElementById('mobileOverlay');
                
                mobileMenu.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>