@extends('admin.layouts.admin')
@section('content')
<div class="main-side">
    <x-message-admin/>

    <div class="main-title">
        <div class="small">الرئيسية</div>
        <div class="large">الهيئات الطبية</div>
    </div>
    <div class="bar-options d-flex align-items-center justify-content-between flex-wrap gap-1 mb-2">
        <div class="btn-holder">
            <a href="{{ route('admin.providers.create') }}" class="main-btn">اضافة <i class="fas fa-plus"></i></a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="main-table mb-2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الهيئة الطبية</th>
                    <th>عنوان الهيئة الطبية</th>
                    <th>نسبة الخصم</th>
                    <th>التخصص الطبي</th>
                    <th>رقم التليفون</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($providers as $provider)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $provider->name }}</td>
                        <td>{{ $provider->address }}</td>
                        <td>{{ $provider->discount }}</td>
                        <td>{{ $provider->category?->name }}</td>
                        <td>{{ $provider->phone }}</td>
                        <td class="">
                            <div class="d-flex align-items-center gap-3">
                                <a href="{{ route('admin.providers.edit', $provider) }}" class="">
                                    <i class="fa-solid fa-pen text-info icon-table"></i>
                                </a>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#delete{{ $provider->id }}">
                                    <i class="fa-solid fa-trash text-danger icon-table"></i>
                                </button>
                                @include('admin.providers.delete-modal')
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination with aria-label -->
        <nav aria-label="Pagination Navigation">
            {{ $providers->links('vendor.pagination.bootstrap-4') }}
        </nav>

    </div>
</div>
@endsection
