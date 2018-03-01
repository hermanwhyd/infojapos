FORMAT: 1A

# Parameter API
Dihalaman ini akan dijelaskan API dari path /v1/pilihan

# Get Enums (/pilihan)
Semua resource terkait pilihan/enumeration

+ Parameters

## Mendapakan list enums per grup [GET]

+ Request (plain/text)

+ Response 200 (application/json)
    [
        {
            "grup": "pekerjaan",
            "options": [
                {
                    "id": 24314,
                    "grup": "pekerjaan",
                    "posisi": 1,
                    "field_01": "PNS",
                    "field_02": null,
                    "field_03": null
                },
                {
                    "id": 24315,
                    "grup": "pekerjaan",
                    "posisi": 2,
                    "field_01": "TNI/Polri",
                    "field_02": null,
                    "field_03": null
                }
            ]
        },
        {
            "grup": "status_rumah",
            "options": [
                {
                    "id": 24324,
                    "grup": "status_rumah",
                    "posisi": 1,
                    "field_01": "Milik sendiri (suami/istri)",
                    "field_02": null,
                    "field_03": null
                }
            ]
        }
    ]

# Get Enums (/pilihan/{grup})
Semua resource terkait pilihan/enumeration

+ Parameters
  + grup (string) - Nama grup dari enums

## Mendapakan list enums per grup [GET]

+ Request (plain/text)
    + /pilihan/izin_alasan

+ Response 200 (application/json)
    [
        {
            "id": 24623,
            "grup": "izin_alasan",
            "value": "Sakit"
        },
        {
            "id": 24624,
            "grup": "izin_alasan",
            "value": "Acara keluarga"
        }
    ]

# Get Enums (/pilihan/{id:[0-9]+})
Semua resource terkait pilihan/enumeration

+ Parameters
  + id (number) - id pilihan

## Mendapakan list enums per grup [GET]

+ Request (plain/text)
    + /pilihan/24457

+ Response 200 (application/json)
    {
        "id": 24457,
        "grup": "dapukan",
        "field_01": "Daerah - Wanhat\r",
        "field_02": null,
        "field_03": null
    }

# Hapus Enums by Grup (/pilihan/{grup})
Menghapus semua enums berdasarkan nama grup

+ Parameters
  + grup (string) - Nama grup dari enums

## Hapus enums berdasarkan grup [DELETE]

+ Request (plain/text)
    + /pilihan/izin_alasan

+ Response 200 (application/json)
    {
        "response_status": "Success",
        "message": "RowDeleted 1"
    }

# Hapus enums by ID (/pilihan/{id:[0-9]+})
Hapus enums berdasarkan ID

+ Parameters
  + id (number) - id pilihan

## Hapus enums berdasarkan ID [GET]

+ Request (plain/text)
    + /pilihan/24457

+ Response 200 (application/json)
    {
        "response_status": "Success",
        "message": "RowDeleted 1"
    }

# Simpan enum baru (/pilihan)
Menyimpan enum baru kedalam db

## Menyimpan enum baru [POST]

+ Request (application/json)
    {
        "grup": "Pekerjaan",
        "field_01":"Karyawan",
        "field_02":"",
        "field_03":""
    }

+ Response 200 (application/json)
    {
        "response_status": "Success",
        "message": "RowInserted 1"
    }

# Update enum baru (/pilihan/{id:[0-9]+})
Update enum di db

+ Parameters
  + id (number) - id pilihan

## Update [PUT]

+ Request (application/json)
    {
        "grup": "Pekerjaan",
        "field_01":"Karyawan",
        "field_02":"",
        "field_03":""
    }

+ Response 200 (application/json)
    {
        "response_status": "Success",
        "message": "RowInserted 1"
    }