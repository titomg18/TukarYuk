<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | TukarYuk</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-center mb-6">Masuk ke TukarYuk</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full mt-1 px-3 py-2 border rounded focus:outline-none focus:ring"
                   required autofocus>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password"
                   class="w-full mt-1 px-3 py-2 border rounded focus:outline-none focus:ring"
                   required>
        </div>

        <!-- Remember -->
        <div class="flex items-center mb-4">
            <input type="checkbox" name="remember" class="mr-2">
            <span class="text-sm text-gray-600">Ingat saya</span>
        </div>

        <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
            Login
        </button>
    </form>

    <p class="mt-4 text-center text-sm">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-green-600 hover:underline">
            Daftar sekarang
        </a>
    </p>
</div>

</body>
</html>
