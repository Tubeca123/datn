@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
<div class="container">
    
    <h2>Sửa Thương hiệu </h2>

    <form action="{{ route('update_brand', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ $brand->description }}</textarea>
        </div>

        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>
</div>
@endsection