<?php

namespace App\Http\Controllers;

use Log;
use DB;
use \DateTime;
use Illuminate\Http\Request;

class KelasController extends Controller {
    /**
     * Mengambil data kelas yang aktif
     * @param String timestamp format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
    public function getKelasAktif($timestamp1, $timestamp2) {
      $sql = <<<EOF
      SELECT
        kl.id, kl.nama_kelas, e1.field_01 lv_pembinaan, mt.nama_mt, e2.field_02 lv_pembina, COUNT(kp.id) ttl_kbm
      FROM kelas kl
        inner join m_pilihan e1 on kl.lv_pembinaan = e1.id
        inner join majelis_taklim mt on kl.mt_id = mt.id
        inner join m_pilihan e2 on mt.lv_pembina = e2.id
        inner join kelas_jadwal kj on kl.id = kj.kelas_id
        left outer join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and (kp.tanggal_presensi >= STR_TO_DATE(:date1, '%d-%m-%Y') and kp.tanggal_presensi < ADDDATE(STR_TO_DATE(:date2, '%d-%m-%Y'), 1))
      GROUP BY
        kl.id, kl.nama_kelas, mt.nama_mt, e1.field_01, e2.field_01
      ORDER BY kl.nama_kelas
EOF;
      
      $date1 = DateTime::createFromFormat('d-m-Y', $timestamp1);
      $date2 = DateTime::createFromFormat('d-m-Y', $timestamp2);
      
      $result = DB::select($sql, [$date1->format('d-m-Y'), $date2->format('d-m-Y')]);

      return response()->json($result);
  }
}
