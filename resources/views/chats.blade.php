<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(isset($swap)) Chat dengan {{ $otherUser->name }} @else Percakapan @endif | TukarYuk</title>
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
        
        /* Line clamp for descriptions */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Chat specific styles */
        .chat-bubble-sender {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 18px 18px 0 18px;
            max-width: 70%;
        }
        
        .chat-bubble-receiver {
            background-color: #e5e7eb;
            color: #374151;
            border-radius: 18px 18px 18px 0;
            max-width: 70%;
        }
        
        /* Scrollbar styling */
        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .chat-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .chat-scroll::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-grid {
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
                    @if($totalUnread > 0)
                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                        <span class="text-xs text-white">{{ $totalUnread }}</span>
                    </div>
                    @endif
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
                    <a href="{{ route('conversations') }}" class="flex items-center p-3 rounded-lg active-nav">
                        <i class="fas fa-comments text-green-600 mr-3"></i>
                        <span class="font-medium">Percakapan</span>
                        @if($totalUnread > 0)
                        <span class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">{{ $totalUnread }}</span>
                        @endif
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
                        <a href="{{ route('swaps.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-exchange-alt text-gray-500 mr-3"></i>
                            <span class="font-medium">Penukaran</span>
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">{{ $stats['active_swaps'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('conversations') }}" class="flex items-center p-3 rounded-lg active-nav">
                            <i class="fas fa-comments text-green-600 mr-3"></i>
                            <span class="font-medium">Percakapan</span>
                            @if($totalUnread > 0)
                            <span class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">{{ $totalUnread }}</span>
                            @endif
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
                    <h1 class="text-2xl font-bold text-gray-800">
                        @if(isset($swap))
                        Chat dengan <span class="logo-text">{{ $otherUser->name }}</span>
                        @else
                        Percakapan
                        @endif
                    </h1>
                    <p class="text-gray-600">
                        @if(isset($swap))
                        Diskusikan penukaran barang dengan {{ $otherUser->name }}
                        @else
                        Kelola percakapan dengan partner tukar barang
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search (only in conversation list mode) -->
                    @if(!isset($swap))
                    <div class="relative hidden md:block">
                        <input type="text" placeholder="Cari percakapan..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    @endif
                    
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                            <i class="fas fa-bell text-gray-600"></i>
                        </button>
                        @if($totalUnread > 0)
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                            <span class="text-xs text-white">{{ $totalUnread }}</span>
                        </div>
                        @endif
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

            @if(!isset($swap))
            <!-- ============ MODE DAFTAR PERCAKAPAN ============ -->
            
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-6 mb-6 card-shadow">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Percakapan TukarYuk</h2>
                        <p class="text-gray-600">Chat dengan partner tukar barang Anda</p>
                    </div>
                    <div>
                        @if($totalUnread > 0)
                        <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-lg font-medium">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $totalUnread }} pesan belum dibaca
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Filter Section -->
            <div class="bg-white rounded-xl card-shadow p-5 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" placeholder="Cari percakapan..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Filter -->
                    <div>
                        <select class="w-full md:w-auto px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Semua Percakapan</option>
                            <option value="unread">Belum Dibaca</option>
                            <option value="recent">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- All Conversations -->
            <div class="bg-white rounded-xl card-shadow p-5 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Semua Percakapan</h2>
                        <p class="text-sm text-gray-500 mt-1">Kelola semua percakapan penukaran barang Anda</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700">{{ $swaps->count() }} percakapan</span>
                    </div>
                </div>
                
                @if($swaps->count() > 0)
                <div class="space-y-4">
                    @foreach($swaps as $swapItem)
                    @php
                        $otherUser = (auth()->id() == $swapItem->owner_id) 
                            ? $swapItem->requester 
                            : $swapItem->owner;
                        
                        $lastMessage = $swapItem->latestChat;
                        $unreadCount = $swapItem->chats()->where('receiver_id', auth()->id())->where('is_read', false)->count();
                    @endphp
                    
                    <a href="{{ route('chats.show', $swapItem->id) }}" class="block border border-gray-200 rounded-xl p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 card-shadow">
                        <div class="flex items-start">
                            <!-- User Avatar -->
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($otherUser->name, 0, 1) }}
                                </div>
                                @if($unreadCount > 0)
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                                    <span class="text-xs text-white">{{ $unreadCount }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Conversation Info -->
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $otherUser->name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            Tukar: <span class="font-medium">{{ $swapItem->item->title }}</span> 
                                            ↔ 
                                            <span class="font-medium">{{ $swapItem->offeredItem->title }}</span>
                                        </p>
                                    </div>
                                    @if($lastMessage)
                                    <span class="text-xs text-gray-500 whitespace-nowrap">
                                        {{ $lastMessage->created_at->diffForHumans() }}
                                    </span>
                                    @endif
                                </div>
                                
                                <!-- Last Message Preview -->
                                @if($lastMessage)
                                <div class="flex items-center mt-2">
                                    @if($lastMessage->sender_id == auth()->id())
                                    <span class="text-xs text-green-600 font-medium mr-2">Anda:</span>
                                    @endif
                                    <p class="text-sm text-gray-600 truncate flex-1">
                                        {{ $lastMessage->message }}
                                    </p>
                                </div>
                                @else
                                <p class="text-sm text-gray-500 italic mt-2">
                                    Belum ada pesan. Mulai percakapan sekarang!
                                </p>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comments text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada percakapan</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">
                        Anda belum memiliki percakapan aktif. Mulai dengan menerima tawaran penukaran barang.
                    </p>
                    <a href="{{ route('swaps.index') }}" class="btn-primary text-white font-medium py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg inline-flex items-center">
                        <i class="fas fa-exchange-alt mr-2"></i> Lihat Penawaran
                    </a>
                </div>
                @endif
            </div>
            
            @else
            <!-- ============ MODE CHAT DETAIL ============ -->
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sidebar Daftar Percakapan (Desktop only) -->
                <div class="hidden lg:block lg:col-span-1">
                    <div class="bg-white rounded-xl card-shadow p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800">Percakapan</h2>
                            @if($totalUnread > 0)
                            <span class="text-sm text-red-600 font-medium">{{ $totalUnread }} belum dibaca</span>
                            @endif
                        </div>
                        <div class="relative mb-4">
                            <input type="text" placeholder="Cari percakapan..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                        
                        <div class="space-y-2 max-h-[calc(100vh-300px)] overflow-y-auto">
                            @foreach($swaps as $swapItem)
                            @php
                                $otherUserItem = (auth()->id() == $swapItem->owner_id) 
                                    ? $swapItem->requester 
                                    : $swapItem->owner;
                                
                                $lastMessage = $swapItem->latestChat;
                                $unreadCount = $swapItem->chats()->where('receiver_id', auth()->id())->where('is_read', false)->count();
                                $isActive = $swapItem->id == $swap->id;
                            @endphp
                            
                            <a href="{{ route('chats.show', $swapItem->id) }}" 
                               class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition {{ $isActive ? 'bg-green-50 border-green-200' : '' }}">
                                <div class="flex items-start">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($otherUserItem->name, 0, 1) }}
                                        </div>
                                        @if($unreadCount > 0 && !$isActive)
                                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full flex items-center justify-center">
                                            <span class="text-xs text-white">{{ $unreadCount }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between">
                                            <h3 class="font-medium text-gray-800 truncate">{{ $otherUserItem->name }}</h3>
                                            @if($lastMessage)
                                            <span class="text-xs text-gray-500">
                                                {{ $lastMessage->created_at->format('H:i') }}
                                            </span>
                                            @endif
                                        </div>
                                        
                                        @if($lastMessage)
                                        <p class="text-sm text-gray-600 truncate">
                                            @if($lastMessage->sender_id == auth()->id())
                                            <span class="text-green-600 font-medium">Anda: </span>
                                            @endif
                                            {{ Str::limit($lastMessage->message, 30) }}
                                        </p>
                                        @else
                                        <p class="text-sm text-gray-500 italic">Belum ada pesan</p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Chat Detail Area -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl card-shadow overflow-hidden">
                        <!-- Chat Header -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <a href="{{ route('conversations') }}" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                                        <i class="fas fa-arrow-left text-lg"></i>
                                    </a>
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg mr-3">
                                            {{ substr($otherUser->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h2 class="font-bold text-gray-800 text-lg">{{ $otherUser->name }}</h2>
                                            <p class="text-sm text-gray-600">
                                                Tukar: <strong>{{ $swap->item->title }}</strong> ↔ <strong>{{ $swap->offeredItem->title }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('swaps.show', $swap->id) }}" 
                                       class="px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition font-medium text-sm flex items-center">
                                        <i class="fas fa-exchange-alt mr-2"></i> Detail Tukar
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chat Messages -->
                        <div class="h-[400px] overflow-y-auto p-6 bg-gray-50 chat-scroll" id="chatMessages">
                            @if($chats->count() > 0)
                                @foreach($chats as $chat)
                                <div class="mb-4 {{ $chat->sender_id == auth()->id() ? 'flex justify-end' : '' }}">
                                    <div class="{{ $chat->sender_id == auth()->id() ? 'chat-bubble-sender' : 'chat-bubble-receiver' }} p-4 card-shadow">
                                        @if($chat->sender_id != auth()->id())
                                        <div class="text-xs font-medium mb-2 opacity-90">{{ $chat->sender->name }}</div>
                                        @endif
                                        <div class="mb-2">{{ $chat->message }}</div>
                                        <div class="text-xs opacity-70 text-right">
                                            {{ $chat->created_at->format('H:i') }}
                                            @if($chat->sender_id == auth()->id())
                                            <i class="fas fa-check ml-1 {{ $chat->is_read ? 'text-blue-300' : 'text-gray-300' }}"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-comment-slash text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-lg font-bold text-gray-700 mb-2">Belum ada pesan</p>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                                    Mulai percakapan dengan {{ $otherUser->name }} untuk mendiskusikan penukaran barang
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Chat Input -->
                        <div class="bg-white border-t p-6">
                            <form id="chatForm" class="flex items-center space-x-3">
                                @csrf
                                <input type="hidden" name="swap_id" value="{{ $swap->id }}">
                                
                                <div class="flex-1">
                                    <div class="relative">
                                        <input type="text" 
                                               name="message" 
                                               id="messageInput"
                                               placeholder="Ketik pesan..."
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                               required>
                                        <div class="absolute left-4 top-3.5 text-gray-400">
                                            <i class="fas fa-comment-dots"></i>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                        id="sendButton"
                                        class="px-6 py-3 btn-primary text-white font-medium rounded-lg flex items-center justify-center shadow-md hover:shadow-lg transition">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
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
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', openMobileMenu);
            }
            
            if (closeMobileMenu) {
                closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
            }
            
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', closeMobileMenuFunc);
            }
            
            // Close mobile menu when clicking on a link
            if (mobileMenu) {
                const mobileLinks = mobileMenu.querySelectorAll('a');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', closeMobileMenuFunc);
                });
            }
            
            @if(isset($swap))
            // Chat functionality only for detail mode
            // Auto scroll to bottom of chat
            scrollToBottom();
            
            // Chat form submission
            const chatForm = document.getElementById('chatForm');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            const chatMessages = document.getElementById('chatMessages');
            
            if (chatForm) {
                chatForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const message = messageInput.value.trim();
                    if (!message) return;
                    
                    // Disable input and button
                    messageInput.disabled = true;
                    sendButton.disabled = true;
                    sendButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                    
                    try {
                        const formData = new FormData(this);
                        const response = await fetch('{{ route("chats.store", $swap->id) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            // Add new message to chat
                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'mb-4 flex justify-end';
                            messageDiv.innerHTML = `
                                <div class="chat-bubble-sender p-4 card-shadow">
                                    <div class="mb-2">${message}</div>
                                    <div class="text-xs opacity-70 text-right">
                                        Sekarang
                                        <i class="fas fa-check ml-1 text-gray-300"></i>
                                    </div>
                                </div>
                            `;
                            
                            chatMessages.appendChild(messageDiv);
                            messageInput.value = '';
                            scrollToBottom();
                            
                            // Mark as read
                            fetch('{{ route("chats.read", $swap->id) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                        alert('Gagal mengirim pesan. Silakan coba lagi.');
                    } finally {
                        // Re-enable input and button
                        messageInput.disabled = false;
                        sendButton.disabled = false;
                        sendButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim';
                        messageInput.focus();
                    }
                });
                
                // Auto-refresh messages every 5 seconds
                setInterval(() => {
                    fetchNewMessages();
                }, 5000);
                
                // Handle enter key
                messageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        chatForm.dispatchEvent(new Event('submit'));
                    }
                });
            }
            @endif
        });
        
        @if(isset($swap))
        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }
        
        async function fetchNewMessages() {
            try {
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    // You could implement AJAX to fetch new messages only
                    // For simplicity, reload the page if there are new messages
                    // Or implement a more sophisticated message update
                }
            } catch (error) {
                console.error('Error fetching new messages:', error);
            }
        }
        @endif
        
        // Handle window resize
        window.addEventListener('resize', function() {
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
        
        // Auto-refresh unread count every 30 seconds (for index mode)
        @if(!isset($swap))
        setInterval(() => {
            fetch('{{ route("chats.unread.count") }}')
                .then(response => response.json())
                .then(data => {
                    const unreadElements = document.querySelectorAll('.unread-count');
                    unreadElements.forEach(el => {
                        if (data.count > 0) {
                            el.textContent = data.count;
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    });
                });
        }, 30000);
        @endif
    </script>
</body>
</html>