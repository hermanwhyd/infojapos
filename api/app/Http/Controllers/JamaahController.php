<?php

namespace App\Http\Controllers;

use DB;

class JamaahController extends Controller
{
    /**
     * Mengambil seluruh data jamaah dari database
     */
    public function fetchAll() {
        $sql = <<<EOF
                SELECT
                    id, nama_lengkap, tempat_lahir, DATE_FORMAT(tanggal_lahir,'%Y-%m-%dT%TZ') tanggal_lahir 
                FROM
                    jamaah
EOF;

        $result = DB::select($sql);
        return response()->json($result);
    }

    /**
     * Mengambil satu data jamaah dari database
     */
    public function fetchOne($id) {
        $sql = <<<EOF
        SELECT
            id, nama_lengkap, tempat_lahir, DATE_FORMAT(tanggal_lahir,'%Y-%m-%dT%TZ') tanggal_lahir 
        FROM
            jamaah
        WHERE 
            id = :id
EOF;

        $result = DB::select($sql, ['id' => $id]);
        if ($result)
            return response()->json($result[0], 200);
        else
            return response()->json(['message' => 'Data tidak ditemukan'], 404); 
    }

    /**
    * Menghapus satu data jamaah dari database
    */
    public function Delete($id) {
        $sql = <<<EOF
        DELETE FROM
            jamaah
        WHERE 
            id = :id
EOF;

        $result = DB::select($sql, ['id' => $id]);
        return $result;
    }
}
