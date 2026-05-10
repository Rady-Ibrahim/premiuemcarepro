@include('front.layouts.parts.head')
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {{$error}}
</div>
@endforeach
@endif

<section class="main-section py-5">
    <div class="container">

        <div class="card card-register h-100">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="card-body shadow">
                    <div class="row gutters g-2">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mb-2 text-primary">تسجيل حساب</h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="mb-2 small-label">@lang("Name") </label>
                                <input type="text" name="name" class="form-control">

                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="mb-2 small-label">@lang("Phone") </label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="eMail" class="mb-2 small-label">البريد الاليكتروني</label>
                                <input type="email" name="email" class="form-control" id="eMail">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="password" class="small-label mb-2">كلمة المرور</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="city">المدينه</label>
                                <select name="city_id" id="city" class="form-select">
                                    <option value="">-- اختر المدينه--</option>
                                    <option value="">الرياض</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="text-right">
                                <label for="">
                                    <input type="checkbox" name="" id="">
                                    الموافقة علي الشروط
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="text-center">
                                <button type="submit" id="submit" class="btn btn-sm btn-primary">تسجيل</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>