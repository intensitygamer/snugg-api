<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class UserStoreRequest extends FormRequest
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
            //
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'required|string|max:50',
            'user_type_id'      => 'required|numeric',
            'email' => 'required|string|email|unique:user',
            'password' => 'required|string|confirmed',    
            'contact_number' => 'required|numeric',           
            'age' => 'required|numeric',           
            'id_status'         => 'required|string',
        ];
    }
    
    /**
     *  Filters to be applied to the input.
     *
     *  @return array
     */
    public function filters()
    {
        return [];
    }

}
