<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Registration extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fname'     => 'required|alpha',
            'lname'     => 'required|alpha',
            'username'  => 'required|regex:/^([a-zA-Z0-9@_]+)$/',
            'email'     => 'required|email',
            'password'  => 'required|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
            'user_type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fname.required'      => 'Firstname is required',
            'lname.required'      => 'Lastname is required',
            'username.required'   => 'Username is required',
            'email.required'      => 'Email ID is required',
            'password.required'   => 'Password is required',
            'user_type.required'  => 'Choose a user type',
            'fname.alpha'         => 'Invalid Firstname',
            'lname.alpha'         => 'Invalid Lastname',
            'username.regex'      => 'Invalid Username',
            'password.regex'      => 'Invalid Password',
            'email.email'         => 'Invalid Email',
        ];
    }
}
