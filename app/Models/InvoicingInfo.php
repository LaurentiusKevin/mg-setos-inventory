<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @method static find($id)
 */
class InvoicingInfo extends Model
{
    use HasFactory;

    /**
     * @param int|null $department_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @return Builder
     */
    public static function getLaporanDetailPerDepartment(int $department_id = null, string $start_date = null, string $end_date = null): Builder
    {
        $data = DB::table(DB::raw('departments d'))
            ->select([
                'ii.id',
                'ii.store_requisition_info_id',
                'ii.user_id',
                'users.name AS penginput',
                'd.name AS department_name',
                'ii.invoice_number',
                'ii.info_penggunaan',
                'ii.total_item',
                DB::raw('sum(ip.price) AS total_price'),
                'ii.catatan',
                'ii.completed_at',
                'ii.created_at'
            ])
            ->leftJoin(DB::raw('store_requisition_infos sri'),'d.id','=','sri.department_id')
            ->leftJoin(DB::raw('invoicing_infos ii'),'sri.id','=','ii.store_requisition_info_id')
            ->leftJoin(DB::raw('invoicing_products ip'),'ii.id','=','ip.invoicing_info_id')
            ->leftJoin('users','ii.user_id','=','users.id')
            ->whereNotNull('ii.completed_at')
            ->groupBy([
                'ii.id',
                'ii.store_requisition_info_id',
                'ii.user_id',
                'd.name',
                'ii.invoice_number',
                'ii.info_penggunaan',
                'ii.total_item',
                'ii.total_price',
                'ii.catatan',
                'ii.completed_at',
                'ii.created_at'
            ]);

        if ($department_id !== null) {
            $data->where('d.id','=',$department_id);
        }

        if ($start_date !== null && $end_date !== null) {
            $data->whereBetween('ii.created_at',[$start_date,$end_date]);
        }

        return $data;
    }

    /**
     * @param string|null $start_date
     * @param string|null $end_date
     * @return Builder
     */
    public static function getLaporanSummaryPerDepartment(string $start_date = null, string $end_date = null): Builder
    {
        $data = DB::table(DB::raw('departments d'))
            ->select([
                'd.name AS department_name',
                DB::raw('sum(ip.price) AS total_price')
            ])
            ->leftJoin(DB::raw('store_requisition_infos sri'),'d.id','=','sri.department_id')
            ->leftJoin(DB::raw('invoicing_infos ii'),'sri.id','=','ii.store_requisition_info_id')
            ->leftJoin(DB::raw('invoicing_products ip'),'ii.id','=','ip.invoicing_info_id')
            ->leftJoin('users','ii.user_id','=','users.id')
            ->whereNotNull('ii.completed_at')
            ->orderBy('d.name')
            ->groupBy([
                'd.name'
            ]);

        if ($start_date !== null && $end_date !== null) {
            $data->whereBetween('ii.created_at',[$start_date,$end_date]);
        }

        return $data;
    }
}
