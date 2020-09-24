<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Mail\EmailVerification;

class UserController extends Controller
{
    //
 
    /**
     * Get All Listings [Admin Access Only]
     *
     * @return  [json user query results] message response
     */

    public function lists(Request $request){
	
		return response()->json(User::where('rec_status', '!=', 'deleted')->get());
   
    }

    /**
     * Create User [Admin Access Only]
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

    public function create(Request $request){
     
        $user = new User([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'middle_name'       => $request->middle_name,
            'user_type_id'      => $request->user_type_id,
            'email'             => $request->email,
            'password'          => bcrypt($request->password),
            'contact_number'    => $request->contact_number,
            'age'               => $request->age,
            'device_id'         => 1,
            'is_email_verified' => 1,
            'id_status'         => 'pending',
            'expiration_date'   => '2021/01/01',

        ]); 

        $user->save();
        
        return response()->json([
            'message' => 'Successfully created user!'], 201);
     
    }
    /**
     * Update User [Admin Access Only]
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
     * @param  [string] expiration_date
     * @param  [string] id_status
     * @return  [message] response message 
     */

    public function update(Request $request){
        
        $user    = User::where('id', $request->id)->update([
    
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'middle_name'       => $request->middle_name,
            'user_type_id'      => $request->user_type_id,
            'email'             => $request->email,
            'password'          => bcrypt($request->password),
            'contact_number'    => $request->contact_number,
            'age'               => $request->age,
            'id_status'         => $request->id_status,
            'expiration_date'   => $request->expiration_date,

        
        ]);

        return response()->json([ 'message' => 'Successfully Updated User!'], 200);
    
    
    }

    /**
     * User Info
     *
     * @param  [integer] id 
     * @return  [json user info result] message response
    
    */

    public function info(Request $request){
    
        $user_info = User::where('id',  $request->id)->where('rec_status', '!=', 'deleted')->get();
        
        if($user_info !== null){
            
             return response()->json($user_info, 201);
  
        } else {
            
            return response()->json([ 'message' => 'User Doesnt Exist!'], 401);

        }

    }

    /**
     * Approve User [Admin Access Only]
     *
     * @param  [integer] id 
     * @return  [string] message response
    
    */

    public function approve(Request $request){
    
        $user    = User::where('id', $request->id)->update(
            ['rec_status' => 'active', 
            'id_status' => 'approved', 
            'date_approved' => Carbon::now(), 
            'approved_by_id' => Auth::id()]

            );

        return response()->json([
            'message' => 'Successfully Approved User!',
            'user' => $request->id,
 
        ], 200);  

    }

    /**
     * Delete User [Admin Access Only]
     *
     * @param  [integer] id 
     * @return  [string] message

    */

    public function delete(Request $request){

        $user    = User::where('id', $request->id)->update(
            ['rec_status' => 'deleted']);

        return response()->json([
            'message' => 'Successfully Deleted User!',
 
        ], 200);       

    }
}

