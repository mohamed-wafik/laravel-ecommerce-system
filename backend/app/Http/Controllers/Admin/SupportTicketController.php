<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminReplyToTicketRequest;
use App\Http\Requests\Admin\UpdateTicketStatusRequest;
use App\Services\Admin\SupportTicketService;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(protected SupportTicketService $tickets) {}

    public function index(Request $request)
    {
        return $this->paginatedSuccess(
            $this->tickets->paginate($request->integer("per_page",15), $request->only(['status', 'search']))
        );
    }

    public function show(string $id)
    {
        return $this->success($this->tickets->find($id));
    }

    public function reply(AdminReplyToTicketRequest $request, string $id)
    {
        return $this->success(
            $this->tickets->reply($request->user(), $id, $request->validated('message'), $request->validated('status')),
            'Reply sent'
        );
    }

    public function updateStatus(UpdateTicketStatusRequest $request, string $id)
    {
        return $this->success($this->tickets->updateStatus($id, $request->validated('status')), 'Status updated');
    }

    public function stats()
    {
        return $this->success($this->tickets->stats());
    }
}