<div class="modal fade" id="create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">إضافة تذكرة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.tickets.store') }}" method="POST">
                <div class="modal-body row g-3">
                    @csrf
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="">العميل</label>
                            <select name="user_id" id="" class="form-control">
                                <option value="">اختر العميل</option>
                                @foreach (App\Models\User::whereIn('type', ['admin', 'user'])->get() as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}

                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <label for="">عنوان التذكرة</label>
                            <input type="text" name="subject" id="" class="form-control">
                        </div>

                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">@lang("admin.Description")</label>
                            <textarea name="description" class="form-control" rows="8"></textarea>
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
