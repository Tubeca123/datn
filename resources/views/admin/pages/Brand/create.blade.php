@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container">
        <h3 class="mb-4">Thêm Thương Hiệu</h3>


        <form action="{{ route('store_brand') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Lưu lại</button>
    </form>
    </div>
</div>
@endsection