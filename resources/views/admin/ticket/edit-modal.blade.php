<div class="modal fade" id="edit{{ $ticket->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تعديل تذكرة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                <div class="modal-body row g-3">
                    @csrf
                    @method('PUT')
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="">العميل</label>
                            <select name="user_id" id="" class="form-control">
                                <option value="">اختر العميل</option>
                                @foreach (App\Models\User::whereIn('type', ['admin', 'user'])->get() as $client)
                                    {{-- <option value="{{ $client->id }}"
                                        {{ $ticket->user_id == $client->id ? 'selected' : '' }}>{{ $client->name }} -
                                        {{ __($client->type) }}</option> --}}
                                        <option value="{{ $client->id }}"
                                            {{ $ticket->user_id == $client->id ? 'selected' : '' }}>{{ $client->name }}
                                            </option>
                                @endforeach
                            </select>
                        </div>
                    </div>




                    <div class="col-12 col-md-6">
                        <div class="form-group ">
                            <label for="">عنوان التذكرة</label>
                            <input type="text" name="subject" id="" value="{{ $ticket->subject }}"
                                   class="form-control">
                        </div>

                    </div>


                    <div class="col-12">
                        <div class="form-group">
                            <label for="">@lang("admin.Description")</label>
                            <textarea name="description" class="form-control" rows="8">{{ $ticket->description }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-group ">
                            <label for="">@lang("Status")</label>
                            <select name="status" id="" class="form-control">
                                <option value="">@lang("Choose status")</option>
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>مفتوحة</option>
                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}> قيد الرد
                                </option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>مغلقة</option>

                            </select>
                        </div>
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
