FORMAT: 1A

# Parameter API
Dihalaman ini akan dijelaskan API dari path /v1/class-schedules

# Group Schedules
Grup dari seluruh resource terkait 'jadwal KBM'

## Jadwal Kelas by Date [class-schedules/{timestamp}]
Untuk mendapatkan jadwal kelas, termasuk informasi absensi, sesuai dengan tanggal di parameter - 'timestamp'.

+ Parameters
  + timestamp (string) - Tanggal dari jadwal kelas. Dikirim dengan format 'dd-mm-yyyy', e.g. 01-12-2019.

### Retrieve Daftar Jadwal [GET]
+ Request Plain Text Message
+ Response 200 (applicatioin/json)
    [
      {
        "id": 31201,
        "nama_kelas": "AXEL 3",
        "lokasi": "MU lt 2",
        "pembinaan": "Desa",
        "jam_mulai": "20:00",
        "jam_selesai": "21:30",
        "status_presensi": "Partial 1"
      }
    ]

## Siswa Kelas by Jadwal [class-schedules/{scdID:[0-9]+}/students]
Untuk mendapatkan List data siswa dalam satu kelas. Parameter - 'scdID' merupakan id dari kelas_jadwal untuk mengambil data kelas dan level binaan (Pusat/Daerah/Desa/Kelompok).

+ Parameters
  + scdID (number, required) - kelas_jadwal.id

### Retrieve Daftar Siswa [GET]
+ Request Plain Text Message
+ Response 200 (applicatioin/json)
    {
      "id": 31201,
      "nama_kelas": "AXEL 3",
      "lv_pembinaan": "Desa",
      "listSiswa": [
        {
          "id": 83001,
          "nama_panggilan": "Anggi",
          "nama_lengkap": "Pratiwi Anggreini",
          "kelompok": "VJ"
        }
      ]
    }

## Presensi Kelas by Jadwal dan Tanggal [class-schedules/{scdID:[0-9]+}/presences/{timestamp}]
Untuk mendapatkan data presensi siswa dalam kelas dengan jadwal di parameter - 'sdcID' dan tanggal sesuai dengan parameter - 'timestamp'

+ Parameters
  + scdID (number) - kelas_jadwal.id
  + timestamp (string) - Tanggal dari jadwal kelas. Dikirim dengan format 'dd-mm-yyyy', e.g. 01-12-2019.

### Retrieve Daftar Siswa [GET]
+ Request Plain Text Message
+ Response 200 (applicatioin/json)
    {
      "id": 31201,
      "nama_kelas": "AXEL 3",
      "tipe_mt": "Desa",
      "listSiswa": [
        {
          "id": 83001,
          "nama_panggilan": "Anggi",
          "nama_lengkap": "Pratiwi Anggreini",
          "kelompok": "VJ",
          "keterangan": "H"
        }
      ]
    }
