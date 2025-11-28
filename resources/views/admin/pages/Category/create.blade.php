@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container">
        <h3 class="mb-4">Thêm danh mục</h3>


        <form action="{{ route('store_category') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Tên</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="about" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label>Vị trí</label>
                <input type="number" name="position" class="form-control">
            </div>

            <button class="btn btn-success mt-3">Lưu</button>
        </form>
    </div>
</div>
@endsection