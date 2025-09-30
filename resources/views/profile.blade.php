<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css?v=3.2.0') }}">

</head>

<body>

        <div class="row align-items-center justify-content-center vh-100">
            <div class="col-md-4">

                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $user->foto) }}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ $user->name }}</h3>

                        <p class="text-muted text-center">{{ $user->role}}</p>

                        <x-flash-message/>

                        <form method="POST" action="{{ route('profile.update', $user->id)}}" id="form-update-profile" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input name="name" type="text" id="name" class="form-control" value="{{ $user->name }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input name="email" type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>

                            <div class="form-group" style="display: none;" id="input-foto">
                                <label for="foto">Foto Profile</label>
                                <input type="file" class="form-control" name="foto" id="foto">
                            </div>

                            <div class="form-group">
                                <label for="phone">Telepon</label>
                                <input name="phone" type="text" id="phone" class="form-control" value="{{ $user->phone }}" readonly>
                            </div>

                            <div class="form-group" style="display: none;" id="input-password">
                                <label for="password">Password</label>
                                <input name="password" id="password" type="password" class="form-control" readonly>
                            </div>

                            <div class="form-group" style="display: none;" id="input-password-confimation">
                                <label for="password-confimation">Konfirmasi Password</label>
                                <input name="password_confirmation" type="password" id="password-confimation" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control" readonly>{{ $user->alamat }}</textarea>
                            </div>


                            <div class="row">
                                <div class="col-6">

                                    <a href="{{ Auth::guard('reseller')->check() ? route(Auth::guard('reseller')->user()->role . '.index') : (Auth::guard('web')->check() ? route(Auth::guard('web')->user()->role . '.index') : route('home')) }}" class="btn btn-primary">Kembali</a>

                                </div>
                                <div class="col-6">

                        <button id="btn-simpan-profile" style="display: none;" type="submit" class="btn btn-primary btn-block"><b>Simpan</b></button>
                        <button type="button" class="btn btn-warning btn-block" id="btn-ubah-profile"><b>Ubah Profile</b></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>



    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js?v=3.2.0')}}"></script>

    <script>

        $(document).ready(function() {

            $('#btn-ubah-profile').click(function() {
                $('#btn-simpan-profile').show();
                $('#input-password').show();
                $('#input-password-confimation').show();
                $('#input-foto').show();
                $(this).hide();

                $('form input[readonly], form textarea[readonly]').removeAttr('readonly');
            });

            $('#btn-simpan-profile').click(function() {
                $('#btn-ubah-profile').show();
                $('#input-password').hide();
                $('#input-foto').hide();
                $('#input-password-confimation').hide();
                $(this).hide();
                $('form input, form textarea').attr('readonly', 'readonly');
            });

        });

    </script>

</body>

</html>
