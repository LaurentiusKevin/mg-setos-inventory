<?php

namespace App\Helpers;

use App\Models\CodeCounter;
use stdClass;

class CounterHelper
{
    public static function getNewCode($kode)
    {
        $tahun = date('Y');
        $bulan = date('n');

        $data = CodeCounter::firstOrCreate(
            ['kode' => $kode, 'tahun' => $tahun, 'bulan' => $bulan],
            ['counter' => 1]
        );

        $tahun = date('y',strtotime($data->tahun));
        $bulan = str_pad($data->bulan,2,'0',STR_PAD_LEFT);
        $counter = str_pad($data->counter,5,'0',STR_PAD_LEFT);

        $code = "{$data->kode}/{$tahun}{$bulan}/{$counter}";

        $data->counter++;
        $data->save();

        return $code;
    }

    public function newCode($kode): string
    {
        $tahun = date('Y');
        $bulan = date('n');

        $data = CodeCounter::firstOrCreate(
            ['kode' => $kode, 'tahun' => $tahun, 'bulan' => $bulan],
            ['counter' => 1]
        );

        $tahun = date('y',strtotime($data->tahun));
        $bulan = str_pad($data->bulan,2,'0',STR_PAD_LEFT);
        $counter = str_pad($data->counter,5,'0',STR_PAD_LEFT);

        $code = "{$data->kode}/{$tahun}{$bulan}/{$counter}";

        $data->counter++;
        $data->save();

        return $code;
    }
}
