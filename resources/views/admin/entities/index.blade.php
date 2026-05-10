@extends('admin.layouts.admin')
@section('content')
<div class="main-side">
    <x-message-admin/>

        <div class="main-title">
            <div class="small">
               الرئيسية
            </div>
            <div class="large">
                 المنشئات
            </div>
        </div>
        <div class="bar-options d-flex align-items-center justify-content-between flex-wrap gap-1 mb-2">
            <div class="btn-holder">
                <a href="{{route('admin.entities.create')}}" class="main-btn">اضافة <i
                        class="fas fa-plus"></i></a>
            </div>
            <div class="holder-inp-btn d-flex align-items-center gap-1">

                    <select class="form-control" name="expiration" id="expiration">
                        <option value="">-- اختر الحالة --</option>
                        <option value="">--  الكل --</option>
                        <option value="good">قيد الاشتراك</option>
                        <option value="warning"> تنتهي بعد شهر</option>
                        <option value="danger">  تم الانتهاء</option>
                    </select>

            </div>
        </div>
        <div class="table-responsive">
            <table class="main-table mb-2">
                <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>اسم المنشئة</th>
                    <th>عدد التابعين للمنشئة</th>
                    <th>تاريخ الاصدار</th>
                    <th> تاريخ الانتهاء</th>
                    <th>  حالة الاصدار</th>
                    <th>  رقم الموبايل</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody>
                    @php
    $today = \Carbon\Carbon::today();
                    @endphp

                @foreach($entities as $entity)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$entity->uuid}}</td>
                        <td>{{$entity->name}}</td>
                        <td>{{$entity->entity_no}}</td>
                        <td>{{$entity->release_date}}</td>
                        <td>{{$entity->expire_date}}</td>
                        <td>
                            @if ($entity->expire_date > $today)
    <span class="badge bg-success">قيد الاشتراك</span>
@elseif (\Carbon\carbon::parse($entity->expire_date)->isToday())
    <span class="badge bg-warning">الاشتارك ينتهي اليوم</span>
@else
    <span class="badge bg-danger">تم انهاء الاشتراك</span>
@endif
                        </td>
                        <td>{{$entity->phone}}</td>

                        <td class="">
                            <div class="d-flex align-items-center gap-3">
                                <a href="{{route('admin.entities.edit' ,$entity)}}" class="">
                                    <i class="fa-solid fa-pen text-info icon-table"></i>
                                </a>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#delete{{$entity->id}}">
                                    <i class="fa-solid fa-trash text-danger icon-table"></i>
                                </button>
                                @include('admin.entities.delete-modal')
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
            <!-- Pagination with aria-label -->
        <nav aria-label="Pagination Navigation">
            {{ $entities->links('vendor.pagination.bootstrap-4') }}
        </nav>
        </div>

</div>
<script>
    document.getElementById('expiration').addEventListener('change', function() {
        var value = this.value; // Get the selected value

        // Redirect to the filter route with the selected value as a query parameter
        if (value) {
            window.location.href = '{{ route('admin.entities.index') }}?status=' + value;
        } else {
            window.location.href = '{{ route('admin.entities.index') }}';
        }
    });
</script>
@endsection
