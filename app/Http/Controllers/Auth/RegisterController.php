<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Request\Register\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new user instance after a valid registration.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {

            $getUser = User::whereEmail($request->get('email'))->first();

            if (!is_null($getUser)) {
                return response()->json([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'title' => 'Creación de usuario',
                    'message' => 'Ya existe un usuario con el mismo email en el sistema',
                    "source" => $getUser,
                ], Response::HTTP_OK);
            }

            $data = $request->all();

            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return response()->json([
                'code' => Response::HTTP_OK,
                'title' => 'Creación de usuario',
                'message' => 'El usuario se creo correctamente en el sistema',
                "source" => $user,
            ], Response::HTTP_OK);

        } catch (Throwable $throwable) {
            return response()->json([
                "title" => 'Error interno del sistema',
                "error" => $throwable->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
