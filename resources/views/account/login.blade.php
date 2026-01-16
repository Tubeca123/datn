<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập</title>
    <link rel="shortcut icon" href="{{asset("assets-web/img/favicon.png")}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/dist/css/adminlte.min.css")}}">
</head>

<body class="hold-transition login-page">    
    <div class="login-box">
        @if(Session::has('messenge') && is_array(Session::get('messenge')))
            @php
                $messenge = Session::get('messenge');
            @endphp
            @if(isset($messenge['style']) && isset($messenge['msg']))
                <div class="alert alert-{{ $messenge['style'] }}" role="alert" style="width: auto; z-index: 999" id="myAlert">
                    <i class="bi bi-check2 text-{{ $messenge['style'] }}"></i>{{ $messenge['msg'] }}
                </div>
                @php
                    Session::forget('messenge');
                @endphp
            @endif
        @endif    
        <div class="card card-outline card-primary">            
            <div class="card-header text-center">
                <a href="#" class="h1">Đăng nhập</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Đăng nhập vào hệ thống</p>
                <form id="quickForm" action="{{route("postLogin")}}" method="post">
                    @csrf
                    <div class="input-group mb-3 form-group">
                        <input type="text" name="user_name" class="form-control" placeholder="Nhập tên đăng nhập">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3 form-group">
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập</button>
                        </div>
                    </div>
                </form>
                <div class="social-auth-links text-center mt-2 mb-3">                   
                    <a href="{{ route("password.request") }}" class="btn btn-block btn-danger">
                        <i class="fa-solid fa-question"></i> Quên mật khẩu
                    </a>
                </div>
                <a href="{{ route("register") }}">Bạn chưa có tài khoản? Đăng ký</a>
            </div>
        </div>
    </div>
    <script src="{{asset("assets/plugins/jquery/jquery.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <script src="{{asset("assets/dist/js/adminlte.min.js")}}"></script>
    <script src="{{asset("assets/plugins/jquery-validation/jquery.validate.min.js")}}"></script>
    <script src="{{asset("assets/plugins/jquery-validation/additional-methods.min.js")}}"></script>

    <script>
        $(function() {
            $('#quickForm').validate({
                rules: {
                    user_name: {
                        required: true,
                        minlength: 2,
                    },
                    password: {
                        required: true,
                        minlength: 1
                    }
                },
                messages: {
                    user_name: {
                        required: "Tên đăng nhập không được để trống",
                        minlength: "Tên đăng nhập phải có ít nhất {0} ký tự!"
                    },
                    password: {
                        required: "Mật khẩu không được để trống",
                        minlength: "Mật khẩu phải có ít nhất {0} ký tự!"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });

        setTimeout(function() {
            $("#myAlert").fadeOut(500);
        },3500);
    </script>
</body>
</html>