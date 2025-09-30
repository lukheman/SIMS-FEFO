<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UD Toko Diva Mowewe</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-6">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex justify-center mb-6">
                <i class="fas fa-store fa-3x text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">UD Toko Diva Mowewe</h2>
            <p class="text-center text-gray-600 mb-6">Login ke akun Anda</p>

             @if (flash()->message)
            <div class="alert mb-4 p-4 rounded-lg text-sm
                @if(flash()->class == 'success')
                    bg-green-100 text-green-800 border border-green-300
                @elseif(flash()->class == 'danger')
                    bg-red-100 text-red-800 border border-red-300
                @elseif(flash()->class == 'warning')
                    bg-yellow-100 text-yellow-800 border border-yellow-300
                @else
                    bg-blue-100 text-blue-800 border border-blue-300
                @endif
                " role="alert">
                {{ flash()->message }}
            </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('registrasi') }}" class="text-sm text-blue-600 hover:underline">Daftar Menjadi Reseller / Kurir</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Login</button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js?v=3.2.0') }}"></script>
</body>
</html>
