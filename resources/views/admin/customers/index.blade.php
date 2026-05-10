@extends('admin.layouts.admin')
@section('content')
<div class="main-side">
    <x-message-admin/>

        <div class="main-title">
            <div class="small">
               الرئيسية
            </div>
            <div class="large">
                 الأفراد
            </div>
        </div>
        <div class="bar-options d-flex align-items-center justify-content-between flex-wrap gap-1 mb-2">
            <div class="btn-holder">
                <a href="{{route('admin.customers.create')}}" class="main-btn">اضافة <i
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
                    <th>اسم الفرد</th>
                    <th>صورة الفرد</th>
                    <th>تاريخ الاصدار</th>
                    <th> تاريخ الانتهاء</th>
                    <th>  حالة الاصدار</th>
                    <th>   تابع لمنشئة ام فرد</th>
                    <th>  اسم المنشئة </th>
                    <th>  رقم الموبايل</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody>
                    @php
    $today = \Carbon\Carbon::today();
                    @endphp

                @foreach($customers as $customer)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$customer->uuid}}</td>
                        <td>{{$customer->name}}</td>
                        <td>
                            @if(!$customer->image)
                                <img src="{{ asset('admin-asset/img/no-image.jpeg') }}" alt=""
                                     class="img-thumbnail img-preview"
                                     width="50">
                            @else
                                <img src="{{ display_file($customer->image) }}" alt="" class="img-thumbnail img-preview"
                                     width="50">
                            @endif
                        </td>
                        <td>{{$customer->release_date}}</td>
                        <td>{{$customer->expire_date}}</td>
                        <td>
                            @if($customer->expire_date)
                            @if ($customer->expire_date > $today)
    <span class="badge bg-success">قيد الاشتراك</span>
    @elseif (\Carbon\carbon::parse($customer->expire_date)->isToday())
    <span class="badge bg-warning">الاشتارك ينتهي اليوم</span>
@else
    <span class="badge bg-danger">تم انهاء الاشتراك</span>
@endif
@endif
                        </td>
                        <td>
                            @if ($customer->customer_type == "entity")
    <span class="badge bg-success"> تابع لمنشئة</span>
@elseif ($customer->customer_type == "individual")
    <span class="badge bg-success">  تابع لفرد</span>
@endif
                        </td>
                        <td>{{$customer->entity?->name}}</td>
                        <td>{{$customer->phone}}</td>

                        <td class="">
                            <div class="d-flex align-items-center gap-3">
                                <a href="{{route('admin.customers.edit' ,$customer)}}" class="">
                                    <i class="fa-solid fa-pen text-info icon-table"></i>
                                </a>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#delete{{$customer->id}}">
                                    <i class="fa-solid fa-trash text-danger icon-table"></i>
                                </button>
                                @include('admin.customers.delete-modal')
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
                <!-- Pagination with aria-label -->
        <nav aria-label="Pagination Navigation">
            {{ $customers->links('vendor.pagination.bootstrap-4') }}
        </nav>
        </div>

</div>
<script>
    document.getElementById('expiration').addEventListener('change', function() {
        var value = this.value; // Get the selected value

        // Redirect to the filter route with the selected value as a query parameter
        if (value) {
            window.location.href = '{{ route('admin.customers.index') }}?status=' + value;
        } else {
            window.location.href = '{{ route('admin.customers.index') }}';
        }
    });
</script>
@endsection
