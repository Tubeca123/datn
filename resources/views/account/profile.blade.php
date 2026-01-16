@extends('admin.master_layout')

@section('page_content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('profile') }}" class="text-info">Thiết lập tài khoản</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>               
                    </div>
                </div>
            </div>
            @if(Session::has('messenge') && is_array(Session::get('messenge')))
                @php
                    $messenge = Session::get('messenge');
                @endphp
                @if(isset($messenge['style']) && isset($messenge['msg']))
                    <div class="alert alert-{{ $messenge['style'] }}" role="alert" style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                        <i class="bi bi-check2 text-{{ $messenge['style'] }}"></i>{{ $messenge['msg'] }}
                    </div>
                    @php
                        Session::forget('messenge');
                    @endphp
                @endif
            @endif
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-info card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    @php
                                            $img = $user->image ?? 'uploads/no_image.png';
                                            @endphp
                                    <img class="profile-user-img img-fluid img-circle" src="{{asset($img)}}" alt="User profile picture" style="width:150px; height:150px; object-fit:cover;">
                                </div>
                                <h3 class="profile-username text-center mt-2">{{$user->user_name}}</h3>
                                <p class="text-muted text-center">{{$user->role->name}}</p>
                                <p class="text-muted text-center">{{$user->email}}</p>
                                <p class="text-muted text-center">{{$user->phone}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card card-info">
                            <div class="card-header p-3 text-center">
                                <h4>Thông tin tài khoản</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Họ tên</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->name}}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Địa chỉ</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->address ? "$user->address" : "Chưa cập nhật"}}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Ngày tạo tài khoản</strong></div>
                                    <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><strong>Trạng thái</strong></div>
                                    <div class="col-lg-9 col-md-8">{{$user->isactive  ? "Hoạt động" : "Bị khóa"}}</div>
                                </div>
                                <hr>
                                <div class="text-center p-1">
                                    <a href="{{ route("edit-profile") }}" class="btn btn-info btn-sm" title="Cập nhật tài khoản">
                                        <i class="bi bi-pencil"> Cập nhật tài khoản</i>
                                    </a>                                
                                    <a href="{{ route("editPassword") }}" class="btn btn-info btn-sm" title="Đổi mật khẩu">
                                        <i class="bi bi-pencil"> Đổi mật khẩu</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
