<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketDetail;
use App\Models\User;
use App\Traits\JodaResource;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use JodaResource;

    public $rules = [
        'subject' => 'required',
        'description' => 'required',
        'user_id' => 'required',
        'status' => 'sometimes|nullable|in:open,pending,closed',
    ];

    // public function query($query)
    // {
    //     if(\request()->status) {
    //         return $query->where('status',\request()->status)->latest()->paginate(10);
    //     } else {
    //         return $query->latest()->paginate(10);
    //     }
    // }
    public function query($query)
    {
        if (\request()->status) {
            return $query->where('status', \request()->status)->latest()->paginate(10);
        } elseif (\request()->has('replied')) {
            // يتم التعامل مع حالة تم الرد هنا
            return $query->whereHas('details')->latest()->paginate(10);
        } else {
            return $query->latest()->paginate(10);
        }
    }



    public function storeComment(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'ticket_id' => 'required',
            'user_id' => 'required',
        ]);
        $ticket = new TicketDetail();
        $ticket->ticket_id = $request->ticket_id;
        $ticket->message = $request->message;
        $ticket->user_id = $request->user_id;
        $ticket->save();


        return redirect()->back()->with('success', trans('تم الاضافه بنجاح'));
    }
}
