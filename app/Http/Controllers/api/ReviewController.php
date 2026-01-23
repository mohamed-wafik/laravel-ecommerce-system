<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends BaseController
{
    public function productReviews($productId)
    {
        $reviews = Review::with('user')
                     ->where('product_id', $productId)
                     ->latest()
                     ->get();

        return $this->sendResponse($reviews, 'Reviews retrieved successfully');
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|between:1,5',
            'comment'    => 'nullable|string'
        ]);

        $data = [
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
        ];

        $updateData = [
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ];

        $review = Review::updateOrCreate($data, $updateData);

        return $this->sendResponse($review, 'Review submitted successfully');
    }
}