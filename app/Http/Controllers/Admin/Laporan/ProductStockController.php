<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Exports\Laporan\ProductStockExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductStockController extends Controller
{
    public function index()
    {
        return view('admin.laporan.product-stock.index');
    }

    public function datatable(Request $request, DataTables $dataTables)
    {
        try {
            $model = Product::query()
                ->select([
                    'products.code',
                    'products.name',
                    'products.stock',
                    'satuans.nama AS satuan',
                    'products.supplier_price',
                    'products.last_price',
                    'products.avg_price',
                    'products.created_at',
                    'products.updated_at'
                ])
                ->leftJoin('satuans','products.satuan_id','=','satuans.id');

            return $dataTables->eloquent($model)->toJson();
        } catch (\Throwable $throwable) {
            return response()->json([
                'status' => 'error',
                'message' => $throwable->getMessage(),
                'details' => $throwable
            ]);
        }
    }

    public function exportExcel()
    {
        return (new ProductStockExport())->download('laporan_stok_'.date('Y-m-d_H-i-s').'.xlsx');
    }
}
