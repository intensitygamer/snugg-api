<?php

namespace App\Http\Requests\Api\v01;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\ApiResponse;

use App\Models\Transaction;

class ReviewCreateRequest extends FormRequest
{

    public $validationFailedTitle = 'Creating review failed.';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        $transaction = Transaction::with('transactionCustomers')->find($this->route('transaction'));

        if(!$transaction) {
            return false;
        }
        
        $toAllow = false;
        // check if the user is part of the customers on this transaction
        foreach($transaction->transactionCustomers as $customer) {
            if($this->user()->id == $customer->id) {
                $toAllow = true;
            }
        }

        return $toAllow;
    }


    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json(
                ApiResponse::build()
                    ->message($this->validationFailedTitle)
                    ->errors(['user is not allowed to make this review'])
            ,
            404)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'star' => 'required|integer|between:1,5',
            'message' => 'required|max:3000',
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

        throw new HttpResponseException(
            response()->json(
                ApiResponse::build()
                    ->message($this->validationFailedTitle)
                    ->errors($validator->errors()->all())
            ,
            400)
        );
    }
}
