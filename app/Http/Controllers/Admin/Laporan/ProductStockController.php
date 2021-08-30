<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Exports\Laporan\ProductStockExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductStockController extends Controller
{
    public function index()
    {
        return view('admin.laporan.product-stock.index');
    }

    public function datatable(Request $request, DataTables $dataTables): JsonResponse
    {
        try {
            $filter_tgl = $request->get('filter_tgl') ?? null;

            $model = Product::laporan($filter_tgl);

            return $dataTables
                ->query($model)
                ->toJson();
        } catch (\Throwable $throwable) {
            return response()->json([
                'status' => 'error',
                'message' => $throwable->getMessage(),
                'details' => $throwable
            ]);
        }
    }

    public function exportExcel(Request $request)
    {
        $filter_tgl = $request->get('filter_tgl') ?? null;

        return (new ProductStockExport($filter_tgl))->download('laporan_stok_'.date('Y-m-d_H-i-s').'.xlsx');
    }
}
