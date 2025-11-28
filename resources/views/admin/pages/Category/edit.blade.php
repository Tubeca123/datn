@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
<div class="container">
    
    <h2>Sửa danh mục </h2>

    <form action="{{ route('update_category', $category->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Tên</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="about" class="form-control">{{ $category->about }}</textarea>
        </div>

        <div class="form-group">
            <label>Vị trí</label>
            <input type="number" name="position" value="{{ $category->position }}" class="form-control">
        </div>

        <button class="btn btn-primary mt-3">Cập nhật</button>
    </form>
</div>
</div>
@endsection