<?php

namespace App\Http\Controllers;

use Log;
use DB;
use \Datetime;
use Illuminate\Http\Request;
use \Excel;
// use Illuminate\Support\Facades\Auth;

class StatistikController extends Controller {
    /**
     * Mengambil list statistik kelas per periode (timestamp1 sampai timestamp2)
     * @param String kelasId
     * @param String timestamp1 format 'dd-mm-yyyy', e.g. '01-09-2017'
     * @param String timestamp2 format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
    public function statistikKelasPerPeriode($id, $timestamp1, $timestamp2) {
        $sql0 = <<<EOF
        select 
            kl.id, kl.nama_kelas, en1.field_01 lv_pembinaan
        from kelas kl
            inner join m_pilihan en1 on kl.lv_pembinaan = en1.id
        where kl.id = :id
EOF;

        $sql1 = <<<EOF
        select 
            kl.nama_kelas, WEEKOFYEAR(kp.tanggal_presensi) woy, YEAR(kp.tanggal_presensi) year, COALESCE(mm.week_label_sb, WEEKOFYEAR(kp.tanggal_presensi)) week_label_sb, pd.keterangan, count(pd.keterangan) total
        from
            kelas_presensi kp
            inner join kelas_jadwal jw on kp.kelas_jadwal_id = jw.id
            inner join kelas kl on jw.kelas_id = kl.id
            right outer join m_minggu_sb mm on mm.weekofyear = WEEKOFYEAR(kp.tanggal_presensi) and mm.year = YEAR(kp.tanggal_presensi)
            right outer join kelas_presensi_detail pd on pd.kelas_presensi_id = kp.id
        where
            (kp.tanggal_presensi >= STR_TO_DATE(:date1, '%d-%m-%Y') and kp.tanggal_presensi < ADDDATE(STR_TO_DATE(:date2, '%d-%m-%Y'), 1))
            and jw.kelas_id = :id
        group by
            kl.nama_kelas, year, woy, pd.keterangan
        order by year, woy, pd.keterangan
EOF;
        
        $date1 = DateTime::createFromFormat('d-m-Y', $timestamp1);
        $date2 = DateTime::createFromFormat('d-m-Y', $timestamp2);
        $bindparams = ['id' => $id, 'date1' => $date1->format('d-m-Y'), 'date2' => $date2->format('d-m-Y')];
        Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql1, $bindparams);

        // convert object into array
        $resultArr = array_map(function ($value) {
            return (array)$value;
        }, $result);

        // main result
        $statistikResult = array();
        $currKelas = NULL;
        $currKelasStt = array('H'=>0, 'A'=>0, 'I'=>0);
        $resultCount = count($resultArr);
        for ($i = 0; $i < $resultCount; $i++) {
            $value = $resultArr[$i];
            
            // init first array
            if ($currKelas == NULL) {
                $currKelas = array('label' => $value['week_label_sb'], 'statistik' => NULL);
            }

            if ($currKelas['label'] == $value['week_label_sb']) {
                $currKelasStt[$value['keterangan']] = $value['total'];
            } else {
                // add into final result
                $currKelas['statistik'] = $currKelasStt;
                $statistikResult[] = $currKelas;

                // add new peserta
                $currKelas = array('label' => $value['week_label_sb'], 'statistik' => NULL);
                $currKelasStt = array('H'=>0, 'A'=>0, 'I'=>0);
                $currKelasStt[$value['keterangan']] = $value['total'];
            }

            if ($i == ($resultCount - 1)) {
                $currKelas['statistik'] = $currKelasStt;
                $statistikResult[] = $currKelas;
            }
        }

        $bindparams0 = ['id' => $id];
        $result0 = DB::select($sql0, $bindparams0);
        $result0[0]->statistik_list = $statistikResult;

        return response()->json($result0[0]);
    }

    /**
     * Mengambil list statistik siswa per periode (timestamp1 sampai timestamp2)
     * @param String kelasId
     * @param String timestamp1 format 'dd-mm-yyyy', e.g. '01-09-2017'
     * @param String timestamp2 format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
    public function statistikPesertaPerPeriode($id, $timestamp1, $timestamp2) {
        $sql0 = <<<EOF
        select 
            kl.id, kl.nama_kelas, en1.field_01 lv_pembinaan
        from kelas kl
            inner join m_pilihan en1 on kl.lv_pembinaan = en1.id
        where kl.id = :id
EOF;

        $sql1 = <<<EOF
        select 
            kl.nama_kelas, jm.nama_lengkap, pd.keterangan, count(pd.keterangan) total
        from
            kelas_presensi kp
            inner join kelas_jadwal jw on kp.kelas_jadwal_id = jw.id
            inner join kelas kl on jw.kelas_id = kl.id
            inner join kelas_presensi_detail pd on pd.kelas_presensi_id = kp.id
            inner join jamaah jm on pd.jamaah_id = jm.id
        where
            (kp.tanggal_presensi >= STR_TO_DATE(:date1, '%d-%m-%Y') and kp.tanggal_presensi < ADDDATE(STR_TO_DATE(:date2, '%d-%m-%Y'), 1))
            and jw.kelas_id = :id
        group by
            jm.id, pd.keterangan
        order by kl.nama_kelas, jm.nama_lengkap, pd.keterangan
EOF;
        
        $date1 = DateTime::createFromFormat('d-m-Y', $timestamp1);
        $date2 = DateTime::createFromFormat('d-m-Y', $timestamp2);
        $bindparams = ['id' => $id, 'date1' => $date1->format('d-m-Y'), 'date2' => $date2->format('d-m-Y')];
        Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql1, $bindparams);

        // convert object into array
        $resultArr = array_map(function ($value) {
            return (array)$value;
        }, $result);

        // main result
        $statistikResult = array();
        $currPeserta = NULL;
        $currPesertaStt = array('H'=>0, 'A'=>0, 'I'=>0);
        $resultCount = count($resultArr);
        for ($i = 0; $i < $resultCount; $i++) {
            $value = $resultArr[$i];
            
            // init first array
            if ($currPeserta == NULL) {
                $currPeserta = array('nama_lengkap' => $value['nama_lengkap'], 'statistik' => NULL);
            }

            if ($currPeserta['nama_lengkap'] == $value['nama_lengkap']) {
                $currPesertaStt[$value['keterangan']] = $value['total'];
            } else {
                // add into final result
                $currPeserta['statistik'] = $currPesertaStt;
                $statistikResult[] = $currPeserta;

                // add new peserta
                $currPeserta = array('nama_lengkap' => $value['nama_lengkap'], 'statistik' => NULL);
                $currPesertaStt = array('H'=>0, 'A'=>0, 'I'=>0);
                $currPesertaStt[$value['keterangan']] = $value['total'];
            }

            if ($i == ($resultCount - 1)) {
                $currPeserta['statistik'] = $currPesertaStt;
                $statistikResult[] = $currPeserta;
            }
        }

        $bindparams0 = ['id' => $id];
        $result0 = DB::select($sql0, $bindparams0);
        $result0[0]->statistik_list = $statistikResult;
        
        //return response()->json($result0[0]);
		return response()->json($statistikResult);
    }
    
    /**
     * Mengambil list statistik siswa per periode (timestamp1 sampai timestamp2)
     * @param String kelasId
     * @param String timestamp1 format 'dd-mm-yyyy', e.g. '01-09-2017'
     * @param String timestamp2 format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
    public function statistikPesertaPerPeriode2($id, $timestamp1, $timestamp2) {
        $sql0 = <<<EOF
        select 
            kl.id, kl.nama_kelas, en1.field_01 lv_pembinaan
        from kelas kl
            inner join m_pilihan en1 on kl.lv_pembinaan = en1.id
        where kl.id = :id
EOF;

        $sql1 = <<<EOF
        select 
            kl.nama_kelas, jm.nama_lengkap, pd.keterangan, count(pd.keterangan) total
        from
            kelas_presensi kp
            inner join kelas_jadwal jw on kp.kelas_jadwal_id = jw.id
            inner join kelas kl on jw.kelas_id = kl.id
            inner join kelas_presensi_detail pd on pd.kelas_presensi_id = kp.id
            inner join jamaah jm on pd.jamaah_id = jm.id
        where
            (kp.tanggal_presensi >= STR_TO_DATE(:date1, '%d-%m-%Y') and kp.tanggal_presensi < ADDDATE(STR_TO_DATE(:date2, '%d-%m-%Y'), 1))
            and jw.kelas_id = :id
        group by
            jm.id, pd.keterangan
        order by kl.nama_kelas, jm.nama_lengkap, pd.keterangan
EOF;
        
        $date1 = DateTime::createFromFormat('d-m-Y', $timestamp1);
        $date2 = DateTime::createFromFormat('d-m-Y', $timestamp2);
        $bindparams = ['id' => $id, 'date1' => $date1->format('d-m-Y'), 'date2' => $date2->format('d-m-Y')];
        Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql1, $bindparams);

        // convert object into array
        $resultArr = array_map(function ($value) {
            return (array)$value;
        }, $result);

        // main result
        $statistikResult = array();
        $currPeserta = NULL;
        $currPesertaStt = array('H'=>0, 'A'=>0, 'I'=>0);
        $resultCount = count($resultArr);
        for ($i = 0; $i < $resultCount; $i++) {
            $value = $resultArr[$i];
            
            // init first array
            if ($currPeserta == NULL) {
                $currPeserta = array('label' => $value['nama_lengkap'], 'statistik' => NULL);
            }

            if ($currPeserta['label'] == $value['nama_lengkap']) {
                $currPesertaStt[$value['keterangan']] = $value['total'];
            } else {
                // add into final result
                $currPeserta['statistik'] = $currPesertaStt;
                $statistikResult[] = $currPeserta;

                // add new peserta
                $currPeserta = array('label' => $value['nama_lengkap'], 'statistik' => NULL);
                $currPesertaStt = array('H'=>0, 'A'=>0, 'I'=>0);
                $currPesertaStt[$value['keterangan']] = $value['total'];
            }

            if ($i == ($resultCount - 1)) {
                $currPeserta['statistik'] = $currPesertaStt;
                $statistikResult[] = $currPeserta;
            }
        }

        $bindparams0 = ['id' => $id];
        $result0 = DB::select($sql0, $bindparams0);
        $result0[0]->statistik_list = $statistikResult;
        
        return response()->json($result0[0]);
    }


    /**
     * Download statistik siswa per periode (timestamp1 sampai timestamp2)
     * @param int id kelasId
     * @param String timestamp1 format 'dd-mm-yyyy', e.g. '01-09-2017'
     * @param String timestamp2 format 'dd-mm-yyyy', e.g. '01-09-2017'
     */
    public function downloadPresensi($id, $timestamp1, $timestamp2) {
        $sql = <<<EOF
        select
        kl.nama_kelas kelas, jm.nama_lengkap, mt.inisial kelompok, DATE(kp.tanggal_presensi) tanggal_kbm, mm.week_label_sb minggu_ke, pd.keterangan kehadiran, (CASE WHEN pd.keterangan = 'I' THEN pd.alasan ELSE '' END) keterangan
        from
            kelas_presensi kp
            inner join kelas_jadwal jw on kp.kelas_jadwal_id = jw.id
        inner join kelas kl on jw.kelas_id = kl.id
        inner join kelas_presensi_detail pd on pd.kelas_presensi_id = kp.id
        left outer join m_minggu_sb mm on mm.weekofyear = WEEKOFYEAR(kp.tanggal_presensi) and mm.year = YEAR(kp.tanggal_presensi)
        inner join jamaah jm on pd.jamaah_id = jm.id
        left outer join sambung_his sh on jm.id = sh.jamaah_id and sh.status_aktif = 'A'
        left outer join majelis_taklim mt on sh.mt_id = mt.id
        where
        (kp.tanggal_presensi >= STR_TO_DATE(:date1, '%d-%m-%Y') and kp.tanggal_presensi < ADDDATE(STR_TO_DATE(:date2, '%d-%m-%Y'), 1))
        and jw.kelas_id = :id
        order by kp.tanggal_presensi, kl.nama_kelas, jm.nama_lengkap, pd.keterangan
EOF;
        
        $date1 = DateTime::createFromFormat('d-m-Y', $timestamp1);
        $date2 = DateTime::createFromFormat('d-m-Y', $timestamp2);
        $bindparams = ['id' => $id, 'date1' => $date1->format('d-m-Y'), 'date2' => $date2->format('d-m-Y')];
        Log::info('params: ' . json_encode($bindparams));

        $result = DB::select($sql, $bindparams);

        // convert object into array
        $resultArr = array_map(function ($value) {
            return (array)$value;
        }, $result);

        Excel::create('Presensi_' . $id . '_' . $date1->format('d-m-Y') . '-' . $date2->format('d-m-Y'), function($excel) use($resultArr) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Presensi');
            $excel->setCreator('HermanW')->setCompany('Majlis Taklim Manba\'ul Ulum');
            $excel->setDescription('Presensi KBM per kelas');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($resultArr) {
                $sheet->fromArray($resultArr);
            });
        })->download('xlsx');
    }
}
