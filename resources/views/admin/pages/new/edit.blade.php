@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="/admin/list_news" class="text-info">Quản lý bài viết</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa bài viết</li>
                    </ol>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-check2 text-danger"></i> {{ $error }}
            </div>
            @endforeach
        </div>
        @endif
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card card-info shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title">Chỉnh sửa bài viết</h3>
                        </div>

                        <form action="{{ route('update_news', $news->id) }}" method="POST" enctype="multipart/form-data" class="p-3">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Thể loại <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="category_id" id="category_id_edit" class="form-control" required>
                                        <option value="">-- Chọn thể loại --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $news->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name ?? $category->description }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCategoryModal">
                                            <i class="fas fa-plus"></i> Thêm thể loại
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $news->description) }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                                <textarea name="content" id="content" class="form-control" rows="10" required>{{ old('content', $news->content) }}</textarea>
                            </div>

                            <!-- ===== Image dropzone (reusable) ===== -->
                            <div class="mb-3 js-image-dropzone">
                                <label class="form-label fw-semibold">Hình ảnh</label>

                                @if($news->image)
                                <div class="mb-3">
                                    <label class="form-label">Ảnh hiện tại:</label>
                                    <div>
                                        @php
                                        $img = $news->image ?? 'uploads/no_image.png';
                                        @endphp
                                        <img src="{{ asset($img) }}" alt="{{ $news->title }}" style="max-width: 300px; max-height: 200px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                </div>
                                @endif

                                <div class="border rounded d-flex align-items-center justify-content-between p-3 drop-area"
                                    data-drop
                                    style="cursor:pointer; gap:16px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:50%;background:#f8f9ff;border:1px solid #e9ecef;">
                                            <!-- icon -->
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 3v10" stroke="#6c757d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M5 13l7-7 7 7" stroke="#6c757d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M21 21H3" stroke="#6c757d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>

                                        <div>
                                            <div class="fw-medium">Kéo &amp; thả ảnh vào đây</div>
                                            <div class="text-muted small drop-hint" data-hint>Chấp nhận: JPG, PNG, GIF — tối đa 2MB</div>
                                            <div class="text-muted small mt-1">Gợi ý: tỉ lệ 16:9 hoặc kích thước 1200×675 để ảnh hiển thị tốt.</div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-outline-primary btn-sm btn-choose" data-choose>Chọn ảnh</button>
                                    </div>

                                    <!-- input file (ẩn) -->
                                    <input type="file" name="image" class="d-none" data-input accept="image/*">
                                </div>

                                <!-- preview -->
                                <div class="mt-3 image-preview" data-preview style="display:none;">
                                    <div class="card shadow-sm" style="max-width:680px;">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-auto p-3">
                                                <img class="rounded preview-img" data-preview-img src="" alt="Preview" style="width:160px;height:90px;object-fit:cover;border:1px solid #e9ecef;">
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

                                <div class="form-text text-muted mt-2">Kích thước tối đa: 2MB. Định dạng: JPG, PNG, GIF.</div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('list_news') }}" class="btn btn-secondary">Hủy</a>
                                <button type="submit" class="btn btn-success">Cập nhật</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@php
    $categorySelectId = 'category_id_edit';
@endphp
@include('admin.pages.new.modalcategory')

@endsection

<!-- include reusable script -->
<script src="{{ asset('js/image-dropzone.js') }}"></script>

