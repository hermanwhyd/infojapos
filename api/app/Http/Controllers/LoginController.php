<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Log;

class LoginController extends Controller {
    /**
     * Index login controller
     *
     * When user success login will retrive callback as api_token
     */
    public function login(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $login = User::where('email', $email)->first();
        if (!$login) {
            $res['response_status'] = "BusinessError";
            $res['message'] = 'Your email or password incorrect!';
            Log::info('Login Response 1 : ' . json_encode($res));
            return response($res, 401);
        } else {
            if ($password === $login->password) {
                $api_token = sha1(time());
                $create_token = User::where('id', $login->id)->update(['api_token' => $api_token]);
                if ($create_token) {
                    $res['response_status'] = "Success";
                    $res['api_token'] = $api_token;
                    $res['user'] = $login;
                    Log::info('Login Response 2 : ' . json_encode($res));
                    return response($res);
                } else {
                    $res['response_status'] = "BusinessError";
                    $res['message'] = 'You email or password incorrect!';
                    Log::info('Login Response 3 : ' . json_encode($res));
                    return response($res, 401);
                }
            }
        }
    }

    public function logout(Request $request) {
        $token = $request->input("api_token");
        User::where('api_token', $token)->update(['api_token' => ""]);
        $res['response_status'] = "Success";
        $res['message'] = 'Logout success';
        return response($res);
    }
}