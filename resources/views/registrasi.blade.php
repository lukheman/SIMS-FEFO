<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <i class="fas fa-user-plus fa-3x text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">UD Toko Diva Mowewe</h2>
            <p class="text-center text-gray-600 mb-6">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
            </p>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('signup') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Daftar Sebagai</label>
                    <div class="relative">
                        <select name="role" id="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                            <option value="{{ \App\Constants\Role::RESELLER }}" {{ old('role') == \App\Constants\Role::RESELLER ? 'selected' : '' }}>Reseller</option>
                            <option value="{{ \App\Constants\Role::KURIR }}" {{ old('role') == \App\Constants\Role::KURIR ? 'selected' : '' }}>Kurir</option>
                        </select>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-vector-square text-gray-400"></i>
                        </span>
                        @error('role')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <div class="relative">
                        <input type="text" name="name" id="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" placeholder="Nama Anda" value="{{ old('name') }}">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-user text-gray-400"></i>
                        </span>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" placeholder="Email" value="{{ old('email') }}">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <div class="relative">
                        <input type="text" name="phone" id="phone" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror" placeholder="Nomor Telepon" value="{{ old('phone') }}">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-phone text-gray-400"></i>
                        </span>
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" placeholder="Password">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Ulangi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ulangi Password">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Daftar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- REQUIRED SCRIPTS -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>
