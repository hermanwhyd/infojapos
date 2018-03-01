FORMAT: 1A

# Parameter API
Dihalaman ini akan dijelaskan API dari path /v1/class

# Group Schedules
Grup dari seluruh resource terkait 'Kelas'

## Kelas Aktif [class/{timestamp}]
Untuk mendapatkan List data kelas aktif di bulan 'timestamp'

+ Parameters
  + timestamp (string) - Tanggal dari jadwal kelas. Dikirim dengan format 'dd-mm-yyyy', e.g. 01-12-2019.

### Get Data [GET]

+ Request (application/json)
    /class/16-12-2017

+ Response 200 (applicatioin/json)
    [
        {
            "id": 31100,
            "nama_kelas": "AXEL 2",
            "lv_pembinaan": "Remaja",
            "lv_pembina": "DESA",
            "nama_pembina": "Japos"
        }
    ]

