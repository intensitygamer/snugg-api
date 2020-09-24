<?php

namespace App\Http\Controllers;

use App\BrokerDetail;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\UserResource;
use App\Image;
use Mail;
use Auth;
use Validator;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use App\User;

use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

// very nice there is comment in every method
class AuthController extends Controller // implements MustVerifyEmail
{
    use VerifiesEmails;
    public $successStatus = 200;

    /**
     * Sign Up User
     *
     * @param  [string] first_name
     * @param  [string] middle_name
     * @param  [string] last_name
     * @param  [integer] user_type_id (broker, sales, guest)
     * @param  [string] email
     * @param  [integer] age
     * @param  [integer] contact_number
     * @param  [integer] device_id
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return  [message] message response
     */
    public function signup(Request $request){
        
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'user_type' => 'required|numeric|exists:user_types,id',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',    
            'contact_number' => 'required|numeric',
            'address' => 'required|string',
            'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'], 
            'lon' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'subscription_type' => 'required_if:user_type,1|numeric|exists:subscription_types,id',
            'image_url' => 'string',
            'device_id' => 'string|unique:users',
            
        ]);
        
        $user = User::create([
            'type_id'           => $request['user_type'],
            'email'             => $request['email'],
            'password'          => bcrypt($request['password']),
            'firstname'         => $request['first_name'],
            'middlename'        => $request['middle_name'],
            'lastname'          => $request['last_name'],
            'contact_number'    => $request['contact_number'],
            'address'           => $request['address'],
            'lat'               => $request['lat'],
            'lon'               => $request['lon'],
            'device_id'         => isset($request['device_id']) ? $request['device_id'] : null,  
        ]); 

        

        // broker only data
        if($request['user_type'] == 1){
            $brokerDetails = BrokerDetail::create([
                'subscription_id' => $request['subscription_type'],
                'user_id' => $user->id,
                'expiration_date' =>'2021-01-01',
            ]);

            if(isset($request['image_url'])){
                $image = Image::create(['path' => $request['image_url']]);
                $brokerDetails->images()->attach($image->id);
                $brokerDetails->id_status = 'pending';
            }

            if($request['subscription_type'] == 1){
                $brokerDetails->expiration_date = date('Y-m-d', strtotime("+14 day"));
                $brokerDetails->id_status = 'approved';
            }

            $brokerDetails->save();
        }

        $user->sendApiEmailVerificationNotification();

        return response()->json([
            'message' => 'Please confirm yourself by clicking on verify user button sent to you on your email!',
            'data' => new UserResource($user),
         ], 201);

    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
      */
    public function login(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)) 
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        
        $user = Auth::user();
        if($user->email_verified_at === NULL){
            return response()->json(['message'=> 'Please Verify Email'], 401);
        }

        if($user->user_type_id == 1) {
            $brokerdetails = $user->brokerDetails;
            if($brokerdetails->expiration_date <= Carbon::now() && $brokerdetails->id_status != 'approved'){
                return response()->json([
                    'message' => 'Broker account is expired or not yet approved'
                ], 401);
            }
        }

        $tokenResult = $user->createToken('Personal Access Token');
      
        $token = $tokenResult->token;
       
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
       
        $token->save();
                
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
            'data' => new UserResource($user)
        ]);

    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request){

        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
