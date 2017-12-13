<?php

namespace App\Http\Controllers;

use Log;
use DB;
use Illuminate\Http\Request;

class KBMController extends Controller {
    /**
     * Mengambil list jadwal KBM berdasarkan tanggal (unix timestamp)
     */
    public function fetchJadwalKBMAll($timestamp) {
        $sql = <<<EOF
                SELECT kj.id, kl.nama_kelas, kj.lokasi, mt.tipe_mt as pembinaan, kj.jam_mulai, kj.jam_selesai, kp.status_presensi
                FROM kelas_jadwal kj
                    inner join kelas kl on kj.kelas_id = kl.id
                    inner join majelis_taklim mt on kj.mt_id = mt.id
                    left outer join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and DATE(kp.tanggal_presensi) = DATE(from_unixtime(:timestamp))
                WHERE
                    kj.hari = :day
                ORDER BY kl.nama_kelas, kj.jam_mulai
EOF;

        $bindparams = ['timestamp' => $timestamp, 'day' => date('w', $timestamp)];
        Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql, $bindparams);
        return response()->json($result);
    }

    /**
     * Mengambil data siswa (jamaah) berdasarkan id jadwal
     */
    public function fetchSiswaByJadwal($id) {
        $sql1 = <<<EOF
                SELECT kj.id, kl.nama_kelas, mt.tipe_mt
                FROM kelas_jadwal kj
                    inner join kelas kl on kj.kelas_id = kl.id
                    inner join majelis_taklim mt on kj.mt_id = mt.id
                WHERE kj.id = :j_id
EOF;

        $sql2 = <<<EOF
                SELECT kj.id, jm.nama_panggilan, jm.nama_lengkap, mt.inisial kelompok
                FROM kelas_jadwal kj
                    inner join kelas_jamaah km on kj.kelas_id = km.kelas_id
                    inner join jamaah jm on km.jamaah_id = jm.id
                    inner join kelas kl on km.kelas_id = kl.id
                    inner join sambung_his sb on jm.id = sb.jamaah_id and sb.status_aktif = 'A' and sb.tanggal_aktif < now()
                    inner join majelis_taklim mt on sb.mt_id = mt.id
                WHERE kj.id = :j_id
                ORDER BY jm.nama_lengkap, mt.inisial
EOF;

        // Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql1, ['j_id' => $id]);
        $result2 = DB::select($sql2, ['j_id' => $id]);

        $jadwal = $result[0];
        // put ke array parent
        $jadwal->listSiswa = $result2;
        return response()->json($jadwal);
    }

    /**
     * Mengambil data siswa (jamaah) berdasarkan id jadwal yang ada di data absensi
     * 
     * @param id Integer kelas_jadwal::id
     * @param timestamp Timestamp kelas_presensi:tanggal_presensi
     */
    public function fetchPresensiByJadwal($id, $timestamp) {
        $sql1 = <<<EOF
                SELECT kj.id, kl.nama_kelas, mt.tipe_mt
                FROM kelas_jadwal kj
                    inner join kelas kl on kj.kelas_id = kl.id
                    inner join majelis_taklim mt on kj.mt_id = mt.id
                WHERE kj.id = :j_id
EOF;

        $sql2 = <<<EOF
                SELECT kj.id, kpd.jamaah_id, jm.nama_panggilan, jm.nama_lengkap, mt.inisial kelompok, kpd.keterangan
                FROM kelas_jadwal kj
                    inner join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and kp.tanggal_presensi = from_unixtime(:timestamp)
                    inner join kelas_presensi_detail kpd on kp.id = kpd.kelas_presensi_id
                    inner join jamaah jm on kpd.jamaah_id = jm.id
                    inner join sambung_his sb on jm.id = sb.jamaah_id and sb.status_aktif = 'A' and sb.tanggal_aktif < kp.tanggal_presensi
                    inner join majelis_taklim mt on sb.mt_id = mt.id
                WHERE kj.id = :j_id
                ORDER BY jm.nama_lengkap, mt.inisial
EOF;

        // Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql1, ['j_id' => $id]);
        $result2 = DB::select($sql2, ['j_id' => $id, 'timestamp' => $timestamp]);

        $jadwal = $result[0];
        // put ke array parent
        $jadwal->listSiswa = $result2;
        return response()->json($jadwal);
    }

    /**
    * Update Presensi Ket by presensiID
    */
    public function createNewPresensi(Request $request, $jadwalID) {
        $sql1 = <<<EOF
        INSERT INTO kelas_presensi (kelas_jadwal_id, tanggal_presensi, status_presensi)
            VALUES (?,from_unixtime(?),?)
EOF;

        $sql2 = <<<EOF
        INSERT INTO kelas_presensi_detail (kelas_presensi_id, jamaah_id)
            SELECT ? kelas_presensi_id, km.jamaah_id
            FROM kelas_jadwal kj
                inner join kelas_jamaah km on kj.kelas_id = km.kelas_id
                inner join jamaah jm on km.jamaah_id = jm.id
            WHERE kj.id = ?
            ORDER BY jm.nama_lengkap                
EOF;

        $result = DB::insert($sql1, [ $jadwalID,$request->input('timestamp_presensi'),'Parsial' ]);
        $idPresensi = DB::connection() -> getPdo() -> lastInsertId();

        $result2 = DB::insert($sql2, [ $idPresensi,$jadwalID ]);

        return response()->json(["ResponseStatus" => "success", "SiswaTotal" => $result2]);
    }

    /**
    * Update Presensi Ket by presensiID
    */
    public function updatePresensi(Request $request, $presensiID) {
        $sql = <<<EOF
        UPDATE
            kelas_presensi_detail SET keterangan=?
        WHERE
            kelas_presensi_id=? and jamaah_id=?
EOF;

        $result = DB::update($sql, [ $request->input('keterangan'),$presensiID,$request->input('jamaah_id')]);
        return response()->json(["ResponseStatus" => "success", "RowUpdated" => $result]);
    }
}