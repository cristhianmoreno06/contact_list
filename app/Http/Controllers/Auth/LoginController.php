<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Request\Login\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function attemptLogin(LoginRequest $request)
    {
        try {
            $user = User::whereUsername($request->get('username'))->first();

            if (is_null($user)) {
                return response()->json([
                    "error" => 'El usuario no es correcto'
                ], Response::HTTP_BAD_REQUEST);
            } else {
                $user->toArray();
            }

            if (Hash::check($request->get('password'), $user['password'])) {
                Passport::personalAccessTokensExpireIn(Carbon::now()->addHours(2));
                Auth::attempt([
                    "username" => $request->get('username'),
                    "password" => $request->get('password')
                ]);

                $token = Auth::user()->createToken($request->get('username'))->accessToken;

                return response()->json([
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "username" => $user['username'],
                    "token" => $token,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    "error" => 'Contraseña incorrecta'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (Throwable $throwable) {
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
