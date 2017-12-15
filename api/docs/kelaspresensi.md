FORMAT: 1A

# Parameter API
Dihalaman ini akan dijelaskan API dari path /v1/class-presences

# Group Schedules
Grup dari seluruh resource terkait 'Presensi KBM'

## Jadwal Kelas by Date [class-presences/{scdID:[0-9]+}]
Untuk membuat presensi baru dengan mengirimkan data parameter - 'scdID' sebagai kelas_jadwal.id dan parameter 'timestamp' di body post dengan format object JSON

+ Parameters
  + scdID (number) - kelas_jadwal.id

### Create Presensi [POST]
Untuk membuat data presensi dengan jadwal dan kelas sesuai di parameters. Seluruh siswa diset keterangan 'A' (Alpha), ini bisa di atur di nilai default DB di table 'kelas_presensi_detail.keterangan'.

+ Request (application/json)
    {"timestamp": "16-12-2017"}

+ Response 200 (applicatioin/json)
    {
      "ResponseStatus": "success",
      "HasStudents": true
    }

+ Response 409 (application/json)
    {
      "ResponseStatus": "BusinessError",
      "Message": "Presensi sudah di ada. Silakan reload aplikasi atau pilih tanggal lain!"
    }

## Update Presensi [PUT]
Untuk update data presensi yang sudah dibuat dari api diatas. Keterangan berisi 'H','A','S','I' yang mengandung arti Hadir, Alpha, Sakit, Izin.

+ Request (application/json)
    {"jamaah_id": "83001", "keterangan": "S"}

+ Response 200 (applicatioin/json)
    {
        "ResponseStatus": "success",
        "RowUpdated": 1
    }