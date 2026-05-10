@extends('admin.layouts.admin')
@section('content')
    <div class="main-side">
        <x-message-admin/>
        <div class="main-title">
            <div class="small">
                @lang("Home")
            </div>
            <div class="large">
                التذاكر
            </div>
        </div>
        <div class="btn-holder d-flex align-items-center justify-content-start gap-1 mb-2">
            <button type="button" class="main-btn" data-bs-toggle="modal" data-bs-target="#create">
                اضافة
                <i class="fa-solid fa-plus"></i>
            </button>
            @include('admin.ticket.create-modal')
            <a href="{{route('admin.tickets.index')}}" type="button" class="main-btn btn-main-color">الكل: {{ App\Models\Ticket::count() }}</a>
            <a href="{{route('admin.tickets.index',['status' => 'open'])}}" type="button" class="main-btn btn-blue">مفتوحة: {{ App\Models\Ticket::where('status','open')->count() }}</a>
            <a href="{{route('admin.tickets.index',['status' => 'pending'])}}" type="button" class="main-btn btn-green">قيد الرد: {{ App\Models\Ticket::where('status','pending')->count() }}</a>
            <a href="{{route('admin.tickets.index',['status' => 'closed'])}}" type="button" class="main-btn btn-orange">مغلقة: {{ App\Models\Ticket::where('status','closed')->count() }}</a>
            {{-- <a type="button" class="main-btn btn-purple">تم الرد: {{ App\Models\Ticket::has('comments')->count() }}</a> --}}
            <a href="{{ route('admin.tickets.index', ['replied' => 'true']) }}" type="button" class="main-btn btn-purple">
                تم الرد: {{ App\Models\Ticket::has('details')->count() }}
            </a>
            {{-- <a href="{{ route('admin.tickets.index', ['replied' => 'true']) }}" type="button" class="main-btn btn-purple">تم الرد: {{ App\Models\Comment::distinct('ticket_id')->count('ticket_id') }}</a> --}}

        </div>
        <div class="table-responsive">
            <table class="main-table mb-2">
                <thead>
                <tr>
                    <th>#</th>
                    <th>العميل</th>
                    <th>العنوان</th>
                    <th>المحتوى</th>
                    <th>الحالة</th>
                    <th>التعليقات</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        {{-- <td>{{ $ticket->user?->name }} - {{ __($ticket->user?->type) }} </td> --}}
                        <td>{{ $ticket->customer?->name }} </td>

                        <td>{{ $ticket->subject }}</td>

                        <td>
                            {{ Str::limit($ticket->description, 50, $end = '....') }}
                        </td>

                        <td>
                            @if ($ticket->status == 'open')
                                <span class="badge bg-warning">مفتوحة</span>
                            @elseif($ticket->status == 'pending')
                                <span class="badge bg-success">قيد الرد</span>
                            @else
                                <span class="badge bg-danger">مغلقة</span>
                            @endif
                        </td>
                        <td>

                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.tickets.show', $ticket->id) }}"> التعليقات
                                ({{ $ticket->details->count() }})
                            </a>


                        </td>

                        <td class="d-flex gap-2">
                            <button type="button" class="btn btn-info btn-sm text-white mx-1" data-bs-toggle="modal" data-bs-target="#edit{{ $ticket->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete{{ $ticket->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            <button type="button" class="btn btn-primary btn-sm text-white mx-1" data-bs-toggle="modal" data-bs-target="#add_comment{{$ticket->id}}">
                                أضف تعليق
                            </button>


                            @include('admin.ticket.edit-modal', ['ticket' => $ticket])
                            @include('admin.ticket.delete-modal', ['ticket' => $ticket])
                            @include('admin.ticket.add_comment', ['ticket' => $ticket])
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
              <!-- Pagination with aria-label -->
        <nav aria-label="Pagination Navigation">
            {{ $tickets->links('vendor.pagination.bootstrap-4') }}
        </nav>
        </div>
    </div>
@endsection
