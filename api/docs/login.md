FORMAT: 1A

# Parameter API
Dihalaman ini akan dijelaskan API dari path /v1/login dan /v1/register

# Group Register
Grup dari seluruh resource terkait Register User

## Register user baru [/register]
Semua resource terkait registrasi user

+ Parameters
  + email (email) - email standard
  + username - alfanumeric
  + password - clear password
  + nama - full name

### Create New User [POST]
Untuk membuat user baru dengan beberapa parameter inputan

+ Request (plain/text)
    email=?&username=?&password=?&nama=?

+ Response 200 (applicatioin/json)
    {
        "ResponseStatus": true,
        "Message": "Success register!"
    }

+ Response 409 (application/json)
    {
        "ResponseStatus": false,
        "Message": "Failed to register"
    }

## Login [/login]
Semua resource terkait mendapatkan informasi user

+ Parameters
  + email (email) - email standard
  + username - alfanumeric
  + password - clear password

login bisa dengan username/email, pilih salahsatu.

### Do Login [POST]
Untuk login user dengan beberapa parameter inputan dan mendapat response 'api_token'

+ Request (plain/text)
    email=?&password=?

+ Response 200 (applicatioin/json)
    {
    "ResponseStatus": true,
    "Message": "Success login",
    "api_token": "7da099b7bfce3b0bfa4830b312efe8c8c11a894e",
    "user": {
        "id": 1,
        "username": "hermanw",
        "email": "hermanwhyd@gmail.com",
        "nama": "Herman Wahyudi",
        "remember_token": null,
        "updated_at": "2018-01-03 14:49:08",
        "deleted_at": null
    }
}

+ Response 401 (application/json)
    {
        "ResponseStatus": false,
        "Message": "Your email or password incorrect!"
    }