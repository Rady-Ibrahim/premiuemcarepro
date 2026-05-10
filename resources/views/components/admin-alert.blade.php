<div>
    @if(session()->has('error'))
    <div class="alert w-100 mb-0 mt-4 alert-warning alert-dismissible fade show">
        <div class="d-flex align-items-center gap-2 justify-content-between">
            <h6><i class="icon fas fa-exclamation-triangle"></i> {{ trans('admin.alert') }}!</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        {{ session('error') }}
    </div>
    @endif
    @if(session()->has('success'))
    <div class="alert w-100 alert-success alert-pop alert-dismissible fade show">
        <div class="d-flex align-items-center  gap-2 justify-content-between">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <ul class="list-unstyled mb-0">
            @foreach ($errors->all() as $error)
            <li class="mb-1">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
