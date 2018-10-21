<?php

namespace App\Http\Controllers;

use Log;
use DB;
use Illuminate\Http\Request;
use \Datetime;
use Illuminate\Support\Facades\Auth;

class KBMController extends Controller {
    /**
     * Mengambil list jadwal KBM berdasarkan timestamp format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
     public function fetchJadwalKBMAll($timestamp) {
         $sql = <<<EOF
                 SELECT kj.id, kl.nama_kelas, kj.lokasi, en.field_02 lv_pembina, kj.jam_mulai, kj.jam_selesai, kp.id presensi_id, kp.status_presensi, count(kpd.jamaah_id) ttl_peserta
                 FROM kelas_jadwal kj
                     inner join kelas kl on kj.kelas_id = kl.id
                     inner join majelis_taklim mt on kj.mt_id = mt.id
                         inner join m_pilihan en on mt.lv_pembina = en.id
                     left outer join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and DATE(kp.tanggal_presensi) = STR_TO_DATE(:timestamp, '%d-%m-%Y')
                     left outer join kelas_presensi_detail kpd on kp.id = kpd.kelas_presensi_id
                 WHERE
                     kj.hari = :day
                 GROUP BY kj.id, kl.nama_kelas, kj.lokasi, en.field_02, kj.jam_mulai, kj.jam_selesai, kp.status_presensi
                 ORDER BY kj.jam_mulai, kj.jam_selesai, kl.nama_kelas
EOF;

         $date = DateTime::createFromFormat('d-m-Y', $timestamp);
         $bindparams = ['timestamp' => $date->format('d-m-Y'), 'day' => $date->format('D')];
         Log::info('params: ' . json_encode($bindparams));

         $result = DB::select($sql, $bindparams);
         return response()->json($result);
     }

    public function fetchJadwalKBMAllV2($timestamp) {
        $sql = <<<EOF
        SELECT kj.id, kl.nama_kelas, kj.lokasi, en.field_02 lv_pembina, kj.jam_mulai, kj.jam_selesai, kp.id presensi_id, kp.status_presensi, count(kpd.jamaah_id) ttl_peserta
               , CAST(sum(case when kpd.keterangan = 'A' then 1 else 0 end) as INT) AS A, CAST(sum(case when kpd.keterangan = 'I' then 1 else 0 end) as INT) AS I, CAST(sum(case when kpd.keterangan = 'H' then 1 else 0 end) as INT) AS H
        FROM kelas_jadwal kj
            inner join kelas kl on kj.kelas_id = kl.id
            inner join majelis_taklim mt on kj.mt_id = mt.id
                inner join m_pilihan en on mt.lv_pembina = en.id
            left outer join kelas_presensi kp on kj.id = kp.kelas_jadwal_id and DATE(kp.tanggal_presensi) = STR_TO_DATE(:timestamp1, '%d-%m-%Y')
            left outer join kelas_presensi_detail kpd on kp.id = kpd.kelas_presensi_id
        WHERE
            kj.hari = :day AND
            kj.status_aktif = 'A' AND
            (date(kj.tanggal_aktif)<=STR_TO_DATE(:timestamp2, '%d-%m-%Y')) AND (date(kj.tanggal_inaktif)>=STR_TO_DATE(:timestamp3, '%d-%m-%Y'))
        GROUP BY kj.id, kl.nama_kelas, kj.lokasi, en.field_02, kj.jam_mulai, kj.jam_selesai, kp.status_presensi
        ORDER BY kj.jam_mulai, kj.jam_selesai, kl.nama_kelas
EOF;

        $date = DateTime::createFromFormat('d-m-Y', $timestamp);
        $bindparams = ['timestamp1' => $date->format('d-m-Y'), 'day' => $date->format('D'), 'timestamp2' => $date->format('d-m-Y'), 'timestamp3' => $date->format('d-m-Y')];
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
                inner join kelas_jamaah km on kj.kelas_id = km.kelas_id and km.status_aktif = 'A' and km.tanggal_aktif < STR_TO_DATE(?, '%d-%m-%Y') AND km.tanggal_inaktif >= STR_TO_DATE(?, '%d-%m-%Y')
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
            $result2 = DB::insert($sql2, [$idPresensi, 'system', $date->format('d-m-Y'), $date->format('d-m-Y'), $jadwalID]);

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

    /**
     * Untuk mendapatkan informasi siapa yang mengupdate presensi siswa
     *
     * Dipanggil di menu presensi, tombol info
     *
     * @param $presensiId kelas_presensi_id
     * @param $jamaahId jamaah_id
     */
    public function getPresensiWhoUpdate($presensiId, $jamaahId) {
        $sql = <<<EOF
        select p.updated_date, p.updated_by , u.nama nama_lengkap
        from kelas_presensi_detail p
            left outer join users u on p.updated_by = u.username
        where kelas_presensi_id = :kp_id and jamaah_id = :jm_id
EOF;

        $result = DB::select($sql, ['kp_id' => $presensiId, 'jm_id' => $jamaahId]);

        return response()->json($result[0]);
    }

    public function getPresensiStatistik($presensiId) {
        $sql = <<<EOF
        select kp.id, kp.sys_creation_date, kp.created_by, u.nama nama_lengkap, kpd.keterangan, count(kpd.jamaah_id) total
        from kelas_presensi kp
            left outer join kelas_presensi_detail kpd on kp.id = kpd.kelas_presensi_id
            left outer join users u on kp.created_by = u.username
        where kp.id = :kp_id
        group by kpd.keterangan;
EOF;

        $result = DB::select($sql, ['kp_id' => $presensiId]);

        // convert object into array
        $resultArr = array_map(function ($value) {
            return (array)$value;
        }, $result);
        $result1 = $resultArr[0];

        // calculation
        $statistikArr = array('H' => 0, 'A' => 0, 'I' => 0);
        $resultCount = count($resultArr);
        for ($i = 0; $i < $resultCount; $i++) {
            $value = $resultArr[$i];
            $statistikArr[$value['keterangan']] = $value['total'];
        }
        $finalResult = array('id' => $result1['id'], 'created_by' => $result1['created_by']
                , 'created_date' => $result1['sys_creation_date'], 'nama_lengkap' => $result1['nama_lengkap']
                , 'statistik' => $statistikArr);

        return response()->json($finalResult);
    }

    /**
    * Hapus Presensi by presensiID, di db ada trigger untuk menghapus presensi_detail
    * $request->input() in array
    */
    public function deletePresensi(Request $request, $presensiId) {
        $user = Auth::user();
        $user = $request->user();

        $sql0 = <<<EOF
            SELECT kelas_presensi_id
            FROM kelas_presensi_detail
            WHERE kelas_presensi_id = :presensiId AND keterangan != 'A'
EOF;

        $sql = <<<EOF
            DELETE FROM kelas_presensi WHERE id = :presensiId
EOF;

        $resultCount = DB::select($sql0, ['presensiId' => $presensiId]);
        if (count($resultCount) == 0) {
            DB::delete($sql, ['presensiId' => $presensiId]);
            return response()->json(["response_status" => "success", "message" => "Data berhasil dihapus."]);
        } else {
            return response()->json(["response_status" => "failed", "message" => "Gagal menghapus! semua peserta harus ditandai ke Alpa terlebih dahulu"], 409);
        }
    }
}
