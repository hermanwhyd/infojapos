<?php

namespace App\Http\Controllers;

use DB;

class EnumsController extends Controller
{
    /**
     * Mengambil seluruh data jamaah dari database
     */
    public function fetchByGrup($grupStrList) {
        $result = [];
        $sql = <<<EOF
                SELECT 
                    id, FIELD_01 value
                FROM 
                    M_PILIHAN
                WHERE 
                    GRUP = (:grup)
                ORDER BY GRUP, POSISI ASC
EOF;

        $grupList = explode(';', $grupStrList);
        foreach($grupList as $grup) {
            $result[$grup] = DB::select($sql, ['grup' => $grup]);
        }
        return response()->json($result);
    }
}
