@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <div class="container">
        <h3 class="mb-4">Thêm danh mục</h3>


        <form action="{{ route('store_category') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Tên</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="about" class="form-control"></textarea>
            </div>

            <div class="mb-3 js-image-dropzone">
                <label class="form-label fw-semibold">Hình ảnh</label>

                <div class="border rounded p-3 d-flex align-items-center justify-content-between drop-area"
                    data-drop style="cursor:pointer; background: linear-gradient(180deg,#fff 0%,#fbfbfd 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle border"
                            style="width:56px;height:56px;background:#f8f9ff;">
                            <!-- icon -->
                            <!-- ... svg ... -->
                        </div>
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


            <div class="form-group">
                <label>Loại danh mục</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="position" id="position1" value="1" checked onchange="toggleParentSelect()">
                        <label class="form-check-label" for="position1">
                            Danh mục cha (1)
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="position" id="position2" value="2" onchange="toggleParentSelect()">
                        <label class="form-check-label" for="position2">
                            Danh mục con (2)
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" id="parentSelectGroup" style="display: none;">
                <label>Danh mục cha <span class="text-danger">*</span></label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">-- Chọn danh mục cha --</option>
                    @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success mt-3">Lưu</button>
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
<script src="{{ asset('js/image-dropzone.js') }}"></script>
@endsection