@extends('admin.layouts.admin')
@section('title', 'المنشئات')
@section('content')
<div class="main-side">
    <div class="main-title">
        <div class="small">
            الرئيسية

        </div>
        <div class="large">
تعديل منشئة        </div>
    </div>
    <form action="{{ route('admin.entities.update',$entity->id) }}" method="post" enctype="multipart/form-data">
        <div class="row g-3">
            @csrf
            @method('PUT')
            @include('admin.entities.form')
        </div>
    </form>

</div>
@endsection
