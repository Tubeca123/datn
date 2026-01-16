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
        
        $rows->shift();

        foreach ($rows as $row) {

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

            
            $images = $row[16] ?? '';
            $imageList = array_filter(array_map('trim', explode('|', $images)));


            
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

           
            $product->update([
                'description' => $description,
                'manufacturer' => $manufacturer,
                'country' => $country,
                'update_date' => now(),
            ]);


            
            $priceImportVi   = $priceImportHop / $soVi;
            $priceSaleVi     = $priceSaleHop / $soVi;

            $priceImportVien = $priceImportVi / $soVien;
            $priceSaleVien   = $priceSaleVi / $soVien;


            
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $unitHopId],
                [
                    'price_import' => $priceImportHop,
                    'price_sale'   => $priceSaleHop,
                    'quantity_per_unit' => $soVi * $soVien,
                ]
            );

            
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $unitViId],
                [
                    'price_import' => $priceImportVi,
                    'price_sale'   => $priceSaleVi,
                    'quantity_per_unit' => $soVien,
                ]
            );

           
            ProductUnit::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $unitVienId],
                [
                    'price_import' => $priceImportVien,
                    'price_sale'   => $priceSaleVien,
                    'quantity_per_unit' => 1,
                ]
            );


            
            Inventory::create([
                'product_id' => $product->id,
                'code' => $code,
                'date_end' => $dateEnd,
                'import_quantity' => $importQty,
                'stock_quantity' => $importQty,
                'create_date' => now(),
                'create_by'=>Auth::user()->id,
                'isactive' => 1,
            ]);



            if (!empty($imageList)) {

                // Xóa tất cả ảnh cũ trong DB cho product này (KHÔNG xóa file trong thư mục uploads/products)
                ImageProduct::where('product_id', $product->id)->delete();

                // Vị trí bắt đầu cho ảnh mới (bắt đầu từ 1)
                $pos = 1;

                // Thêm ảnh mới, thay thế hoàn toàn các bản ghi cũ
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
