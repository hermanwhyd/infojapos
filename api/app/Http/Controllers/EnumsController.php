<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class EnumsController extends Controller {
    /**
     * Mengambil seluruh data dari database
     */
    public function fetchAll() {
        $result = [];
        $sql = "SELECT DISTINCT(grup) grup FROM m_pilihan ORDER BY grup";
        $sql1 = <<<EOF
                SELECT 
                    id, grup, posisi, field_01, field_02, field_03
                FROM 
                    m_pilihan
                WHERE
                    grup = :grup
                ORDER BY GRUP, POSISI ASC
EOF;
        $grupList = DB::select($sql);

        // convert list to array
        $grupList = array_map(function ($value) {
            return (array)$value;
        }, $grupList);

        foreach($grupList as $obj) {
            $grup = $obj['grup'];
            $options = DB::select($sql1, ['grup' => $grup]);
            $objGrup = array("grup"=>$grup, "options"=>$options);
            $result[] = $objGrup; 
        }
        return response()->json($result);
    }

    /**
     * Mengambil satu data dari database
     */
    public function fetchByGrup($grup) {
        $sql = <<<EOF
                SELECT 
                    id, grup, FIELD_01 value
                FROM 
                    m_pilihan
                WHERE 
                    GRUP = :grup
                ORDER BY POSISI ASC
EOF;

        $result = DB::select($sql, ['grup' => $grup]);
        return response()->json($result);
    }

    /**
     * Mengambil satu data dari database
     */
    public function fetchById($id) {
        $sql = <<<EOF
                SELECT 
                    id, grup, field_01, field_02, field_03
                FROM 
                    m_pilihan
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
    * Menghapus satu data dari database
    */
    public function deleteById($id) {
        $sql = <<<EOF
        DELETE FROM
            m_pilihan
        WHERE 
            id = :id
EOF;

        $result = DB::delete($sql, ['id' => $id]);
        return response()->json(["response_status" => "success", "RowDeleted: " => $result]);
    }

    /**
    * Menghapus satu data dari database
    */
    public function deleteByGrup($grup) {
        $sql = <<<EOF
        DELETE FROM
            m_pilihan
        WHERE 
            grup = :grup
EOF;

        $result = DB::delete($sql, ['grup' => $grup]);
        return response()->json(["response_status" => "success", "RowDeleted: " => $result]);
    }

    /**
    * Save satu data dari database
    */
    public function save(Request $request) {
        $sql = <<<EOF
        INSERT INTO
            m_pilihan (GRUP, FIELD_01, FIELD_02, FIELD_03)
        VALUES
            (?,?,?,?)
EOF;

        $result = DB::insert($sql, [ $request->input('grup'),$request->input('field_01'),$request->input('field_02'),$request->input('field_03') ]);
        return response()->json($result);
    }

    /**
    * Update satu data dari database
    */
    public function update(Request $request, $id) {
        $sql = <<<EOF
        UPDATE
            m_pilihan SET GRUP=?, FIELD_01=?, FIELD_02=?, FIELD_03=?
        WHERE
            id=?
EOF;

        $result = DB::update($sql, [ $request->input('grup'),$request->input('field_01'),$request->input('field_02'),$request->input('field_03'), $id ]);
        return response()->json($result);
    }
}
