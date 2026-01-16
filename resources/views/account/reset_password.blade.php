<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đổi mật khẩu</title>
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
        @if ($errors->any())
            <div style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-check2 text-danger"></i> {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif
        <div class="card card-outline card-primary">            
            <div class="card-header text-center">
                <a href="#" class="h1">Đổi mật khẩu</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Đặt lại mật khẩu đăng nhập hệ thống</p>
                <p class="login-box-msg text-danger"></p>
                <form id="quickForm" action="{{route("password.update")}}" method="post">
                    @csrf
                    <div class="input-group mb-3 form-group">
                        <input type="hidden" name="token" value="{{$token}}">
                        <input type="password" name="pw" id="pw" class="form-control" placeholder="Nhập mật khẩu mới">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3 form-group">
                        <input type="password" name="pw_confirm" class="form-control" placeholder="Xác nhận mật khẩu mới">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                        </div>
                    </div>         
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-block"><i class="fa-solid fa-lock"></i> Đặt lại mật khẩu</button>
                        </div>
                    </div>
                </form>               
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
                pw: {
                    required: true,
                    minlength: 6
                },
                pw_confirm: {
                    required: true,
                    equalTo: "#pw"
                }
            },
            messages: {
                pw: {
                    required: "Vui lòng nhập mật khẩu mới!",
                    minlength: "Mật khẩu phải có ít nhất {0} ký tự!"
                },                
                pw_confirm: {
                    required: "Vui lòng nhập lại mật khẩu mới!",
                    equalTo: "Mật khẩu xác nhận không khớp!"
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