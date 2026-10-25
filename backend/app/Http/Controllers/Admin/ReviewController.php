<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateReviewRequest;
use App\Services\Admin\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviews) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess(
            $this->reviews->paginate(
                $request->integer("per_page",20), 
                $request->only(['product_id', 'is_approved', 'rating'])
            )
        );
    }

    public function store(CreateReviewRequest $request, string $productId)
    {
        $data = $request->validated();
        $data['product_id'] = $productId;
        $data['user_id'] = $request->user()->id;

        return $this->created($this->reviews->create($data), 'Review submitted successfully');
    }
    public function show(string $id)
    {
        return $this->success($this->reviews->find($id));
    }

    public function approve(string $id)
    {
        return $this->success($this->reviews->approve($id), 'Review approved');
    }

    public function reject(string $id)
    {
        return $this->success($this->reviews->reject($id), 'Review rejected');
    }

    public function destroy(string $id)
    {
        $this->reviews->delete($id);

        return $this->noContentSuccess('Review deleted');
    }
}