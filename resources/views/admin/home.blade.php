@extends('admin.layouts.admin')

@section('content')

<div class="main-side">
    <div class="main-title">

        <div class="large">
            الرئيسية
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="box-statistic">
                <div class="right-side">
                    <h6 class="name">الهيئات الطبية</h6>
                    <h3 class="amount"><span class="num-stat" data-goal="{{\App\Models\Provider::count()}}">{{\App\Models\Provider::count()}}</span></h3>
                    <a href="{{route('admin.providers.index')}}" class="link-view">كل الهيئات الطبية</a>
                </div>
                <div class="left-side">
                    <p class="status-number up"> </i></p>
                    <div class="icon-holder green">
                        <i class="fa-regular fa-circle-user"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="box-statistic">
                <div class="right-side">
                    <h6 class="name">المنشئات</h6>
                    <h3 class="amount"><span class="num-stat" data-goal="{{\App\Models\User::where('type','entity')->count()}}">{{\App\Models\User::where('type','entity')->count()}}</span></h3>
                    <a href="{{route('admin.entities.index')}}" class="link-view">كل المنشئات</a>
                </div>
                <div class="left-side">
                    <p class="status-number down"></i></p>
                    <div class="icon-holder blue">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="box-statistic">
                <div class="right-side">
                    <h6 class="name">الافراد</h6>
                    <h3 class="amount"><span class="num-stat" data-goal="{{\App\Models\User::where('type','customer')->count()}}">{{\App\Models\User::where('type','customer')->count()}}</span></h3>
                    <a href="{{route('admin.customers.index')}}" class="link-view">كل الافراد</a>
                </div>
                <div class="left-side">
                    <p class="status-number down"></i></p>
                    <div class="icon-holder blue">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="box-statistic">
                <div class="right-side">
                    <h6 class="name">الدعم الفني</h6>
                    <h3 class="amount"><span class="num-stat" data-goal="{{ App\Models\Ticket::count() }}">{{ App\Models\Ticket::count() }}</span></h3>
                    <a href="{{route('admin.tickets.index')}}" class="link-view">كل الدعم الفني</a>
                </div>
                <div class="left-side">
                    <p class="status-number"></p>
                    <div class="icon-holder">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('js')

@endpush
