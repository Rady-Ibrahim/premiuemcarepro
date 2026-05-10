@extends('admin.layouts.admin')
@section('title', 'الافراد')
@section('content')
<div class="main-side">
    <div class="main-title">
        <div class="small">
            الرئيسية

        </div>
        <div class="large">
تعديل فرد        </div>
    </div>
    <form action="{{ route('admin.customers.update',$customer->id) }}" method="post" enctype="multipart/form-data">
        <div class="row g-3">
            @csrf
            @method('PUT')
            @include('admin.customers.form')
        </div>
    </form>

</div>
@endsection
