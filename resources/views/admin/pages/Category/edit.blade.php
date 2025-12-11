@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container">
        <h3 class="mb-4">Sửa danh mục</h3>

        <form action="{{ route('update_category', $category->id) }}" method="POST" enctype="multipart/form-data">
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
                <label>Loại danh mục</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="position" id="position1" value="1" {{ $category->position == 1 ? 'checked' : '' }} onchange="toggleParentSelect()">
                        <label class="form-check-label" for="position1">
                            Danh mục cha (1)
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="position" id="position2" value="2" {{ $category->position == 2 ? 'checked' : '' }} onchange="toggleParentSelect()">
                        <label class="form-check-label" for="position2">
                            Danh mục con (2)
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" id="parentSelectGroup" @if($category->position == 2) style="display: block;" @else style="display: none;" @endif>
                <label>Danh mục cha <span class="text-danger">*</span></label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">-- Chọn danh mục cha --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Hình ảnh</label>
                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ asset($category->image) }}" alt="Current image" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                        <p class="text-muted small mt-1">Ảnh hiện tại</p>
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                <small class="form-text text-muted">Chấp nhận các định dạng: JPG, PNG, GIF. Để trống nếu không muốn thay đổi ảnh.</small>
                <div class="mt-2" id="imagePreview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                    <p class="text-muted small mt-1">Ảnh mới</p>
                </div>
            </div>

            <button class="btn btn-primary mt-3">Cập nhật</button>
        </form>
    </div>
</div>

<script>
    function toggleParentSelect() {
        const position2 = document.getElementById('position2');
        const parentSelectGroup = document.getElementById('parentSelectGroup');
        const parentId = document.getElementById('parent_id');
        
        if (position2.checked) {
            parentSelectGroup.style.display = 'block';
            parentId.setAttribute('required', 'required');
        } else {
            parentSelectGroup.style.display = 'none';
            parentId.removeAttribute('required');
            parentId.value = '';
        }
    }
    
    // Gọi hàm khi trang load để đảm bảo trạng thái đúng
    document.addEventListener('DOMContentLoaded', function() {
        toggleParentSelect();
    });

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endsection