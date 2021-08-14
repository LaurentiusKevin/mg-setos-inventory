<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MutasiStockController extends Controller
{
    public function index()
    {
        return view('admin.laporan.mutasi-stock.index');
    }

    public function datatable(Request $request, DataTables $dataTables): JsonResponse
    {
        try {
            $product_id = $request->get('product_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            $model = ProductTransaction::query()
                ->select([
                    'product_transactions.id',
                    'users.name AS penginput',
                    'product_id',
                    'invoice_number',
                    'type',
                    'price',
                    'in',
                    'out',
                    'saldo',
                    'product_transactions.created_at',
                    'product_transactions.updated_at'
                ])
                ->leftJoin('users', 'product_transactions.user_id', '=', 'users.id')
                ->where('product_id', '=', $product_id)
                ->whereBetween('product_transactions.created_at', [$startDate, $endDate]);

            return $dataTables
                ->eloquent($model)
                ->toJson();
        } catch (\Throwable $throwable) {
            return response()->json([
                'status' => 'error',
                'message' => $throwable->getMessage(),
                'details' => [
                    $throwable->getFile(),
                    $throwable->getLine()
                ]
            ], 500);
        }
    }

    public function productList(Request $request)
    {
        $search = $request->get('search');

        try {
            $product = Product::query()
                ->select([
                    'id',
                    'code',
                    'name',
                    DB::raw("concat(code,' - ',name) AS text")
                ])
                ->where('code', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->paginate(10);

            return response()->json([
                'results' => $product->items(),
                'pagination' => [
                    'more' => $product->hasMorePages()
                ]
            ]);
        } catch (\Throwable $throwable) {
            return response()->json([
                'status' => 'error',
                'message' => $throwable->getMessage(),
                'details' => [
                    $throwable->getFile(),
                    $throwable->getLine()
                ]
            ], 500);
        }
    }
}
