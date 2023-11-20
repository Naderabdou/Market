<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ExtrasRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       $rules = [
           'name_extra' => 'required|array|',
           'name_extra.*' => 'string|min:3|max:10|',

           'price_extra' => 'required|array',
           'price_extra.*' => 'numeric',


           'image' => 'required|array|min:1|',
           'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|min:1|max:2048',
           'product_id' => 'required|exists:products,id',
           'product_id.*' => 'exists:products,id',
           'status_extra' => 'required|array',
           'status_extra.*' => 'in:available,unavailable',
       ];
         if (in_array($this->method(), ['PUT', 'PATCH'])){

               $rules['name_extra'] = 'nullable|string|min:3|max:10|';


                $rules['price_extra'] = 'nullable|numeric';

                $rules['status_extra'] = 'nullable|in:available,unavailable';
                $rules['product_id'] = 'nullable|exists:products,id';

                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';

         }

            return $rules;
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response= response()->apiError($validator->errors()->first(), 1, 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
