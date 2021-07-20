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
        'd.id AS department_id',
                'd.name AS department_name',
                'sri.invoice_number AS invoice_number_sr',
                'ii.id',
                'ii.store_requisition_info_id',
                'ii.user_id',
                'ii.penginput',
                'ii.invoice_number AS invoice_number_invoicing',
                'ii.info_penggunaan',
                'ii.total_item',
                'ii.total_price',
                'ii.catatan',
                'ii.completed_at',
                'ii.created_at',
            ])
            ->leftJoin(DB::raw('store_requisition_infos sri'),'d.id','=','sri.department_id')
            ->join(DB::raw(
                "(SELECT ii.id,
                    ii.store_requisition_info_id,
                    ii.user_id,
                    users.name AS penginput,
                    ii.invoice_number,
                    ii.info_penggunaan,
                    ii.total_item,
                    sum(ip.total_price) AS total_price,
                    ii.catatan,
                    ii.completed_at,
                    ii.created_at
                FROM invoicing_infos ii
                    LEFT JOIN (SELECT invoicing_info_id,
                                      product_id,
                                      quantity,
                                      price,
                                      quantity*price AS total_price
                               FROM invoicing_products
                               WHERE deleted_at IS NULL) ip on ii.id = ip.invoicing_info_id
                    LEFT JOIN users ON ii.user_id = users.id
                WHERE ii.completed_at IS NOT NULL
                GROUP BY ii.id,
                         ii.store_requisition_info_id,
                         ii.user_id,
                         ii.invoice_number,
                         ii.info_penggunaan,
                         ii.total_item,
                         ii.total_price,
                         ii.catatan,
                         ii.completed_at,
                         ii.created_at) ii"
            ),function ($join) {
                $join->on('sri.id','=','ii.store_requisition_info_id');
            });

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
        return self::getLaporanDetailPerDepartment(null,$start_date,$end_date)
            ->select([
                'd.id AS department_id',
                'd.name AS department_name',
                DB::raw('SUM(ii.total_price) AS total_price')
            ])
            ->orderBy('d.name')
            ->groupBy([
                'd.name'
            ]);
    }
}
