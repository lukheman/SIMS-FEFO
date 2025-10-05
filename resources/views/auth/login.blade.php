<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Toko Bintang Poleang Timur</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Tailwind CSS CDN -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gradient-to-br from-teal-500 to-indigo-600 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-2xl p-8 transform transition-all hover:scale-105 duration-300">
            <div class="flex justify-center mb-6">
                <i class="fas fa-store fa-3x text-teal-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-2">Toko Bintang Poleang Timur</h2>
            <p class="text-center text-gray-500 mb-6">Masuk ke akun Anda</p>

            @if (flash()->message)
            <div class="alert mb-4 p-4 rounded-lg text-sm
                @if(flash()->class == 'success')
                    bg-green-50 text-green-700 border border-green-200
                @elseif(flash()->class == 'danger')
                    bg-red-50 text-red-700 border border-red-200
                @elseif(flash()->class == 'warning')
                    bg-yellow-50 text-yellow-700 border border-yellow-200
                @else
                    bg-blue-50 text-blue-700 border border-blue-200
                @endif
                " role="alert">
                {{ flash()->message }}
            </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200" placeholder="Masukkan email Anda" required>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-200" placeholder="Masukkan password Anda" required>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('registrasi') }}" class="text-sm text-teal-600 hover:text-teal-800 hover:underline transition duration-200">Daftar Sebagai Reseller / Kurir</a>
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-300">Masuk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js?v=3.2.0') }}"></script>
</body>
</html>
