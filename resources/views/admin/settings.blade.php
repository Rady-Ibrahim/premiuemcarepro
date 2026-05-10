@extends('admin.layouts.admin')

@section('content')

<div class="main-side">
    <div class="main-title">
        <div class="small">
           الرئيسية
        </div>
        <div class="large">
           الاعدادات
        </div>
    </div>
    <x-admin-alert></x-admin-alert>
    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 mb-2 g-3">

        <div class="col">
            <div class="inp-holder">
                <label class="special-input">
                    <span>رقم التليفون</span>
                    <input type="text" name="phone" placeholder="رقم التليفون" class="form-control"
                value="{{ setting()->get('phone') }}">
                </label>
            </div>
        </div>

        <div class="col-12 col-md-12 col-lg-12 col-xl-12">
            <div class="btn-holder d-flex justify-content-center mt-4">
                <button  type="submit" class="main-btn">حفظ</button>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection
@push('js')

@endpush
