<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | TukarYuk - Platform Tukar Barang</title>
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
        
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            transition: width 0.5s ease;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-green-300 rounded-full opacity-20"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-300 rounded-full opacity-20"></div>
        <div class="absolute top-1/3 right-1/4 w-32 h-32 bg-teal-300 rounded-full opacity-15"></div>
    </div>

    <div class="w-full max-w-lg bg-white rounded-2xl card-shadow overflow-hidden z-10">
        <!-- Header dengan logo -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b border-green-100">
            <div class="flex items-center justify-center mb-2">
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mr-3">
                    <i class="fas fa-exchange-alt text-white text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold logo-text">TukarYuk</h1>
            </div>
            <p class="text-center text-gray-600 text-sm">Bergabunglah dengan komunitas tukar barang kami</p>
        </div>
        
        <!-- Progress bar -->
        <div class="px-6 pt-6">
            <div class="mb-2 flex justify-between text-sm">
                <span class="font-medium text-green-600">Langkah Pendaftaran</span>
                <span class="text-gray-500">1/2</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="progress-bar bg-green-600 rounded-full w-1/2"></div>
            </div>
        </div>
        
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-2 text-gray-800">Daftar Akun Baru</h2>
            <p class="text-center text-gray-500 mb-6">Isi data diri Anda untuk mulai bertukar barang</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-user text-green-500 mr-2"></i>Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                            placeholder="Masukkan nama lengkap"
                            required>
                    </div>
                    @error('name')
                        <p class="mt-1 text-red-500 text-sm flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-envelope text-green-500 mr-2"></i>Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                            placeholder="nama@email.com"
                            required>
                    </div>
                    @error('email')
                        <p class="mt-1 text-red-500 text-sm flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
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
                        <input type="password" name="password" id="password"
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                            placeholder="Minimal 8 karakter"
                            required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="flex items-center mb-1">
                            <div id="password-strength-bar" class="h-2 rounded-full flex-grow mr-2 bg-gray-200 overflow-hidden">
                                <div id="password-strength" class="h-full w-0 transition-all duration-300"></div>
                            </div>
                            <span id="password-strength-text" class="text-xs font-medium">Lemah</span>
                        </div>
                        <ul class="text-xs text-gray-500 space-y-1">
                            <li id="length-check" class="flex items-center">
                                <i class="fas fa-times text-red-400 mr-1"></i> Minimal 8 karakter
                            </li>
                            <li id="lowercase-check" class="flex items-center">
                                <i class="fas fa-times text-red-400 mr-1"></i> Mengandung huruf kecil
                            </li>
                            <li id="uppercase-check" class="flex items-center">
                                <i class="fas fa-times text-red-400 mr-1"></i> Mengandung huruf besar
                            </li>
                            <li id="number-check" class="flex items-center">
                                <i class="fas fa-times text-red-400 mr-1"></i> Mengandung angka
                            </li>
                        </ul>
                    </div>
                    @error('password')
                        <p class="mt-1 text-red-500 text-sm flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-lock text-green-500 mr-2"></i>Konfirmasi Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="confirm-password"
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none"
                            placeholder="Ulangi kata sandi"
                            required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none toggle-confirm-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div id="password-match" class="mt-1 text-sm hidden">
                        <i class="fas fa-check text-green-500 mr-1"></i>
                        <span>Kata sandi cocok</span>
                    </div>
                    <div id="password-mismatch" class="mt-1 text-sm hidden">
                        <i class="fas fa-times text-red-500 mr-1"></i>
                        <span>Kata sandi tidak cocok</span>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input type="checkbox" name="terms" id="terms" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1" required>
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        Saya menyetujui <a href="#" class="text-green-600 hover:text-green-500 font-medium">Syarat & Ketentuan</a> dan <a href="#" class="text-green-600 hover:text-green-500 font-medium">Kebijakan Privasi</a> TukarYuk
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                        class="w-full btn-primary text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </button>
            </form>

            <p class="mt-8 text-center text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:text-green-500 transition ml-1">
                    Masuk sekarang
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
        document.querySelectorAll('.toggle-password, .toggle-confirm-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.relative').querySelector('input');
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        const strengthBar = document.getElementById('password-strength');
        const strengthText = document.getElementById('password-strength-text');
        const passwordMatch = document.getElementById('password-match');
        const passwordMismatch = document.getElementById('password-mismatch');
        
        // Password requirement check elements
        const lengthCheck = document.getElementById('length-check');
        const lowercaseCheck = document.getElementById('lowercase-check');
        const uppercaseCheck = document.getElementById('uppercase-check');
        const numberCheck = document.getElementById('number-check');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            // Reset checks
            lengthCheck.innerHTML = '<i class="fas fa-times text-red-400 mr-1"></i> Minimal 8 karakter';
            lowercaseCheck.innerHTML = '<i class="fas fa-times text-red-400 mr-1"></i> Mengandung huruf kecil';
            uppercaseCheck.innerHTML = '<i class="fas fa-times text-red-400 mr-1"></i> Mengandung huruf besar';
            numberCheck.innerHTML = '<i class="fas fa-times text-red-400 mr-1"></i> Mengandung angka';
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) {
                strength += 25;
                lengthCheck.innerHTML = '<i class="fas fa-check text-green-500 mr-1"></i> Minimal 8 karakter';
            }
            
            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength += 25;
                lowercaseCheck.innerHTML = '<i class="fas fa-check text-green-500 mr-1"></i> Mengandung huruf kecil';
            }
            
            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength += 25;
                uppercaseCheck.innerHTML = '<i class="fas fa-check text-green-500 mr-1"></i> Mengandung huruf besar';
            }
            
            // Number check
            if (/[0-9]/.test(password)) {
                strength += 25;
                numberCheck.innerHTML = '<i class="fas fa-check text-green-500 mr-1"></i> Mengandung angka';
            }
            
            // Update strength bar and text
            strengthBar.style.width = strength + '%';
            
            if (strength < 50) {
                strengthBar.style.backgroundColor = '#ef4444';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs font-medium text-red-500';
            } else if (strength < 75) {
                strengthBar.style.backgroundColor = '#f59e0b';
                strengthText.textContent = 'Cukup';
                strengthText.className = 'text-xs font-medium text-yellow-500';
            } else {
                strengthBar.style.backgroundColor = '#10b981';
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-xs font-medium text-green-500';
            }
            
            // Check password confirmation
            checkPasswordMatch();
        });
        
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword.length === 0) {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.add('hidden');
                return;
            }
            
            if (password === confirmPassword) {
                passwordMatch.classList.remove('hidden');
                passwordMismatch.classList.add('hidden');
                confirmPasswordInput.classList.remove('border-red-300');
                confirmPasswordInput.classList.add('border-green-300');
            } else {
                passwordMatch.classList.add('hidden');
                passwordMismatch.classList.remove('hidden');
                confirmPasswordInput.classList.remove('border-green-300');
                confirmPasswordInput.classList.add('border-red-300');
            }
        }
        
        // Form validation before submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Kata sandi tidak cocok. Silakan periksa kembali.');
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Anda harus menyetujui Syarat & Ketentuan untuk mendaftar.');
                return;
            }
            
            // Update progress bar on form submit
            document.querySelector('.progress-bar').style.width = '100%';
        });
    </script>
</body>
</html>