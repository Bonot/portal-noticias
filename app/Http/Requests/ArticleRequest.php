<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'max:255'
            ],
            'content' => [
                'required',
            ],
            'author_id' => [
                'required',
                'integer',
                'exists:authors,id'
            ]
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'title.required' => 'Informe um título.',
            'title.max' => 'O campo título deve ter no máximo 255 caracteres.',
            'content.required' => 'Informe uma descrição.',
            'author_id.required' => 'Informe um autor.',
            'author_id.exists' => 'O autor selecionado é inválido.',
        ];
    }
}
