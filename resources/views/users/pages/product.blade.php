@extends('users/master_layout')

@section('title')
<title>{{ $product->name }} - Chi tiết sản phẩm</title>
@endsection

@section('content')
<!-- Main Content -->
<div class="mn-main-content">
    <div class="mn-breadcrumb m-b-30">
        <div class="row">
            <div class="col-12">
                <div class="row gi_breadcrumb_inner">
                    <div class="col-md-6 col-sm-12">
                        <h2 class="mn-breadcrumb-title">Trang sản phẩm</h2>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <ul class="mn-breadcrumb-list">
                            <li class="mn-breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="mn-breadcrumb-item"><a href="{{ route('shop') }}">Cửa hàng</a></li>
                            <li class="mn-breadcrumb-item active">{{ $product->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12">
            <section class="mn-single-product">
                <div class="row">
                    <div class="mn-pro-rightside mn-common-rightside col-lg-12 col-md-12 m-b-15">
                        <!-- Single product content Start -->
                        <div class="single-pro-block">
                            <div class="single-pro-inner">
                                <div class="row">
                                    <div class="single-pro-img single-pro-img-no-sidebar">
                                        <div class="single-product-scroll">
                                            <div class="single-product-cover">
                                                @if($product->images->count() > 0)
                                                @foreach($product->images as $image)
                                                <div class="single-slide zoom-image-hover">
                                                    <img class="img-responsive"
                                                        src="{{ asset($image->src) }}"
                                                        alt="{{ $product->name }}">
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="single-slide zoom-image-hover">
                                                    <img class="img-responsive"
                                                        src="{{ asset('assets/img/product/default.jpg') }}"
                                                        alt="No image">
                                                </div>
                                                @endif
                                            </div>

                                            @if($product->images->count() > 1)
                                            <div class="single-nav-thumb">
                                                @foreach($product->images as $image)
                                                <div class="single-slide">
                                                    <img class="img-responsive"
                                                        src="{{ asset($image->src) }}"
                                                        alt="{{ $product->name }}">
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="single-pro-desc single-pro-desc-no-sidebar m-t-991">
                                        <div class="single-pro-content">
                                            <h5 class="mn-single-title">{{ $product->name }}</h5>

                                            @if($product->brand)
                                            <div class="mn-single-brand mb-3">
                                                <span class="text-muted">Thương hiệu: </span>
                                                <strong>{{ $product->brand->name }}</strong>
                                            </div>
                                            @endif

                                            <div class="mn-single-price-stoke">
                                                <!-- Hiển thị giá theo từng đơn vị -->
                                                <div class="mn-single-price mb-3">
                                                    @if($product->units->count() > 0)
                                                    @foreach($product->units as $index => $productUnit)
                                                    <div class="price-unit-item mb-2">
                                                        <span class="unit-label">
                                                            <i class="ri-price-tag-3-line"></i>
                                                            {{ $productUnit->unit->name ?? 'Đơn vị ' . ($index + 1) }}:
                                                        </span>
                                                        <span class="final-price ms-2">
                                                            {{ number_format($productUnit->price_sale, 0, ',', '.') }}đ
                                                        </span>

                                                        <small class="text-muted ms-2">
                                                            ({{ $productUnit->quantity_per_unit }} viên)
                                                        </small>
                                                    </div>
                                                    @endforeach
                                                    @else
                                                    <div class="text-muted">Chưa có thông tin giá</div>
                                                    @endif
                                                </div>

                                                <div class="mn-single-stoke">
                                                    <span class="mn-single-sku">
                                                        <i class="ri-barcode-line"></i>
                                                        Mã thuốc: <strong>{{ $product->id }}</strong>
                                                    </span>
                                                </div>


                                            </div>

                                            @if($product->description)
                                            <div class="mn-single-sales mt-3">
                                                <div class="mn-single-sales-inner">
                                                    <div class="mn-single-sales-visitor">
                                                        <strong>Mô tả:</strong> {{ $product->description }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if($product->details)
                                            <div class="mn-single-desc mt-3">
                                                <strong>Chi tiết:</strong>
                                                <p>{{ $product->details }}</p>
                                            </div>
                                            @endif

                                            @if($product->manufacturer)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="ri-building-line"></i>
                                                    Nhà sản xuất: <strong>{{ $product->manufacturer }}</strong>
                                                </small>
                                            </div>
                                            @endif

                                            @if($product->country)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="ri-global-line"></i>
                                                    Xuất xứ: <strong>{{ $product->country }}</strong>
                                                </small>
                                            </div>
                                            @endif

                                            <!-- Chọn đơn vị và số lượng -->
                                            <div class="mn-pro-variation mt-4">
                                                @if($product->units->count() > 0)
                                                <div class="mn-pro-variation-inner mn-pro-variation-size m-b-24">
                                                    <span>Chọn đơn vị:</span>
                                                    <div class="mn-pro-variation-content">
                                                        <ul>
                                                            @foreach($product->units as $index => $productUnit)
                                                            <li class="{{ $index === 0 ? 'active' : '' }}"
                                                                data-unit-id="{{ $productUnit->id }}"
                                                                data-price="{{ $productUnit->price_sale }}">
                                                                <span>{{ $productUnit->unit->name ?? 'Đơn vị ' . ($index + 1) }}</span>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            <div class="mn-single-qty">
                                                <div class="qty-plus-minus">
                                                    <input class="qty-input" type="text" name="ms_qtybtn" value="1">
                                                </div>
                                                <div class="mn-btns">
                                                    <form method="POST" action="{{ route('cart.add') }}" id="addToCartForm">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="product_unit_id" id="product_unit_id" value="{{ $product->units->count() ? $product->units[0]->id : '' }}">
                                                        <input type="hidden" name="quantity" id="quantity_input" value="1">
                                                        <button type="submit" class="btn btn-primary mn-btn-2 mn-add-cart">
                                                            <span><i class="ri-shopping-cart-line"></i> Thêm vào giỏ</span>
                                                        </button>
                                                    </form>
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const unitItems = document.querySelectorAll('.mn-pro-variation-size ul li');
                                                        const unitInput = document.getElementById('product_unit_id');
                                                        const addToCartForm = document.getElementById('addToCartForm');
                                                        const qtyInput = document.querySelector('.qty-input');
                                                        const qtyHidden = document.getElementById('quantity_input');

                                                        unitItems.forEach(item => {
                                                            item.addEventListener('click', function() {
                                                                unitItems.forEach(i => i.classList.remove('active'));
                                                                this.classList.add('active');
                                                                unitInput.value = this.dataset.unitId;
                                                            });
                                                        });
                                                        if (qtyInput && qtyHidden) {
                                                            qtyInput.addEventListener('input', function() {
                                                                qtyHidden.value = this.value;
                                                            });
                                                        }
                                                    });
                                                </script>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Single product content End -->

                    <!-- Single product accordion start -->
                    <div class="mn-accordion style-1 mn-single-pro-tab-content mt-4">
                        <div class="mn-accordion-item">
                            <h4 class="mn-accordion-header">
                                Thông tin chi tiết
                            </h4>
                            <div class="mn-accordion-body show">
                                <div class="mn-single-pro-tab-desc">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Thông tin cơ bản</h6>
                                            <ul class="list-unstyled">
                                                <li><strong>Tên sản phẩm:</strong> {{ $product->name }}</li>
                                                <li><strong>Mã sản phẩm:</strong> {{ $product->id }}</li>
                                                @if($product->brand)
                                                <li><strong>Thương hiệu:</strong> {{ $product->brand->name }}</li>
                                                @endif
                                                @if($product->category)
                                                <li><strong>Danh mục:</strong> {{ $product->category->name }}</li>
                                                @endif
                                                @if($product->manufacturer)
                                                <li><strong>Nhà sản xuất:</strong> {{ $product->manufacturer }}</li>
                                                @endif
                                                @if($product->country)
                                                <li><strong>Xuất xứ:</strong> {{ $product->country }}</li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Giá bán theo đơn vị</h6>
                                            <ul class="list-unstyled">
                                                @foreach($product->units as $productUnit)
                                                <li>
                                                    <strong>{{ $productUnit->unit->name ?? 'Đơn vị' }}:</strong>
                                                    {{ number_format($productUnit->price_sale, 0, ',', '.') }}đ
                                                    <small class="text-muted">({{ $productUnit->quantity_per_unit }} viên)</small>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    @if($product->description || $product->details)
                                    <div class="mt-3">
                                        <h6>Mô tả chi tiết</h6>
                                        @if($product->description)
                                        <p>{{ $product->description }}</p>
                                        @endif
                                        @if($product->details)
                                        <p>{{ $product->details }}</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </section>
    </div>
</div>
</div>

<style>
    .price-unit-item {
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #007bff;
    }

    .stock-unit-item {
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 5px;
    }

    .mn-pro-variation-size ul li {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .mn-pro-variation-size ul li:hover {
        background-color: #007bff;
        color: white;
    }

    .mn-pro-variation-size ul li.active {
        background-color: #007bff;
        color: white;
    }
</style>

<script>
    // Script để chọn đơn vị
    document.addEventListener('DOMContentLoaded', function() {
        const unitItems = document.querySelectorAll('.mn-pro-variation-size ul li');

        unitItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all
                unitItems.forEach(i => i.classList.remove('active'));

                // Add active to clicked item
                this.classList.add('active');

                // Get unit data
                const unitId = this.dataset.unitId;
                const price = this.dataset.price;

                console.log('Selected unit:', unitId, 'Price:', price);
                // Có thể thêm logic cập nhật giá hiển thị ở đây
            });
        });
    });
</script>
@endsection