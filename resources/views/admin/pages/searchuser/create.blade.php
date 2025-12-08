@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container">
        <h3 class="mb-4">Tạo tài khoản</h3>


        <form action="{{ route('store_user') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên khách hàng</label>
                <input type="text" name="name" class="form-control" >
            </div>
            @error('name')
            <span class="text-danger text-sm">{{ $message }}</span>
            @enderror

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input name="phone" class="form-control" type="text"></input>
            </div>
            @error('phone')
            <span class="text-danger text-sm">{{ $message }}</span>
            @enderror
            <button class="btn btn-primary">Lưu</button>
        </form>
    </div>
</div>
@endsection