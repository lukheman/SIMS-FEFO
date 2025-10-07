<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Toko Bintang Poleang Timur</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Tailwind CSS CDN -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-br from-teal-500 to-indigo-600 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-3xl">
        <div class="bg-white rounded-xl shadow-2xl p-8 transform transition-all hover:scale-105 duration-300">
            <div class="flex justify-center mb-6">
                <i class="fas fa-user-plus fa-3x text-teal-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-2">Toko Bintang Poleang Timur</h2>
            <p class="text-center text-gray-500 mb-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-800 hover:underline transition duration-200">Login</a>
            </p>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-200 text-green-700 p-4 mb-6 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

<form action="{{ route('signup') }}" method="post">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Role -->
        <div class="mb-5">
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Daftar Sebagai</label>
            <div class="relative">
                <select name="role" id="role" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200 @error('role') border-red-500 @enderror">
                    <option value="{{ \App\Enums\Role::RESELLER }}" {{ old('role') == \App\Enums\Role::RESELLER ? 'selected' : '' }}>Reseller</option>
                    <option value="{{ \App\Enums\Role::KURIR }}" {{ old('role') == \App\Enums\Role::KURIR ? 'selected' : '' }}>Kurir</option>
                </select>
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-vector-square text-gray-400"></i>
                </span>
                @error('role')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Nama -->
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <div class="relative">
                <input type="text" name="name" id="name" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200 @error('name') border-red-500 @enderror" placeholder="Nama Anda" value="{{ old('name') }}">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-user text-gray-400"></i>
                </span>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <div class="relative">
                <input type="email" name="email" id="email" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200 @error('email') border-red-500 @enderror" placeholder="Masukkan email Anda" value="{{ old('email') }}">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-envelope text-gray-400"></i>
                </span>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Telepon -->
        <div class="mb-5">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
            <div class="relative">
                <input type="text" name="phone" id="phone" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200 @error('phone') border-red-500 @enderror" placeholder="Nomor Telepon" value="{{ old('phone') }}">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-phone text-gray-400"></i>
                </span>
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <input type="password" name="password" id="password" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200 @error('password') border-red-500 @enderror" placeholder="Masukkan password">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-lock text-gray-400"></i>
                </span>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Ulangi Password -->
        <div class="mb-5">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
            <div class="relative">
                <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200" placeholder="Ulangi password">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-lock text-gray-400"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Tombol -->
    <div class="flex justify-end mt-6">
        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-300">Daftar</button>
    </div>
</form>
        </div>
    </div>

    <!-- REQUIRED SCRIPTS -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>
