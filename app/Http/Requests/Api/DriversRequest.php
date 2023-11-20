<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DriversRequest extends FormRequest
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




       $Rules = [
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:255|unique:users,phone',
                'address' => 'required|string|max:255',
               'date_of_birth' => 'required|date',
               'img_profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'account_number' => 'required|integer|unique:users,account_number',
                'account_name' => 'required|string|max:255|unique:users,account_name',
                'api_domain' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'subscription_value' => 'required|string|max:255',
                'order_value' => 'required|string|max:255',
                'registration_date' => 'required|date|before:expiry_date',
                'expiry_date' => 'required|date|after:registration_date',
                'vehicle_number' => 'required|string|min:17|max:20|unique:users,vehicle_number',
       ];
        if(in_array($this->method(), ['PUT', 'PATCH'])){

            $Rules['name'] = ['required', 'string', 'max:20','min:3'];
            $Rules['password'] = ['nullable', 'string', 'min:8'];
            $Rules['phone'] = ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/','min:11','max:13','unique:users,phone,' .  $this->driver];
            $Rules['address'] = ['required', 'string', 'max:255'];
            $Rules['date_of_birth'] = ['required', 'date'];
            $Rules['img_profile'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
            $Rules['account_number'] = ['required', 'integer', 'min:11','unique:users,account_number,' . $this->driver];
            $Rules['account_name'] = ['required', 'string', 'max:255', 'unique:users,account_name,' . $this->driver];
            $Rules['api_domain'] = ['required', 'string', 'max:255'];
            $Rules['subscription_value'] = ['required', 'string', 'max:255'];
            $Rules['order_value'] = ['required', 'string', 'max:255'];
            $Rules['registration_date'] = ['required', 'date', 'before:expiry_date'];
            $Rules['expiry_date'] = ['required', 'date', 'after:registration_date'];
            $Rules['vehicle_number'] = ['required', 'string', 'max:255','unique:users,vehicle_number,' . $this->driver];

        }
        return $Rules;
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response= response()->apiError($validator->errors()->first(), 1, 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }}
