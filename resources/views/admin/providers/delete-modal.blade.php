<div class="modal fade" id="delete{{$provider->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">حذف هيئة طبية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                هل انت متأكد من عملية الحذف؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">إغلاق</button>
                <form action="{{ route('admin.providers.destroy', $provider->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-3">نعم</button>
                </form>
            </div>
        </div>
    </div>
</div>
