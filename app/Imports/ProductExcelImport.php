<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Inventory;
use App\Models\ImageProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class ProductExcelImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Bỏ dòng tiêu đề
        $rows->shift();

        foreach ($rows as $row) {

            // ===============================
            // Lấy dữ liệu từ Excel
            // ===============================
            $name = $row[0];
            $description = $row[1];
            $brandId = (int)$row[2];
            $categoryId = (int)$row[3];
            $manufacturer = $row[4];
            $country = $row[5];

            $unitHopId  = (int)$row[6];
            $unitViId   = (int)$row[7];
            $unitVienId = (int)$row[8];

            $priceImportHop = (float)$row[9];
            $priceSaleHop   = (float)$row[10];

            $soVi   = (int)$row[11];
            $soVien = (int)$row[12];

            $code     = $row[13];
            $dateEnd  = Carbon::parse($row[14]);
            $importQty = (int)$row[15];

            // Ảnh
            $images = $row[16] ?? '';
            $imageList = array_filter(array_map('trim', explode('|', $images)));


            // ===============================
            // 1. Tìm hoặc tạo mới product
            // ===============================
            $product = Product::firstOrCreate(
                [
                    'name' => $name,
                    'brand_id' => $brandId,
                    'category_id' => $categoryId,
                ],
                [
                    'description' => $description,
                    'manufacturer' => $manufacturer,
                    'country' => $country,
                    'create_date' => now(),
                    'create_by'=>Auth::user()->id,
                    'isactive' => 1,
                ]
            );

            // Update lại thông tin nếu đã tồn tại
            $product->update([
                'description' => $description,
                'manufacturer' => $manufacturer,
                'country' => $country,
                'update_date' => now(),
            ]);


            // ===============================
            // 2. Chia giá theo đơn vị
            // ===============================
            $priceImportVi   = $priceImportHop / $soVi;
            $priceSaleVi     = $priceSaleHop / $soVi;

            $priceImportVien = $priceImportVi / $soVien;
            $priceSaleVien   = $priceSaleVi / $soVien;


            // ===============================
            // 3. Update/Create ProductUnit
            // ===============================

            // Hộp
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $unitHopId],
                [
                    'price_import' => $priceImportHop,
                    'price_sale'   => $priceSaleHop,
                    'quantity_per_unit' => $soVi * $soVien,
                ]
            );

            // Vỉ
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $unitViId],
                [
                    'price_import' => $priceImportVi,
                    'price_sale'   => $priceSaleVi,
                    'quantity_per_unit' => $soVien,
                ]
            );

            // Viên
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $unitVienId],
                [
                    'price_import' => $priceImportVien,
                    'price_sale'   => $priceSaleVien,
                    'quantity_per_unit' => 1,
                ]
            );


            // ===============================
            // 4. Nhập kho (Inventory)
            // ===============================
            Inventory::create([
                'product_id' => $product->id,
                'code' => $code,
                'date_end' => $dateEnd,
                'import_quantity' => $importQty,
                'stock_quantity' => $importQty,
                'create_date' => now(),
                'create_by'=>Auth::user()->id
            ]);



            if (!empty($imageList)) {

                // Lấy danh sách ảnh cũ (KHÔNG XÓA)
                $oldImgs = ImageProduct::where('product_id', $product->id)->get();

                // Vị trí bắt đầu cho ảnh mới
                $pos = ($oldImgs->max('position') ?? 0) + 1;

                // Thêm ảnh mới, KHÔNG đụng ảnh cũ
                foreach ($imageList as $img) {
                    ImageProduct::create([
                        'product_id' => $product->id,
                        'src' => 'uploads/products/' . $img . '.jpg',
                        'position' => $pos++,
                        'create_date' => now(),
                        'isactive' => 1,
                    ]);
                }
            }
        }
    }
}
