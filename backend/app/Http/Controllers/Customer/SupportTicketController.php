<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateSupportTicketRequest;
use App\Http\Requests\ReplyToTicketRequest;
use App\Services\Customer\SupportTicketService;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(protected SupportTicketService $tickets) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess($this->tickets->list($request->user()));
    }

    public function show(Request $request, string $id)
    {
        return $this->success($this->tickets->find($request->user(), $id));
    }

    public function store(CreateSupportTicketRequest $request)
    {
        return $this->created($this->tickets->create($request->user(), $request->validated()), 'Ticket created');
    }

    public function reply(ReplyToTicketRequest $request, string $id)
    {
        return $this->success(
            $this->tickets->reply($request->user(), $id, $request->validated('message')),
            'Reply sent'
        );
    }
}