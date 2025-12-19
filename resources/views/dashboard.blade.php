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
        
        .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
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
        
        .notification-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ef4444;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        .avatar-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .status-available {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
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
            border-left: 4px solid #10b981;
        }
        
        .logo-text {
            background: linear-gradient(to right, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex">
    <!-- Sidebar Navigation -->
    <aside class="sidebar w-64 min-h-screen flex flex-col">
        <!-- Logo -->
        <div class="p-6 border-b">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full avatar-gradient flex items-center justify-center mr-3">
                    <i class="fas fa-exchange-alt text-white text-lg"></i>
                </div>
                <h1 class="text-2xl font-bold logo-text">TukarYuk</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1">Platform tukar barang terpercaya</p>
        </div>
        
        <!-- User Profile -->
        <div class="p-6 border-b">
            <div class="flex items-center">
                <div class="relative">
                    <div class="w-14 h-14 rounded-full avatar-gradient flex items-center justify-center text-white text-xl font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                <div class="ml-4">
                    <h2 class="font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    <div class="flex items-center mt-1">
                        <i class="fas fa-map-marker-alt text-green-500 text-xs mr-1"></i>
                        <span class="text-xs text-gray-600">Jakarta Selatan</span>
                    </div>
                </div>
            </div>
            
            <!-- User Stats -->
            <div class="grid grid-cols-3 gap-2 mt-4">
                <div class="text-center p-2 bg-gray-50 rounded">
                    <p class="font-bold text-gray-800">12</p>
                    <p class="text-xs text-gray-500">Barang</p>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded">
                    <p class="font-bold text-gray-800">5</p>
                    <p class="text-xs text-gray-500">Tukar</p>
                </div>
                <div class="text-center p-2 bg-gray-50 rounded">
                    <p class="font-bold text-gray-800">4.8</p>
                    <p class="text-xs text-gray-500">Rating</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-grow p-4">
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg active-nav">
                        <i class="fas fa-home text-green-600 mr-3"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-box text-gray-500 mr-3"></i>
                        <span class="font-medium">Barang Saya</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-exchange-alt text-gray-500 mr-3"></i>
                        <span class="font-medium">Penukaran</span>
                        <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">3</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-comments text-gray-500 mr-3"></i>
                        <span class="font-medium">Percakapan</span>
                        <span class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">5</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-heart text-gray-500 mr-3"></i>
                        <span class="font-medium">Favorit</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-cog text-gray-500 mr-3"></i>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                </li>
            </ul>
            
            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3 px-3">Aksi Cepat</h3>
                <button class="w-full flex items-center justify-center p-3 bg-green-50 text-green-700 rounded-lg mb-2 hover:bg-green-100 transition">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="font-medium">Tambah Barang</span>
                </button>
                <button class="w-full flex items-center justify-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-search mr-2"></i>
                    <span class="font-medium">Cari Barang</span>
                </button>
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
    </aside>
    
    <!-- Main Content -->
    <main class="flex-grow p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Selamat datang, <span class="logo-text">{{ auth()->user()->name }}</span>!</h1>
                <p class="text-gray-600">Apa yang ingin Anda lakukan hari ini?</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                    <div class="notification-dot"></div>
                </div>
                
                <!-- Search -->
                <div class="relative">
                    <input type="text" placeholder="Cari barang..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-xl card-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100 mr-4">
                        <i class="fas fa-box text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Barang</p>
                        <h3 class="text-2xl font-bold text-gray-800">12</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-green-600 font-medium">+2 minggu ini</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-xl card-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100 mr-4">
                        <i class="fas fa-exchange-alt text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Penukaran Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800">3</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-blue-600 font-medium">1 menunggu konfirmasi</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-xl card-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-yellow-100 mr-4">
                        <i class="fas fa-comments text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Percakapan</p>
                        <h3 class="text-2xl font-bold text-gray-800">5</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <span class="text-yellow-600 font-medium">2 belum dibaca</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-xl card-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-purple-100 mr-4">
                        <i class="fas fa-star text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rating Anda</p>
                        <h3 class="text-2xl font-bold text-gray-800">4.8</h3>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="ml-2 text-gray-500">(24 ulasan)</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity & Items -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Items -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Barang Terbaru Anda</h2>
                    <a href="#" class="text-green-600 hover:text-green-800 font-medium text-sm">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-100 to-emerald-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-book text-green-600 text-2xl"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-800">Koleksi Novel Lengkap</h3>
                            <div class="flex items-center mt-1">
                                <span class="px-2 py-1 text-xs rounded-full status-available mr-2">Tersedia</span>
                                <span class="text-xs text-gray-500">Tukar</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">2 hari lalu</p>
                            <div class="flex text-yellow-400 text-xs mt-1">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-100 to-cyan-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-headphones text-blue-600 text-2xl"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-800">Headphone Bluetooth</h3>
                            <div class="flex items-center mt-1">
                                <span class="px-2 py-1 text-xs rounded-full status-pending mr-2">Dalam Penukaran</span>
                                <span class="text-xs text-gray-500">Gratis</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">1 minggu lalu</p>
                            <button class="mt-1 text-xs bg-green-600 text-white px-3 py-1 rounded-full">Lacak</button>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-100 to-pink-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-tshirt text-purple-600 text-2xl"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-800">Jaket Kulit Premium</h3>
                            <div class="flex items-center mt-1">
                                <span class="px-2 py-1 text-xs rounded-full status-completed mr-2">Selesai</span>
                                <span class="text-xs text-gray-500">Tukar</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">2 minggu lalu</p>
                            <div class="flex text-green-600 text-xs mt-1">
                                <i class="fas fa-check-circle mr-1"></i>
                                <span>Berhasil</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="w-full mt-6 flex items-center justify-center p-3 border border-dashed border-gray-300 rounded-lg text-gray-500 hover:text-green-600 hover:border-green-300 transition">
                    <i class="fas fa-plus mr-2"></i>
                    <span>Tambah Barang Baru</span>
                </button>
            </div>
            
            <!-- Recent Activity -->
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Aktivitas Terkini</h2>
                    <a href="#" class="text-green-600 hover:text-green-800 font-medium text-sm">Lihat Riwayat <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-exchange-alt text-green-600"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-800">Penukaran baru</h3>
                            <p class="text-sm text-gray-600">Anda mendapat tawaran untuk <span class="font-medium">Headphone Bluetooth</span></p>
                            <p class="text-xs text-gray-500 mt-1">2 jam yang lalu</p>
                        </div>
                        <button class="ml-4 text-sm bg-green-600 text-white px-4 py-1 rounded-full hover:bg-green-700 transition">Lihat</button>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-comment text-blue-600"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-800">Pesan baru</h3>
                            <p class="text-sm text-gray-600">Rudi mengirim pesan tentang <span class="font-medium">Koleksi Novel Lengkap</span></p>
                            <p class="text-xs text-gray-500 mt-1">5 jam yang lalu</p>
                        </div>
                        <button class="ml-4 text-sm bg-blue-600 text-white px-4 py-1 rounded-full hover:bg-blue-700 transition">Balas</button>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-star text-yellow-600"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-800">Rating baru</h3>
                            <p class="text-sm text-gray-600">Sinta memberi rating 5 bintang untuk penukaran <span class="font-medium">Jaket Kulit</span></p>
                            <p class="text-xs text-gray-500 mt-1">1 hari yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-check-circle text-purple-600"></i>
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-medium text-gray-800">Penukaran selesai</h3>
                            <p class="text-sm text-gray-600">Penukaran <span class="font-medium">Sepatu Sneakers</span> telah berhasil diselesaikan</p>
                            <p class="text-xs text-gray-500 mt-1">3 hari yang lalu</p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="font-medium text-gray-800 mb-4">Statistik Singkat</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-700">85%</p>
                            <p class="text-xs text-green-600">Tukar Berhasil</p>
                        </div>
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-700">12</p>
                            <p class="text-xs text-blue-600">Pengguna Tertukar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>&copy; 2023 TukarYuk. Semua hak dilindungi. • <a href="#" class="text-green-600 hover:text-green-800">Bantuan</a> • <a href="#" class="text-green-600 hover:text-green-800">Kebijakan Privasi</a> • <a href="#" class="text-green-600 hover:text-green-800">Syarat Layanan</a></p>
        </div>
    </main>
    
    <script>
        // Dashboard interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate loading data
            setTimeout(() => {
                const notificationDot = document.querySelector('.notification-dot');
                if (notificationDot) {
                    notificationDot.style.animation = "pulse 2s infinite";
                    
                    // Add pulse animation
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes pulse {
                            0% { opacity: 1; }
                            50% { opacity: 0.5; }
                            100% { opacity: 1; }
                        }
                    `;
                    document.head.appendChild(style);
                }
                
                // Update stats with animation
                const stats = document.querySelectorAll('.text-2xl.font-bold.text-gray-800');
                stats.forEach(stat => {
                    const originalValue = stat.textContent;
                    if (!isNaN(originalValue)) {
                        let count = 0;
                        const target = parseInt(originalValue);
                        const increment = target / 30;
                        
                        const timer = setInterval(() => {
                            count += increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(timer);
                            }
                            stat.textContent = Math.floor(count);
                        }, 50);
                    }
                });
            }, 500);
        });
    </script>
</body>
</html>