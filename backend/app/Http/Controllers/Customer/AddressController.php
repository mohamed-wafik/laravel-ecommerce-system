<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressRequest;
use App\Services\Customer\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(protected AddressService $addresses) {}

    public function index(Request $request)
    {
        return $this->success($this->addresses->list($request->user()));
    }

    public function store(AddressRequest $request)
    {
        return $this->created($this->addresses->create($request->user(), $request->validated()), 'Address added');
    }

    public function update(AddressRequest $request, string $id)
    {
        return $this->success($this->addresses->update($request->user(), $id, $request->validated()), 'Address updated');
    }

    public function destroy(Request $request, string $id)
    {
        $this->addresses->delete($request->user(), $id);

        return $this->noContentSuccess('Address deleted');
    }
}
