<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Log;

class UserController extends Controller
{
    /**
     * Register new user
     *
     * @param $request Request
     */
    public function register(Request $request) {
        $username = $request->input('username');
        $email = $request->input('email');
        $nama = $request->input('nama');
        $password = $request->input('password');
        
        $login = User::where('email', $email)->first();
        if (!$login) {
            $register = User::create([
                'username'=> $username,
                'email'=> $email,
                'nama'=> $nama,
                'password'=> $password,
            ]);
    
            if ($register) {
                $res['response_status'] = "Success";
                $res['message'] = 'Success register!';
                return response($res);
            }else{
                $res['response_status'] = "BusinessError";
                $res['message'] = 'Gagal registrasi!';
                return response($res, 401);
            }
        } else {
            $res['response_status'] = "BusinessError";
            $res['message'] = 'Akun lain sudah registrasi dengan email ' . $email;
            return response($res, 401);
        }
    }
    /**
     * Get user by id
     *
     * URL /user/{id}
     */
    public function get_user(Request $request, $id)
    {
        $user = User::where('id', $id)->get();
        if ($user) {
              $res['response_status'] = true;
              $res['message'] = $user;
        
              return response($res);
        }else{
          $res['response_status'] = false;
          $res['message'] = 'Cannot find user!';
        
          return response($res);
        }
    }
}