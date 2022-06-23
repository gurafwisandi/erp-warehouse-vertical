<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Provice Group - Integrated Property Services</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo.png') }}">
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/css/form.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <a href="#" class="logo logo-admin"><img src="{{ URL::asset('assets/images/logo.png') }}"
                            height="100" alt="logo"></a>
                </div>
                @if (count($errors) > 0)
                    <div class="px-2">
                        <div class="alert alert-danger" role="alert">
                            Login Fail!
                        </div>
                    </div>
                @endif
                @if (session()->has('loginError'))
                    <div class="px-2">
                        <div class="alert alert-danger" role="alert">
                            Login Fail!
                        </div>
                    </div>
                @endif
                @if (session()->has('registerSucces'))
                    <div class="px-2">
                        <div class="alert alert-success" role="alert">
                            Register Succes!
                        </div>
                    </div>
                @endif
                <div class="px-3 pb-3">
                    <form class="form-horizontal m-t-20" action="{{ route('login.proses') }}" method="POST"
                        novalidate>
                        @csrf
                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" name="email" type="text" placeholder="Email">
                                {!! $errors->first('email', '<div class="invalid-validasi">:message</div>') !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control" name="password" type="password" placeholder="Password">
                                {!! $errors->first('password', '<div class="invalid-validasi">:message</div>') !!}
                            </div>
                        </div>
                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-danger btn-block waves-effect waves-light" type="submit">Log
                                    In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/detect.js') }}"></script>
    <script src="{{ asset('assets/js/fastclick.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
