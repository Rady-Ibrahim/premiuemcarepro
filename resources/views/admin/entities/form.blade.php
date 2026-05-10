<div class="col-md-12">
    <x-admin-alert></x-admin-alert>
</div>

<div class="col-12 col-md-4 col-lg-3">
        <div class="inp-holder">
        <label class="special-input">
            <span>اسم المنشئة</span>
            <input type="text" name="name" value="{{ old('name',$entity?->name) }}" class="form-control">
        </label>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label class="special-input">
        <span> عدد التابعين للمنشئة</span>
        <input type="number" name="entity_no" value="{{ old('entity_no',$entity?->entity_no) }}" class="form-control">
    </label>
</div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label class="special-input">
        <span>   تاريخ الاصدار</span>
        <input type="date" name="release_date" value="{{ old('release_date',$entity?->release_date) }}" class="form-control">
    </label>
</div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label class="special-input">
        <span>   تاريخ الانتهاء</span>
        <input type="date" name="expire_date" value="{{ old('expire_date',$entity?->expire_date) }}" class="form-control">
    </label>
</div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label class="special-input">
        <span>    رقم الموبايل</span>
        <input type="text" name="phone" value="{{ old('phone',$entity?->phone) }}" class="form-control">
    </label>
</div>
</div>
<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label class="special-input">
        <span>  ID</span>
        <input type="text" name="uuid" value="{{ old('uuid',isset($entity) ? $entity->uuid : '') }}" class="form-control">
    </label>
</div>
</div>

<div class="col-12 m-0">
    <hr class="m-0 border-0">
</div>

<div class="col-12 col-md-12 col-lg-12 col-xl-12">
    <div class="btn-holder mt-2">
        <button type="submit" class="main-btn">حفظ</button>
    </div>
</div>
@push('js')
<script src="{{asset('ckeditor-articles/build/ckeditor.js')}}"></script>

@endpush
