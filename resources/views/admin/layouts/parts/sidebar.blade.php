<div class="sidebar" wire:ignore.self>
    <div class="tog-active d-none d-lg-block" data-tog="true" data-active=".app">
        <i class="fas fa-bars"></i>
    </div>
    <ul class="list">
        <li class="list-item {{request()->routeIs('admin.home') ? 'active' :''}}">
            <a href="{{ route('admin.home') }}" wire:navigate>
                <div>
                    <i class="fa-solid fa-grip"></i>
                    الرئيسية
                </div>
            </a>
        </li>

        <li class="list-item {{request()->routeIs('admin.providers') ? 'active' :''}}">
            <a href="{{ route('admin.providers.index') }}">
                <div>
                    <i class="fa-solid fa-bars-progress"></i>
                    الهيئات الطبية
                </div>
            </a>
        </li>


        <li class="list-item">
            <a data-bs-toggle="collapse" href="#users" aria-expanded="false">
                <div>
                    <i class="fa-solid fa-users"></i>
                   المنشئات والافراد
                </div>
                <i class="fa-solid fa-angle-right arrow"></i>
            </a>
        </li>
        <div class="collapse item-collapse" id="users">
            <li class="list-item">
                <a href="{{route('admin.entities.index')}}">
                    <div>
                        <i class="fa-solid fa-ticket "></i>
                       المنشئات

                    </div>
                </a>
                <a href="{{route('admin.customers.index')}}">
                    <div>
                        <i class="fa-solid fa-ticket "></i>
                        الأفراد

                    </div>
                </a>
            </li>
        </div>

        <li class="list-item">
            <a data-bs-toggle="collapse" href="#support" aria-expanded="false">
                <div>
                    <i class="fa-solid fa-headset "></i>
                   تذاكر الدعم الفني
                </div>
                <i class="fa-solid fa-angle-right arrow"></i>
            </a>
        </li>
        <div class="collapse item-collapse" id="support">
            <li class="list-item">
                <a href="{{ route('admin.tickets.index') }}">
                    <div>
                        <i class="fa-solid fa-ticket "></i>
                       كل التذاكر
                        <div class="main-badge">{{ App\Models\Ticket::count() }}</div>
                    </div>
                </a>
            </li>
        </div>

        <li class="list-item {{request()->routeIs('admin.settings') ? 'active' :''}}">
            <a href="{{ route('admin.settings') }}">
                <div>
                    <i class="fa-solid fa-gear icon"></i>
                    الاعدادات
                </div>
            </a>
        </li>

    </ul>
</div>
