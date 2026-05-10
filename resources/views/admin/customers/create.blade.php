@extends('admin.layouts.admin')
@section('title', 'الافراد')
@section('content')
<div class="main-side">
    <div class="main-title">
        <div class="small">
         الرئيسية
        </div>
        <div class="large">
          اضافة فرد
        </div>
    </div>
    <form action="{{ route('admin.customers.store') }}" method="post" enctype="multipart/form-data">
        <div class="row g-3">
            @csrf
            @include('admin.customers.form')
        </div>
    </form>

</div>
@endsection
