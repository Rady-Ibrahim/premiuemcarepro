@extends('admin.layouts.admin')
@section('title', 'المنشئات')
@section('content')
<div class="main-side">
    <div class="main-title">
        <div class="small">
         الرئيسية
        </div>
        <div class="large">
          اضافة منشئة
        </div>
    </div>
    <form action="{{ route('admin.entities.store') }}" method="post" enctype="multipart/form-data">
        <div class="row g-3">
            @csrf
            @include('admin.entities.form')
        </div>
    </form>

</div>
@endsection
