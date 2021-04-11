<?php

namespace App\Services\Admin\Stock;

use App\Helpers\CounterHelper;
use App\Models\PenggunaanBarangInfo;
use App\Models\PenggunaanBarangProduct;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Repositories\Admin\Stock\PenggunaanBarangRepository;
use Illuminate\Support\Facades\Auth;

class PenggunaanBarangService
{
    private $repository;

    public function __construct(PenggunaanBarangRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getData()
    {
        return $this->repository->penggunaanBarangInfo();
    }

    public function getProductList($selected_product_id)
    {
        return $this->repository->productList($selected_product_id);
    }

    public function store($product,$info_penggunaan,$catatan)
    {
        try {
            $total_item = 0;
            foreach ($product AS $item) {
                $total_item += $item['quantity'];
            }

            $invoice_number = CounterHelper::getNewCode('PB');

            $info = new PenggunaanBarangInfo();
            $info->user_id = Auth::id();
            $info->invoice_number = $invoice_number;
            $info->info_penggunaan = $info_penggunaan;
            $info->total_item = $total_item;
            $info->catatan = $catatan;
            $info->save();

            foreach ($product AS $item) {
                $product_detail = Product::find($item['product_id']);
                $product_detail->stock -= $item['quantity'];
                $product_detail->save();

                $product_digunakan = new PenggunaanBarangProduct();
                $product_digunakan->penggunaan_barang_info_id = $info->id;
                $product_digunakan->product_id = $item['product_id'];
                $product_digunakan->quantity = $item['quantity'];
                $product_digunakan->save();

                $product_transaction = new ProductTransaction();
                $product_transaction->user_id = Auth::id();
                $product_transaction->product_id = $item['product_id'];
                $product_transaction->price = $product_detail->last_price;
                $product_transaction->in = 0;
                $product_transaction->out = $item['quantity'];
                $product_transaction->saldo = 0;
                $product_transaction->save();
            }

            return response()->json([
                'status' => 'success',
                'invoice_number' => $invoice_number,
                'invoice_pdf' => route('admin.stock.penggunaan-barang.view.invoice',[$info->id]),
                'redirect' => route('admin.stock.penggunaan-barang.view.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function indexInfoData($penggunaan_barang_info_id)
    {
        return [
            'info' => $this->repository->penggunaanBarangInfo($penggunaan_barang_info_id)->first(),
            'product' => $this->repository->penggunaanBarangProduct($penggunaan_barang_info_id)->get()
        ];
    }
}
