<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | TukarYuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-6">
    <h1 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}</h1>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button class="bg-red-600 text-white px-4 py-2 rounded">
            Logout
        </button>
    </form>
</body>
</html>
