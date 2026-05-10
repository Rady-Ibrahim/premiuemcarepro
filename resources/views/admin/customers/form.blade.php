<div class="col-md-12">
    <x-admin-alert></x-admin-alert>
</div>

<div class="col-12 col-md-4 col-lg-3">
        <div class="inp-holder">
        <label class="special-input">
            <span>اسم الفرد</span>
            <input type="text" name="name" value="{{ old('name',isset($customer) ? $customer->name : '') }}" class="form-control">
        </label>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <label class="special-input">
    <span>صورة الفرد</span>
    <input type="file" name="image" class="form-control mb-3">
</label>
@if(isset($customer))
            <img src="{{ $customer?->image ? display_file($customer?->image) : asset('admin-asset/img/no-img.jpg') }}" alt="" class="img-thumbnail img-preview" width="50">
@endif

</div>





<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label>
        <input type="radio" name="customer_type" id="radioEntity" value="entity" onchange="toggleFields()"
            {{ isset($customer) && $customer->customer_type == 'entity' ? 'checked' : '' }}>
        تابع لمنشئة
    </label>
    <label>
        <input type="radio" name="customer_type" id="radioIndividual" value="individual" onchange="toggleFields()"
            {{ isset($customer) && $customer->customer_type == 'individual' ? 'checked' : '' }}>
        فرد
    </label>
</div>
</div>

<div id="entityField" style="display: {{ isset($customer) && $customer->customer_type == 'entity' ? 'block' : 'none' }};" class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label for="entity_id">المنشئة</label>
        <select name="entity_id" id="entity_id" class="form-control">
            <option value="">اختر المنشئة</option>
            @foreach ($entities as $entity)
                <option value="{{ $entity->id }}" {{ isset($customer) && $customer->entity_id == $entity->id ? 'selected' : '' }}>{{ $entity->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div id="individualField" style="display: {{ isset($customer) && $customer->customer_type == 'individual' ? 'block' : 'none' }};" class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label class="special-input">
            <span>تاريخ الاصدار</span>
            <input type="date" name="release_date" value="{{ old('release_date', isset($customer) ? $customer->release_date : '') }}" class="form-control">
        </label>
    </div>
</div>



<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label class="special-input">
        <span>    رقم الموبايل</span>
        <input type="text" name="phone" value="{{ old('phone',isset($customer) ? $customer->phone : '') }}" class="form-control">
    </label>
</div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
    <label class="special-input">
        <span>  ID</span>
        <input type="text" name="uuid" value="{{ old('uuid',isset($customer) ? $customer->uuid : '') }}" class="form-control">
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
<script>
    function toggleFields() {
        const entityField = document.getElementById('entityField');
        const individualField = document.getElementById('individualField');

        const isEntityChecked = document.getElementById('radioEntity').checked;
        const isIndividualChecked = document.getElementById('radioIndividual').checked;

        // Show entity field if the Entity radio button is checked
        entityField.style.display = isEntityChecked ? 'block' : 'none';

        // Show individual field if the Individual radio button is checked
        individualField.style.display = isIndividualChecked ? 'block' : 'none';
    }

    // Call toggleFields on page load to set the initial state correctly
    window.onload = function() {
        toggleFields();
    };
</script>


@endpush
