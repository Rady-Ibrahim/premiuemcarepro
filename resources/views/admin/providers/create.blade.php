@extends('admin.layouts.admin')
@section('title', 'المنشئات')
@section('content')
<div class="main-side">
    <div class="main-title">
        <div class="small">
         الرئيسية
        </div>
        <div class="large">
          اضافة هيئة طبية
        </div>
    </div>
    <form action="{{ route('admin.providers.store') }}" method="post" enctype="multipart/form-data">
        <div class="row g-3">
            @csrf
            @include('admin.providers.form')
        </div>
    </form>

</div>
@endsection
