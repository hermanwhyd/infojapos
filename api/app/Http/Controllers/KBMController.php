<?php

namespace App\Http\Controllers;

use Log;
use DB;
use Illuminate\Http\Request;
use \Datetime;
use Illuminate\Support\Facades\Auth;

class KBMController extends Controller {
    /**
     * Mengambil list jadwal KBM berdasarkan timestamp format 'dd-mm-yyyy', e.g. '01/09/2017'
     */
    public function fetchJadwalKBMAll($timestamp) {
        $sql = <<<EOF
                SELECT kj.id, kl.nama_kelas, kj.lokasi, en.field_02 lv_pembina, kj.jam_mulai, kj.jam_selesai, kp.status_presensi
                FROM kelas_jadwal kj
                    inner join kelas kl on kj.kelas_id = kl.id
                    inner join majelis_taklim mt on kj.mt_id = mt.id
                        inner join m_pilihan en on mt.lv_pembina = en.id
                    left outer join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and DATE(kp.tanggal_presensi) = STR_TO_DATE(:timestamp, '%d-%m-%Y')
                WHERE
                    kj.hari = :day
                ORDER BY kj.jam_mulai, kj.jam_selesai, kl.nama_kelas
EOF;
        
        $date = DateTime::createFromFormat('d-m-Y', $timestamp);
        $bindparams = ['timestamp' => $date->format('d-m-Y'), 'day' => $date->format('D')];
        Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql, $bindparams);
        return response()->json($result);
    }

    /**
     * Mengambil data siswa (jamaah) berdasarkan id jadwal
     */
    public function fetchSiswaByJadwal($scdID) {
        $sql1 = <<<EOF
                SELECT kj.id, kl.nama_kelas, en.field_02 lv_pembina
                FROM kelas_jadwal kj
                    inner join kelas kl on kj.kelas_id = kl.id
                    inner join majelis_taklim mt on kj.mt_id = mt.id
                        inner join m_pilihan en on mt.lv_pembina = en.id
                WHERE kj.id = :j_id
EOF;

        $sql2 = <<<EOF
                SELECT jm.id, jm.nama_panggilan, jm.nama_lengkap, jm.jenis_kelamin, mt.inisial kelompok
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

        $result = DB::select($sql1, ['j_id' => $scdID]);
        $result2 = DB::select($sql2, ['j_id' => $scdID]);

        $jadwal = $result[0];
        // put ke array parent
        $jadwal->list_siswa = $result2;
        return response()->json($jadwal);
    }

    /**
     * Mengambil data siswa (jamaah) berdasarkan id jadwal yang ada di data absensi
     * 
     * @param scdID Integer kelas_jadwal::id
     * @param timestamp Date kelas_presensi:tanggal_presensi, timestamp format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
    public function fetchPresensiByJadwal($scdID, $timestamp) {
        $sql1 = <<<EOF
                SELECT kp.id, kl.nama_kelas, en.field_02 lv_pembina
                FROM kelas_jadwal kj
                    inner join kelas kl on kj.kelas_id = kl.id
                    inner join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and DATE(kp.tanggal_presensi) = DATE(STR_TO_DATE(:timestamp, '%d-%m-%Y'))
                    inner join majelis_taklim mt on kj.mt_id = mt.id
                    inner join m_pilihan en on mt.lv_pembina = en.id
                WHERE kj.id = :j_id
EOF;

        $sql2 = <<<EOF
                SELECT kpd.jamaah_id, jm.nama_panggilan, jm.nama_lengkap, jm.jenis_kelamin, mt.inisial kelompok, kpd.keterangan, kpd.alasan
                FROM kelas_jadwal kj
                    inner join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and DATE(kp.tanggal_presensi) = DATE(STR_TO_DATE(:timestamp, '%d-%m-%Y'))
                    inner join kelas_presensi_detail kpd on kp.id = kpd.kelas_presensi_id
                    inner join jamaah jm on kpd.jamaah_id = jm.id
                    inner join sambung_his sb on jm.id = sb.jamaah_id and sb.status_aktif = 'A' and sb.tanggal_aktif < kp.tanggal_presensi
                    inner join majelis_taklim mt on sb.mt_id = mt.id
                WHERE kj.id = :j_id
                ORDER BY jm.nama_lengkap, mt.inisial
EOF;

        $date = DateTime::createFromFormat('d-m-Y', $timestamp);
        Log::info('Datetime: ' . json_encode(['j_id' => $scdID, 'timestamp' => $date->format('d-m-Y')]));
        
        $result = DB::select($sql1, ['j_id' => $scdID, 'timestamp' => $date->format('d-m-Y')]);
        $result2 = DB::select($sql2, ['j_id' => $scdID, 'timestamp' => $date->format('d-m-Y')]);

        if (count($result) > 0) {
            $jadwal = $result[0];
            // put ke array parent
            $jadwal->list_siswa = $result2;
            return response()->json($jadwal);
        } else {
            return response()->json(["response_status" => "BusinessError", "message" => "Jadwal KBM tidak ada. Silakan pilih tanggal lain!"], 404);
        }
    }

    /**
    * Update Presensi Ket by presensiID
    */
    public function createNewPresensi(Request $request, $jadwalID) {
        $sql0 = <<<EOF
        SELECT count(*) count FROM kelas_presensi 
            WHERE kelas_jadwal_id = ? and DATE(tanggal_presensi) = STR_TO_DATE(?, '%d-%m-%Y')
EOF;

        $sql1 = <<<EOF
        INSERT INTO kelas_presensi (kelas_jadwal_id, tanggal_presensi, status_presensi, created_by)
            VALUES (?,STR_TO_DATE(?, '%d-%m-%Y'),?,?)
EOF;

        $sql2 = <<<EOF
        INSERT INTO kelas_presensi_detail (kelas_presensi_id, jamaah_id, updated_by)
            SELECT ? kelas_presensi_id, km.jamaah_id, ? updated_by
            FROM kelas_jadwal kj
                inner join kelas_jamaah km on kj.kelas_id = km.kelas_id and km.status_aktif = 'A' and km.tanggal_aktif < STR_TO_DATE(?, '%d-%m-%Y')
                inner join jamaah jm on km.jamaah_id = jm.id
            WHERE kj.id = ?
            ORDER BY jm.nama_lengkap
EOF;

        $date = DateTime::createFromFormat('d-m-Y', $request->input('timestamp'));
        $count = DB::select($sql0, [ $jadwalID,$date->format('d-m-Y') ]);

        if ($count[0]->count == 0) {
            $user = Auth::user();
            $user = $request->user();
            
            $result = DB::insert($sql1, [ $jadwalID,$date->format('d-m-Y'),'Parsial',$user->username ]);
            $idPresensi = DB::connection()->getPdo()->lastInsertId();
            $result2 = DB::insert($sql2, [$idPresensi, 'system', $date->format('d-m-Y'), $jadwalID]);

            return response()->json(["response_status" => "success", "message" => "Result: " . $result2]);
        } else {
            return response()->json(["response_status" => "BusinessError", "message" => "Presensi sudah di ada. Silakan reload aplikasi atau pilih tanggal lain!"], 409);
        }
    }

    /**
    * Update Presensi Ket by presensiID
    * $request->input() in array
    */
    public function updatePresensi(Request $request, $presensiID) {
        $user = Auth::user();
        $user = $request->user();

        $sql = <<<EOF
        UPDATE
            kelas_presensi_detail SET keterangan=?, alasan=?, updated_by=?
        WHERE
            kelas_presensi_id=? and jamaah_id=?
EOF;

        $presences = $request->input('list_siswa');
        foreach($presences as $presence) {
            DB::update($sql, [ $presence['keterangan'],$presence['alasan'],$user->username,$presensiID,$presence['jamaah_id'] ]);
        }
        
        return response()->json(["response_status" => "success", "message" => "Data berhasil diupdate."]);
    }
}
