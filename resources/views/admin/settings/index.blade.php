@extends('admin.layouts.admin')

@section('content')

<div class="main-side">
    <div class="main-title">
        <div class="small">
           الرئيسية
        </div>
        <div class="large">
           الاعدادات
        </div>
    </div>
    <x-admin-alert></x-admin-alert>
    
    <!-- Phone Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">اعدادات عامة</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="inp-holder">
                            <label class="special-input">
                                <span>رقم التليفون</span>
                                <input type="text" name="phone" placeholder="رقم التليفون" class="form-control"
                                value="{{ setting()->get('phone') }}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-holder d-flex align-items-end h-100">
                            <button type="submit" class="main-btn w-100">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Chat Settings -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">اعدادات الشات</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="inp-holder mb-3">
                            <label class="special-input">
                                <span>ايميل الادمن للإشعارات</span>
                                <input type="email" name="admin_chat_email" placeholder="admin@example.com" class="form-control"
                                value="{{ $settings['admin_chat_email'] ?? 'admin@example.com' }}">
                            </label>
                        </div>
                        <div class="inp-holder">
                            <label class="special-input">
                                <span>تفعيل إشعارات الشات</span>
                                <select name="enable_chat_notifications" class="form-control">
                                    <option value="1" {{ ($settings['enable_chat_notifications'] ?? '1') == '1' ? 'selected' : '' }}>مفعل</option>
                                    <option value="0" {{ ($settings['enable_chat_notifications'] ?? '1') == '0' ? 'selected' : '' }}>معطل</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-holder d-flex align-items-end h-100">
                            <button type="submit" class="main-btn w-100">حفظ</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')

@endpush
