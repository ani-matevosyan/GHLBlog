<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    /**
     * Register a user for api
     */
    public function register()
    {
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        return response()->json(['status' => 201]);
    }

    /**
     * Login a user.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        $data = [
            'grant_type' => 'password',
            'client_id' => '2',
            'client_secret' => 'HhVtNRj8ADibV8jbiZHMEU587oDX2TuPQr7SlDQr',
            'username' => request('username'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);
        return app()->handle($request);
    }

    /**
     * Logout a user.
     * @return string
     */
    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }
}
