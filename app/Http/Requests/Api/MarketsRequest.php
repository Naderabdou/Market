<?php

namespace App\Http\Requests\Api;

use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;

class MarketsRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:13|unique:users',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            // registration_date < expiry_date

            'expiry_date' => 'required|date|after:registration_date',
            'registration_date' => 'required|date|before:expiry_date',

            'account_number' => 'required|integer|min:11|unique:users',
            'account_name' => 'required|string|unique:users',
            'name_branch' => 'required|string|max:255',
            'api_domain' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],

            'subscription_value' => 'required|string|max:255',
            'commercial_registration_number' => 'required|integer|unique:users',
            'img_profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'img_background' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ];
        if(in_array($this->method(), ['PUT', 'PATCH'])){

            //check if market is exist
         //   $market = $this->route()->parameter('market');



            $rules['name'] = ['required', 'string', 'max:20','min:3'];
            $rules['password'] = ['nullable', 'string', 'min:8'];               $rules['phone'] = ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/','min:11','max:13','unique:users,phone,' .  $this->market];

            $rules['address'] = ['required', 'string', 'max:255'];
            $rules['registration_date'] ='required|date|before:expiry_date';
            $rules['expiry_date'] = 'required|date|after:registration_date';
            $rules['account_number'] = ['required', 'integer', 'min:11','unique:users,account_number,' . $this->market];
            $rules['account_name'] = ['required', 'string', 'max:255', 'unique:users,account_name,' . $this->market];
            $rules['api_domain'] = ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'];
            $rules['name_branch'] = ['required', 'string', 'max:255'];

            $rules['subscription_value'] = ['required', 'string', 'max:255'];
            $rules['commercial_registration_number'] = ['required', 'integer', 'unique:users,commercial_registration_number,' . $this->market];
            $rules['img_profile'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048',];
            $rules['img_background'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        }
        return $rules;

    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response= response()->apiError($validator->errors()->first(), 1, 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
