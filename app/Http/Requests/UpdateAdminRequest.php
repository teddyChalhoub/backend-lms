<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateAdminRequest extends FormRequest
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
//        dd();

        return [
            'name' => ['min:3'],
            'username'=>['unique:admins,username,'.$this->admin],
            'password'=>['min:6', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'],
            'phoneNumber'=>[''],
        ];

    }

    public function messages()
    {
        return [
            'required'=> ':attribute must be provided',
            'name.min'=> 'Name must be more than 3',
            'password.regex'=>'Password should contain at least one Uppercase, one Lowercase, one Numeric and one special character',
            'unique'=>':attribute already exits'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'username' => 'Username',
            'password' => 'Password',
            'phoneNumber' => 'Phone number',

        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {

        if($this->wantsJson())
        {
            $response = response()->json([
                "status"=>400,
                'success' => false,
                'message' => 'Ops! Some errors occurred',
                'errors' => $validator->errors()
            ]);
        }

        throw (new ValidationException($validator, $response));
    }

}
