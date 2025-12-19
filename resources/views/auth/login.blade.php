<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TukarYuk - Platform Tukar Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(to right, #10b981, #059669);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }
        
        .input-focus:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        
        .logo-text {
            background: linear-gradient(to right, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-green-300 rounded-full opacity-20"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-300 rounded-full opacity-20"></div>
        <div class="absolute top-1/2 left-1/3 w-40 h-40 bg-teal-300 rounded-full opacity-15"></div>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl card-shadow overflow-hidden z-10">
        <!-- Header dengan logo -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b border-green-100">
            <div class="flex items-center justify-center mb-2">
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mr-3">
                    <i class="fas fa-exchange-alt text-white text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold logo-text">TukarYuk</h1>
            </div>
            <p class="text-center text-gray-600 text-sm">Platform tukar barang dan berbagi</p>
        </div>
        
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-2 text-gray-800">Masuk ke Akun Anda</h2>
            <p class="text-center text-gray-500 mb-6">Selamat datang kembali! Silakan masuk untuk melanjutkan</p>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 rounded">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle mt-1 mr-3"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-envelope text-green-500 mr-2"></i>Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                               placeholder="nama@email.com"
                               required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-lock text-green-500 mr-2"></i>Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="password" name="password"
                               class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                               placeholder="Masukkan kata sandi"
                               required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Remember & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                    </div>
                    <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500 transition">Lupa kata sandi?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full btn-primary text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <p class="mt-8 text-center text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-500 transition ml-1">
                    Daftar sekarang
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </p>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 py-4 px-6 border-t border-gray-100">
            <p class="text-center text-xs text-gray-500">
                &copy; 2023 TukarYuk. Semua hak dilindungi.
            </p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Form validation feedback
        const emailInput = document.querySelector('input[name="email"]');
        const passwordInput = document.querySelector('input[name="password"]');
        
        emailInput.addEventListener('blur', function() {
            if (this.value && !this.validity.valid) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
        
        passwordInput.addEventListener('blur', function() {
            if (this.value && this.value.length < 6) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    </script>
</body>
</html>