FORMAT: 1A

# Parameter API
Dihalaman ini akan dijelaskan API dari path /v1/class

# Group Schedules
Grup dari seluruh resource terkait 'Kelas'

## Kelas Aktif [class/{timestamp1}/{timestamp2}]
Untuk mendapatkan List data kelas aktif diperiode 'timestamp1' sampai 'timestamp2'

+ Parameters
  + timestamp1 (string) - Tanggal dari jadwal kelas awal. Dikirim dengan format 'dd-mm-yyyy', e.g. 01-12-2019.
  + timestamp2 (string) - Tanggal dari jadwal kelas akhir. Dikirim dengan format 'dd-mm-yyyy', e.g. 01-12-2019.

### Get Data [GET]

+ Request (application/json)
    /class/16-12-2017/16-01-2018

+ Response 200 (applicatioin/json)
    [
        {
            "id": 31100,
            "nama_kelas": "AXEL 2",
            "lv_pembinaan": "Remaja",
            "lv_pembina": "DESA",
            "nama_pembina": "Japos",
            "ttl_kbm": 10
        }
    ]

