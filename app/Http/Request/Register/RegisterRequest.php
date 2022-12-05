<?php

namespace App\Http\Request\Register;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "name.required" => "El campo es requerido",
            "username.required" => "El campo es requerido",
            "email.required" => "El campo es requerido",
            "password.required" => "El campo es requerido",
        ];
    }


    /**
     * Handle a failed validation attempt and return Json Response for AdminClient
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json(
            [
                'code' => Response::HTTP_BAD_REQUEST,
                'title' => 'Validación de formulario',
                'message' => $errors,
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
