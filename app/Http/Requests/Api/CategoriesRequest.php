<?php

namespace App\Http\Requests\Api;

use Doctrine\Inflector\Rules\French\Rules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriesRequest extends FormRequest
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
                $locale.'.*'=>'required',
            ];
            $rules+=[
                $locale.'.name'=>[Rule::unique('category_translations')],
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

                $locale.'.name'=>['nullable','unique:category_translations,name,' .  $this->category . ',category_id'],
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
         $rules= $this->langValidation();
        $rules += [
              'img_category'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];


        if(in_array($this->method(), ['PUT', 'PATCH'])){

          $rules =  $this->langValidationUpdate();
            $rules['img_category'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        }
        return $rules;

    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response= response()->apiError($validator->errors()->first(), 1, 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
