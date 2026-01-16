@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('profile') }}" class="text-info">Thiết lập tài khoản</a></li>
                        <li class="breadcrumb-item active">Cập nhật Profile</li>
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


                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header p-3 text-center">
                            <h4>Thông tin tài khoản</h4>
                        </div>
                        <form method="post" action="{{route("updateProfile")}}" enctype="multipart/form-data" id="quickForm">
                            @csrf


                            <div class="card card-info card-outline">
                                <div class="card-body box-profile">
                                    <!-- ===== Image dropzone ===== -->
                                    @if($user->image)
                                    <div class="mb-2">
                                        <img src="{{ asset($user->image) }}" alt="Current image" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                                        <p class="text-muted small mt-1">Ảnh hiện tại</p>
                                    </div>
                                    @endif
                                    <div class="mb-3 js-image-dropzone">
                                        <label class="form-label fw-semibold">Hình ảnh</label>

                                        <div class="border rounded p-3 d-flex align-items-center justify-content-between drop-area"
                                            data-drop style="cursor:pointer; background: linear-gradient(180deg,#fff 0%,#fbfbfd 100%);">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle border" style="width:56px;height:56px;background:#f8f9ff;"> <!-- simple icon --> <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 3v10" stroke="#6c757d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M5 13l7-7 7 7" stroke="#6c757d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M21 21H3" stroke="#6c757d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg> </div>
                                                <div>
                                                    <div class="fw-medium">Kéo thả ảnh vào đây hoặc</div>
                                                    <div class="text-muted small drop-hint" data-hint>Chấp nhận: JPG, PNG, GIF — tối đa 5MB</div>
                                                </div>
                                            </div>

                                            <div>
                                                <button type="button" class="btn btn-outline-primary btn-sm btn-choose" data-choose>Chọn ảnh</button>
                                            </div>

                                            <!-- input file (ẩn) -->
                                            <input type="file" name="image" class="d-none" data-input accept="image/*">
                                        </div>

                                        <!-- preview -->
                                        <div class="mt-3 image-preview" data-preview style="display:none;">
                                            <div class="card" style="max-width:420px;">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-auto p-3">
                                                        <img class="rounded preview-img" data-preview-img src="" alt="Preview" style="width:120px;height:120px;object-fit:cover;border:1px solid #e9ecef;">
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body py-3">
                                                            <h6 class="card-title mb-1 file-name" data-filename></h6>
                                                            <p class="card-text small text-muted mb-2 file-info" data-fileinfo></p>
                                                            <div class="d-flex gap-2">
                                                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove" data-remove>Xoá</button>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-change" data-change>Thay ảnh</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-text text-muted mt-2">Kích thước tối đa: 5MB. Định dạng: JPG, PNG, GIF.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Họ tên</label>
                                            <input type="text" name="name" value="{{old('name', $user->name)}}" class="form-control" placeholder="Nhập họ tên người dùng">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" value="{{old('email', $user->email)}}" class="form-control" placeholder="Nhập email người dùng">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <input type="text" name="phone" value="{{old('phone', $user->phone)}}" class="form-control" placeholder="Nhập số điện thoại người dùng">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <input type="text" name="address" value="{{old('address', $user->address)}}" class="form-control" placeholder="Nhập địa chỉ người dùng">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('profile') }}" class="btn btn-info btn-sm" title="Cập nhật tài khoản">
                                    <i class="bi bi-pencil"> Quay lại</i>
                                </a>
                                <button type="submit" class="btn btn-success btn-sm">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script src="{{ asset('js/image-dropzone.js') }}"></script>

@endsection