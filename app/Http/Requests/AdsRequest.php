<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdsRequest extends FormRequest
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
        $data= [
            'title' => 'required|string|max:255',
            'link' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'image' => 'required|image|max:2048',
            'status' => 'required|in:active,inactive',
           // 'user_id' => 'required|exists:users,id',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $data['title'] = ['required', 'string', 'max:20','min:3'];
            $data['link'] = ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'];
            $data['image'] = ['required', 'image', 'max:2048'];
            $data['status'] = ['required', 'in:active,inactive'];
//$data['user_id'] = ['nullable', 'exists:users,id'];
        }
        return $data;
    }
}
