<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function langValidation(): array
    {
        $rules=[];
        foreach (config('translatable.locales') as $locale){

            $rules+=[
                $locale.'.name'=>'required|string|max:255',
                $locale.'.description'=>'required|string|max:255',
            ];
        }
        return $rules;

    }
    public function langValidationUpdate(): array
    {
        $rules=[];
        foreach (config('translatable.locales') as $locale){
            $rules+=[
                $locale.'.*'=>'nullable',
            ];
            $rules+=[

                $locale.'.name'=>'nullable|string|max:255',
                $locale.'.description'=>'nullable|string|max:255',
            ];
        }

        return $rules;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $Rules = $this->langValidation();
       $Rules += [

            'price' => 'required|numeric',
           'discount' => 'nullable|numeric',

            'image' => 'required|array|min:3|max:3',
           'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           'status' => 'required|in:available,unavailable',
            'market_id' => 'required|exists:users,id',


        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])){
            $Rules = $this->langValidationUpdate();
            $Rules['price'] = 'nullable|numeric';
            $Rules['discount'] = 'nullable|numeric';

            $Rules['status'] = 'nullable|in:available,unavailable';
            $Rules['market_id'] = 'nullable|exists:users,id';
            $Rules['images'] = 'nullable|array|min:3|max:3';
            $Rules['images.*'] ='image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }


        return $Rules ;
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response= response()->apiError($validator->errors()->first(), 1, 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
