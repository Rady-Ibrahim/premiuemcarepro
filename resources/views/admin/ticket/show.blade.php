@extends('admin.layouts.admin')

@push('css')
    <style>
        ul.timeline {
            list-style-type: none;
            position: relative;
        }

        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }

        ul.timeline > li {
            margin: 20px 0;
            padding-left: 20px;
        }

        ul.timeline > li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
    </style>
@endpush

@section('content')
    <div class="main-side">
        <x-message-admin/>

        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="main-title">
                <div class="small">
                   الرئيسية
                </div>
                <div class="large">
                    التذاكر - عرض تذكرة
                </div>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">عودة للتذاكر <i
                    class="fas fa-arrow-left-long"></i></a>
        </div>

        <div class="section_content content_view">

            <ul class="timeline">

                <li>
                    <div class="box-content"
                         style="width: 90%; padding: 10px; border-radius: 4px; background-color: #ebeaea;">
                        <p class="mb-0">
                            {{ucfirst($ticket->customer?->name)  }}
                        </p>
                        <a href="javascript:void(0);">{{$ticket->subject}}</a>
                        <a href="javascript:void(0);" class="float-right">{{$ticket->created_at?->format('Y-m-d')}}</a>
                        <p class="mb-0">
                            {{$ticket->description}}
                        </p>

                    </div>
                </li>

                @if ($ticket->details->count() > 0)
                    @foreach ($ticket->details as $comment)

                        <li>
                            <div class="box-content"
                                 @if($comment->user_id !== $ticket->user_id)
                                     style="width: 90%; padding: 10px; border-radius: 4px; background-color: #2E5789;color: #fff"
                                @endif
                            >
                                <p class="mb-0">
                                    @if($comment->user_id !== $ticket->user_id)
                                        الادارة
                                    @else
                                        {{$comment->user->name}}
                                    @endif
                                </p>
                                <a href="javascript:void(0);" class="float-right text-white">{{$comment->human_date}} -
                                    {{$comment->created_at?->format('Y-m-d')}}</a>
                                <p class="mb-0">
                                    {{$comment->message}}
                                </p>
                            </div>
                        </li>
                    @endforeach
                @endif

            </ul>


            <div class="card">
                <div class="card-header">
                    إضافة تعليق
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tickets.storeComment') }}" method="post" class="row g-3"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" value="{{auth()->id()}}">
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                        <div class="form-group">
                            <textarea name="message" class="form-control" rows="5" placeholder="أكتب تعليقك"></textarea>
                        </div>

                        <div class="text-center mt-2">
                            <button type="submit" class="main-btn px-4">@lang("Save")</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
