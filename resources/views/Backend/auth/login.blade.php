<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>LARAVEL CMS 01</title>

        <link href="{{ asset('giaodien/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('giaodien/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <link href="{{ asset('giaodien/css/animate.css') }}" rel="stylesheet">
        <link href="{{ asset('giaodien/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('giaodien/css/sofm.css') }}" rel="stylesheet">
        <link href="{{ asset('giaodien/css/plugins/switchery/switchery.css') }}" rel="stylesheet">
    </head>

    <body class="gray-bg">
        <div class="loginColumns animated fadeInDown">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="font-bold">Welcome to IN+</h2>
                    <p>
                        Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
                    </p>
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                    </p>
                    <p>
                        When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    </p>
                    <p>
                        <small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small>
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="ibox-content">
                        <!-- Hiển thị thông báo lỗi hoặc thành công -->
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="post" class="m-t" role="form" action="{{ route('auth.login') }}">
                            @csrf
                            <div class="form-group">
                                <input 
                                    type="text" 
                                    name="email"
                                    class="form-control" 
                                    placeholder="Email" 
                                    value="{{ old('email') }}"
                                >
                                @error('email')
                                    <span class="error-message">* {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input 
                                    type="password" 
                                    name="password"
                                    class="form-control" 
                                    placeholder="Password" 
                                >
                                @error('password')
                                    <span class="error-message">* {{ $message }}</span>
                                @enderror
                                
                            </div>
                            <button type="submit" class="btn btn-primary block full-width m-b">Đăng nhập</button>
                            <a href="#">
                                <small>Forgot password?</small>
                            </a>
                        </form>
                        <p class="m-t">
                            <small>Newbie Code we app framework base on Bootstrap 3 &copy; 2014</small>
                        </p>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-6">
                    Copyright Example Company
                </div>
                <div class="col-md-6 text-right">
                    <small>© 2023</small>
                </div>
            </div>
        </div>
    </body>
</html>
