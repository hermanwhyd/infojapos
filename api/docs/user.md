FORMAT: 1A

# Parameter API
Dihalaman ini akan dijelaskan API dari path /v1/user

# Get User Info (/user/[id:[0-9]+])
Semua resource terkait registrasi user

+ Parameters
  + id : numeric

### Info User [GET]
Untuk mendapatkan info user dengan beberapa parameter inputan

+ Request (plain/text)
    id=?

+ Response 200 (applicatioin/json)
    {
        "id": 1,
        "username": "hermanw",
        "email": "hermanwhyd@gmail.com",
        "nama": "Herman Wahyudi",
        "remember_token": null,
        "updated_at": "2018-01-03 14:52:19",
        "deleted_at": null
    }

+ Response 401 (application/json)
    {
        "ResponseStatus": false,
        "Message": "Login please!"
    }