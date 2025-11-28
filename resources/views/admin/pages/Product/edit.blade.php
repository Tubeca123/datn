@extends('admin.master_layout')

@section('page_content')
<div class="content-wrapper">
<div class="container">
    <h3 class="mb-4">Edit Sản phẩm</h3>

    <form action="{{ route('update_product', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {{-- Tên --}}
                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Danh mục + Thương hiệu --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" {{ (old('category_id', $product->category_id) == $c->id) ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach ($brands as $b)
                                <option value="{{ $b->id }}" {{ (old('brand_id', $product->brand_id) == $b->id) ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Giá theo unit --}}
                <div class="mb-3">
                    <label class="form-label">Giá (theo đơn vị)</label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="small text-muted">Giá theo hộp</label>
                            <input type="number" name="price_box" class="form-control" step="0.01"
                                   value="{{ old('price_box', isset($price_box) ? $price_box : '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted">Giá theo vỉ</label>
                            <input type="number" name="price_pack" class="form-control" step="0.01"
                                   value="{{ old('price_pack', isset($price_pack) ? $price_pack : '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted">Giá theo viên</label>
                            <input type="number" name="price_pill" class="form-control" step="0.01"
                                   value="{{ old('price_pill', isset($price_pill) ? $price_pill : '') }}">
                        </div>
                    </div>
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Ảnh hiện tại --}}
                <div class="mb-3">
                    <label class="form-label d-block">Ảnh hiện tại</label>
                    <div class="d-flex flex-wrap align-items-start gap-2">
                        @forelse ($product->images as $img)
                            <div class="text-center" style="width:100px">
                                <img src="{{ asset($img->src) }}" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">
                                <div class="mt-1 small text-muted">#{{ $img->position }}</div>
                            </div>
                        @empty
                            <div class="text-muted">Chưa có ảnh</div>
                        @endforelse
                    </div>
                    
                </div>

                {{-- Upload ảnh mới --}}
                <div class="mb-3">
                    <label class="form-label">Upload ảnh mới</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    @error('images.*') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('list_product') }}" class="btn btn-secondary">Hủy</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
</div>
</div>
@endsection