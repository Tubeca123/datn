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
        // Lấy khoảng thời gian từ request (mặc định 30 ngày gần nhất)
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // 1. Tổng quan doanh thu
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

    // API endpoint cho biểu đồ real-time (nếu cần)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
