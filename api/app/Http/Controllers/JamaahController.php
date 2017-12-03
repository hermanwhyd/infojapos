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
                    id, gelar_depan, nama_lengkap, gelar_belakang, tempat_lahir, DATE_FORMAT(tanggal_lahir,'%Y-%m-%dT%TZ') tanggal_lahir 
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
            id, nama_panggilan, gelar_depan, nama_lengkap, gelar_belakang, inisial_khusus, tempat_lahir
            , jenis_kelamin, golongan_darah, status_pernikahan, status_kehidupan, nama_ayah_kandung, nama_ibu_kandung
            , DATE_FORMAT(tanggal_lahir,'%d/%m/%Y') tanggal_lahir, DATE_FORMAT(tanggal_meninggal,'%Y-%m-%dT%TZ') tanggal_meninggal
            , tempat_tinggal, pekerjaan_lainnya, pekerjaan_id
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
    public function delete($id) {
        $sql = <<<EOF
        DELETE FROM
            jamaah
        WHERE 
            id = :id
EOF;

        $result = DB::delete($sql, ['id' => $id]);
        return $result;
    }
}
