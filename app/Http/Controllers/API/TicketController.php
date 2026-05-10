<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\Controller;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::query();

        if ($request->filled('status')) {
            $tickets->where('status', $request->input('status'));
        }

        if ($request->filled('type')) {
            $tickets->where('type', $request->input('type'));
        }

        if ($request->filled('subject')) {
            $tickets->where('subject', 'like', '%' . $request->input('subject') . '%');
        }

        if ($request->filled('user_id')) {
            $tickets->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $tickets->whereBetween('created_at', [
                $request->input('start_date'),
                $request->input('end_date')
            ]);
        }

        $pageSize = $request->get('size', 10);
        $currentPage = $request->get('page', 1);

        $result = $tickets->with('customer')->with('details.user')->paginate($pageSize, ['*'], 'page', $currentPage);

        return $this->sendResponse($result, 'Tickets retrieved successfully.');
    }

    public function show($id)
    {
        if (!Ticket::whereKey($id)->exists()) {
            return $this->sendError('validation_error', 'There is no ticket with this id', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ticket = Ticket::with('details.user')->find($id);
        return $this->sendResponse($ticket, 'Ticket retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation_error', $validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ticket = new Ticket();
        $ticket->forceFill($request->all())->save();
        return $this->sendResponse(Ticket::with('customer')->with('details.user')->find($ticket->id), 'Tickets created successfully.');
    }

    public function update(Request $request, $id)
    {
        if (!Ticket::whereKey($id)->exists()) {
            return $this->sendError('validation_error', 'There is no ticket with this id', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ticket = Ticket::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:open,pending,closed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('validation_error', $validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ticket->forceFill($request->all())->save();

        return $this->sendResponse(Ticket::with('customer')->with('details.user')->find($ticket->id), 'Tickets updatd successfully.');
    }

    public function destroy($id)
    {
        if (!Ticket::whereKey($id)->exists()) {
            return $this->sendError('validation_error', 'There is no ticket with this id', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ticket = Ticket::findOrFail($id);
        $ticket = $ticket->delete();

        return $this->sendResponse($ticket, 'Tickets deleted successfully.');
    }

    public function detailStore(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        (new TicketDetail())->forceFill([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ])->save();

        $ticket = Ticket::with('details.user')->find($ticket->id);

        return $this->sendResponse($ticket, 'Ticket details added successfully.');
    }
}
