<div class="modal fade" id="add_comment{{$ticket->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">إضافة تعليق - {{ $ticket->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.tickets.storeComment') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_id" value="1">
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    <div class="form-group mb-3">
                        <label for="">التعليق</label>
                        <textarea name="message" class="form-control" rows="5"></textarea>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">@lang("Save")</button>
                </div>
            </form>

        </div>
    </div>
</div>
