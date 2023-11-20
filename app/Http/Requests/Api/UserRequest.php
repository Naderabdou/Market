<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;
use function Laravel\Prompts\password;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules= [

            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:13|unique:users',

           'address' => 'nullable|string|max:255',

        ];
        if (request()->is('api/login')){

           $user=  User::where('phone', request()->phone)->select('type')->first();
           if ($user){
           if ($user->type == 'user'){
               $rules= [
                   'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:13',
                   'password' => 'string|min:8|',
               ];
           }else{
               $rules= [
                   'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:13',
                   'password' => 'required|string|min:8|',
               ];
           }//end if
               }


        }

        return $rules;
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response= response()->apiError($validator->errors()->first(), 1, 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
