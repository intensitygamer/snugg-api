<?php

namespace App\Http\Controllers\Api\v01;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Events\ReviewedTransaction;

use App\Http\Requests\Api\v01\ReviewCreateRequest;
use App\Http\Requests\Api\v01\ReviewRetrieveRequest;
use App\Http\Requests\Api\v01\ReviewUpdateRequest;

use App\Http\Resources\Api\v01\Review as ReviewResource;

use App\Models\Transaction;
use App\Models\Review;

use App\ApiResponse;

class ReviewController extends Controller
{
    
    public function index(ReviewRetrieveRequest $request) {

        $limit = isset($request['limit']) ? $request['limit'] : 10;
		$excludes = isset($request['excludes']) ? $request['excludes'] : [];

        $reviews = Review::whereNotIn('id', $excludes)
                        ->where('poi_id', $request->route('poi'))
                        ->orderBy('created_at', 'DESC')
                        ->get()
                        ->take($limit);

        return response()->json(
            ApiResponse::build()
                ->message('Review retrieve successfully.')
                ->data(
                    ReviewResource::collection($reviews)
                ), 
            200);
    }

    public function create(ReviewCreateRequest $request) {

        $transaction = Transaction::find($request->route('transaction'));

        $review = Review::where('user_id', $request->user()->id)
                        ->where('transaction_id', $transaction->id)
                        ->first();

        if(!$review) {

            $review = Review::create([
                'poi_id' => $transaction->poi_id,
                'user_id' => $request->user()->id,
                'transaction_id' => $transaction->id,
                'star' => $request['star'],
                'message' => $request['message'],
            ]);

        } else {

            $review->star = $request['star'];
            $review->message = $request['message'];
            $review->save();
        }

        event(new ReviewedTransaction($review));


        return response()->json(
            ApiResponse::build()
                ->message('Review created successfully.')
                ->data(
                    new ReviewResource($review)
                ), 
            200);

    }


    public function update(ReviewUpdateRequest $request){

        $review = Review::find($request->route('review'));
        $review->star = isset($request['star']) ? $request['star'] : $review->star;
        $review->message = isset($request['message']) ? $request['message'] :$review->message;
        $review->save();

        return response()->json(
            ApiResponse::build()
                ->message('Review updated successfully.')
                ->data(
                    new ReviewResource($review)
                ), 
            200);
    }
}
