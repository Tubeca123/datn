<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $totalRevenue = Order::where('isactive', 1)
            ->whereBetween('create_date', [$startDate, $endDate])
            ->sum('total');

        // 2. Tổng số đơn hàng
        $totalOrders = Order::where('isactive', 1)
            ->whereBetween('create_date', [$startDate, $endDate])
            ->count();

        // 3. Số sản phẩm đã bán
        $totalProductsSold = OrderDetail::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->where('isactive', 1)
                ->whereBetween('create_date', [$startDate, $endDate]);
        })
            ->sum('quantity');

        // 4. Giá trị đơn hàng trung bình
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // 5. Top 10 sản phẩm bán chạy nhất (theo doanh thu)
        $topProductsByRevenue = OrderDetail::select(
            'product_id',
            DB::raw('SUM(quantity * price) as total_revenue'),
            DB::raw('SUM(quantity) as total_quantity')
        )
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('isactive', 1)
                    ->whereBetween('create_date', [$startDate, $endDate]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // 6. Top 10 sản phẩm bán chạy nhất (theo số lượng)
        $topProductsByQuantity = OrderDetail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * price) as total_revenue')
        )
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('isactive', 1)
                    ->whereBetween('create_date', [$startDate, $endDate]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // 7. Doanh thu theo ngày (cho biểu đồ)
        $revenueByDate = Order::select(
            DB::raw('DATE(create_date) as date'),
            DB::raw('SUM(total) as daily_revenue'),
            DB::raw('COUNT(*) as daily_orders')
        )
            ->where('isactive', 1)
            ->whereBetween('create_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 8. Doanh thu theo tháng (năm hiện tại)
        $revenueByMonth = Order::select(
            DB::raw('MONTH(create_date) as month'),
            DB::raw('SUM(total) as monthly_revenue'),
            DB::raw('COUNT(*) as monthly_orders')
        )
            ->where('isactive', 1)
            ->whereYear('create_date', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 9. Doanh thu theo danh mục sản phẩm
        $revenueByCategory = OrderDetail::select(
            'product.category_id',
            'categories.name as category_name',
            DB::raw('SUM(order_detail.quantity * order_detail.price) as category_revenue')
        )
            ->join('product', 'order_detail.product_id', '=', 'product.id')
            ->join('categories', 'product.category_id', '=', 'categories.id')
            ->join('order', 'order_detail.order_id', '=', 'order.id')
            ->where('order.isactive', 1)
            ->whereBetween('order.create_date', [$startDate, $endDate])
            ->groupBy('product.category_id','categories.name')
            ->get();

        

        // 10. So sánh doanh thu kỳ trước
        $previousStartDate = Carbon::parse($startDate)->subDays(
            Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate))
        )->format('Y-m-d');
        $previousEndDate = $startDate;

        $previousRevenue = Order::where('isactive', 1)
            ->whereBetween('create_date', [$previousStartDate, $previousEndDate])
            ->sum('total');

        $revenueGrowth = $previousRevenue > 0
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100
            : 0;

        return view('admin.pages.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProductsSold',
            'avgOrderValue',
            'topProductsByRevenue',
            'topProductsByQuantity',
            'revenueByDate',
            'revenueByMonth',
            'revenueByCategory',
            'revenueGrowth',
            'startDate',
            'endDate'
        ));
    }

    
    /**
     * Hiển thị chi tiết doanh thu và lợi nhuận
     */
    public function revenueDetails(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $view = $request->input('view', 'products'); 

        // Thông tin chi tiết từng sản phẩm với giá nhập/bán
        $productDetails = OrderDetail::select(
            'order_detail.product_id',
            'product.id',
            DB::raw("COALESCE(product.name, 'Sản phẩm đã xóa') as product_name"),
            DB::raw("COALESCE(categories.name, 'Danh mục không xác định') as category_name"),
            'product_unit.price_import',
            'product_unit.price_sale',
            DB::raw('COUNT(DISTINCT order_detail.order_id) as order_count'),
            DB::raw('SUM(order_detail.quantity) as total_quantity'),
            DB::raw('SUM(order_detail.quantity * order_detail.price) as total_revenue'),
            DB::raw('SUM(order_detail.quantity * product_unit.price_import) as total_cost'),
            DB::raw('SUM(order_detail.quantity * (order_detail.price - product_unit.price_import)) as profit')
        )
            ->join('order', 'order_detail.order_id', '=', 'order.id')
            ->leftJoin('product', 'order_detail.product_id', '=', 'product.id')
            ->leftJoin('categories', 'product.category_id', '=', 'categories.id')
            ->join('product_unit', 'order_detail.product_unit_id', '=', 'product_unit.id')
            ->where('order.isactive', 1)
            ->whereBetween('order.create_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('order_detail.product_id', 'product.id', 'product.name', 'categories.name', 'product_unit.id', 'product_unit.price_import', 'product_unit.price_sale')
            ->orderByDesc('total_revenue')
            ->paginate(20);

        // Chi tiết theo danh mục
        $categoryDetails = OrderDetail::select(
            'categories.id',
            'categories.name as category_name',
            DB::raw('COUNT(DISTINCT product.id) as product_count'),
            DB::raw('SUM(order_detail.quantity) as total_quantity'),
            DB::raw('SUM(order_detail.quantity * order_detail.price) as total_revenue'),
            DB::raw('SUM(order_detail.quantity * product_unit.price_import) as total_cost'),
            DB::raw('SUM(order_detail.quantity * (order_detail.price - product_unit.price_import)) as profit')
        )
            ->join('order', 'order_detail.order_id', '=', 'order.id')
            ->join('product', 'order_detail.product_id', '=', 'product.id')
            ->join('categories', 'product.category_id', '=', 'categories.id')
            ->join('product_unit', 'order_detail.product_unit_id', '=', 'product_unit.id')
            ->where('order.isactive', 1)
            ->whereBetween('order.create_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Tổng thống kê
        $totalStats = OrderDetail::select(
            DB::raw('SUM(order_detail.quantity * order_detail.price) as total_revenue'),
            DB::raw('SUM(order_detail.quantity * product_unit.price_import) as total_cost'),
            DB::raw('SUM(order_detail.quantity * (order_detail.price - product_unit.price_import)) as total_profit'),
            DB::raw('AVG(order_detail.price - product_unit.price_import) as avg_profit_per_item')
        )
            ->join('order', 'order_detail.order_id', '=', 'order.id')
            ->join('product_unit', 'order_detail.product_unit_id', '=', 'product_unit.id')
            ->where('order.isactive', 1)
            ->whereBetween('order.create_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->first();

        $profitMargin = $totalStats->total_revenue > 0 
            ? ($totalStats->total_profit / $totalStats->total_revenue) * 100 
            : 0;

        return view('admin.pages.dashboard.revenue-details', compact(
            'productDetails',
            'categoryDetails',
            'totalStats',
            'profitMargin',
            'startDate',
            'endDate',
            'view'
        ));
    }
    
    public function getRealtimeData()
    {
        $today = Carbon::today();

        $hourlyRevenue = Order::select(
            DB::raw('HOUR(create_date) as hour'),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
            ->where('isactive', 1)
            ->whereDate('create_date', $today)
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get();

        return response()->json($hourlyRevenue);
    }

    
}
