<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Listings;

class ListingsController extends Controller
{
    // Retrieves all property listings and returns json data

    public function listings(Request $request){
	
		return response()->json(Listings::where('listing_status', '!=', 'deleted')->get(), 200);
   
    }

    public function own(Request $request){
	
		return response()->json(Listings::where('user_id',  Auth::id())->where('listing_status', '!=', 'deleted')->get(), 200);
   
    }

    // * Listing Created by users (broker, salesman and etc)

    public function user(Request $request){
	
		return response()->json(Listings::where('user_id',   $request->id)->where('listing_status', '!=', 'deleted')->get(), 200);
   
    }

    // Inserts New Property Listings

    public function create(Request $request){

    	$listing = new Listings([
            'user_id' 	=> Auth::id(),
            'listing_name' 	=> $request->listing_name,
            'listing_type_id' 	=> $request->listing_type_id,
            'address' 	=> $request->address,
            'latitude' 	=> $request->latitude,
            'longitude'	=> $request->longitude,
            'bedroom' 	=> $request->bedroom,
            'bathroom' 	=> $request->bathroom,
            'floor_area' => $request->floor_area,
            'lot_area' 	=> $request->lot_area,
            'floor' 	=> $request->floor,
            'offer_type_id' => $request->offer_type_id
         ]);
        
        $listing->save();
        
        return response()->json([
            'message' => 'Successfully created listings!'], 201);


     }

    // Retrieves Property Listing Info

    public function info(Request $request){
	
		$listing = Listings::where('listing_id',  $request->id)->where('listing_status', '!=', 'deleted')->get();
 		
		if($listing !== null){
		
              return response()->json($listing, 200);

    	}else {

	        $response = ['message' => 'Listing doesnt exist!'];
        
            return response()->json($response, 401);

    	}


    }

    // Update Property Listing Info

    public function update(Request $request, $id){

		$listing	= Listings::where('listing_id', $request->id)->update([
 	
 		'listing_name'  	=> $request->listing_name,
 		'listing_type_id'   => $request->listing_type_id,
 		'address'	    	=> $request->address,
 		'latitude' 			=> $request->latitude,
 		'longitude' 		=> $request->longitude,
 		'bedroom'			=> $request->bedroom,
 		'bathroom' 			=>  $request->bathroom,
 		'floor_area' 		=> $request->floor_area,
 		'lot_area' 			=> $request->lot_area,
 		'floor' 			=> $request->floor,
 		'offer_type_id' 	=> $request->offer_type_id
		
		]);

        return response()->json([
            'message' => 'Successfully Updated listing!'], 200);
	}

    public function approve(Request $request){
        
        $listing    = Listings::where('listing_id', $request->id)->update(
            ['listing_status' => 'approved', 
            'date_approved' => Carbon::now(), 
            'approved_by_id' => Auth::id()]);

        return response()->json([
            'message' => 'Successfully Approved listing!',
            'listing' => $request->id,
 
        ], 200);    

    }

    public function delete(Request $request){
        
        $listing    = Listings::where('listing_id', $request->id)->update(
            ['listing_status' => 'deleted']);

        return response()->json([
            'message' => 'Successfully Deleted listing!',
 
        ], 200);    

    }


}
