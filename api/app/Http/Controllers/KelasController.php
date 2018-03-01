<?php

namespace App\Http\Controllers;

use Log;
use DB;
use Illuminate\Http\Request;

class KelasController extends Controller {
    /**
     * Mengambil data kelas yang aktif
     * @param String timestamp format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
    public function getKelasAktif($timestamp) {
      $sql = <<<EOF
        SELECT 
          distinct ks.id, ks.nama_kelas, es.field_01 lv_pembinaan, em.field_02 lv_pembina, mt.nama_mt
        FROM
          kelas_jadwal kj
          inner join kelas ks on kj.kelas_id = ks.id
          inner join m_pilihan es on ks.lv_pembinaan = es.id
          inner join majelis_taklim mt on kj.mt_id = mt.id
          inner join m_pilihan em on mt.lv_pembina = em.id
          order by ks.nama_kelas
EOF;
      
      // $date = DateTime::createFromFormat('d-m-Y', $timestamp);
      
      $result = DB::select($sql);

      return response()->json($result);
  }
}
