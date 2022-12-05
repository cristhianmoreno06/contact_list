<?php

namespace App\Http\Request\Login;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            "username" => "required",
            "password" => ["required", "string"]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            "username.required" => "El campo es requerido",
            "password.required" => "El campo es requerido"
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
            ['errors' => $errors],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
