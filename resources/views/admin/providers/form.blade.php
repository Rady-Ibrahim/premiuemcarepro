<div class="col-md-12">
    <x-admin-alert></x-admin-alert>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label class="special-input">
            <span>اسم الهيئة الطبية</span>
            <input type="text" name="name" value="{{ old('name', isset($provider) ? $provider->name : '') }}"
                class="form-control">
        </label>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label class="special-input">
            <span> عنوان الهيئة الطبية</span>
            <input type="text" name="address"
                value="{{ old('address', isset($provider) ? $provider?->address : '') }}" class="form-control">
        </label>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label class="special-input">
            <span> نسبة الخصم</span>
            <input type="number" name="discount"
                value="{{ old('discount', isset($provider) ? $provider?->discount : 0) }}" class="form-control">
        </label>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label for="type">النوع</label>
        <select name="type" id="type" class="form-control">
            <option value="" disabled selected>اختر النوع</option>
            <option value="pharmacy">صيدليات </option>
            <option value="clinic">مراكز طبية</option>
            <option value="hospital">مستشفيات</option>
                    <option value="doctors">أطباء </option>

        </select>
    </div>
</div>
<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label for="category_id">التخصص الطبي</label>
        <select name="category_id" id="category_id" class="form-control">
            
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ isset($provider) && $provider->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label class="special-input">
            <span> رقم الموبايل</span>
            <input type="text" name="phone" value="{{ old('phone', isset($provider) ? $provider?->phone : '') }}"
                class="form-control">
        </label>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label for="city">المدينة</label>
        <select name="city" id="city" class="form-control">
            <option value="" disabled selected>اختر المدينة</option>
            <!-- You can directly define the options in the HTML or dynamically populate them in JavaScript -->
        </select>
    </div>
</div>

<div class="col-12 col-md-4 col-lg-3">
    <div class="inp-holder">
        <label for="area">المنطقة</label>
        <select name="area" id="area" class="form-control" disabled>
            <option value="" disabled selected>اختر المنطقة</option>
        </select>
    </div>
</div>



<div class="col-12 col-md-6 col-lg-6">
    <div class="inp-holder">
        <label class="special-input">
            <span>Latitude</span>
            <input type="text" name="latitude" id="latitude"
                value="{{ old('latitude', isset($provider) ? $provider->latitude : '') }}" class="form-control"
                readonly>
        </label>
    </div>
</div>

<div class="col-12 col-md-6 col-lg-6">
    <div class="inp-holder">
        <label class="special-input">
            <span>Longitude</span>
            <input type="text" name="longitude" id="longitude"
                value="{{ old('longitude', isset($provider) ? $provider->longitude : '') }}" class="form-control"
                readonly>
        </label>
    </div>
</div>

<!-- Map Container -->
<div class="col-12">
    <div id="map" style="height: 400px;"></div>
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
    <script src="{{ asset('ckeditor-articles/build/ckeditor.js') }}"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the map and set its view to the default coordinates
            const map = L.map('map').setView([30.033333, 31.233334], 7); // Default location to Cairo

            // Add the OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Marker variable to track the current position
            let marker;

            // Function to set latitude and longitude in the form
            function setLatLng(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }

            // Click event to place the marker and update coordinates
            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;

                // Remove previous marker if it exists
                if (marker) {
                    map.removeLayer(marker);
                }

                // Add marker to the clicked position
                marker = L.marker([lat, lng]).addTo(map);

                // Set the form's latitude and longitude inputs
                setLatLng(lat, lng);
            });

            // Set initial marker if latitude and longitude are already set
            const initialLat = document.getElementById('latitude').value;
            const initialLng = document.getElementById('longitude').value;
            if (initialLat && initialLng) {
                marker = L.marker([initialLat, initialLng]).addTo(map);
                map.setView([initialLat, initialLng], 10); // Adjust zoom as needed
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const governoratesWithAreas = {
                'القاهرة': ['مدينة نصر', 'المعادي', 'مصر الجديدة', 'الزمالك', 'شبرا', 'العباسية', 'عين شمس',
                    'حلوان', 'التجمع الخامس', 'وسط البلد'
                ],
                'الجيزة': ['الدقي', 'المهندسين', 'العجوزة', 'الهرم', '6 أكتوبر', 'الشيخ زايد', 'المنيب',
                    'إمبابة', 'البدرشين', 'الصف'
                ],
                'الإسكندرية': ['سموحة', 'العجمي', 'محرم بك', 'المنتزه', 'رشدي', 'زيزينيا', 'جليم', 'سيدي بشر',
                    'كرموز', 'المعمورة'
                ],
                'الدقهلية': ['المنصورة', 'طلخا', 'ميت غمر', 'دكرنس', 'شربين', 'المنزلة', 'الجمالية', 'بلقاس',
                    'نبروه', 'أجا'
                ],
                'الشرقية': ['الزقازيق', 'العاشر من رمضان', 'بلبيس', 'منيا القمح', 'ههيا', 'الإبراهيمية',
                    'فاقوس', 'أبو كبير', 'كفر صقر', 'الصالحية'
                ],
                'الغربية': ['طنطا', 'المحلة الكبرى', 'زفتى', 'كفر الزيات', 'السنطة', 'سمنود', 'بسيون', 'قطور',
                    'ميت حبيش', 'برما'
                ],
                'المنوفية': ['شبين الكوم', 'السادات', 'منوف', 'الباجور', 'أشمون', 'بركة السبع', 'تلا',
                    'الشهداء', 'قويسنا', 'سرس الليان'
                ],
                'المنيا': ['المنيا', 'ملوي', 'بني مزار', 'أبو قرقاص', 'مطاي', 'سمالوط', 'دير مواس', 'العدوة',
                    'مغاغة', 'الفكرية'
                ],
                'أسيوط': ['أسيوط', 'ديروط', 'القوصية', 'أبو تيج', 'صدفا', 'الغنايم', 'أبنوب', 'البداري',
                    'ساحل سليم', 'منفلوط'
                ],
                'سوهاج': ['سوهاج', 'جرجا', 'البلينا', 'طما', 'طهطا', 'أخميم', 'المراغة', 'المنشأة', 'ساقلته',
                    'الكوثر'
                ],
                'قنا': ['قنا', 'نجع حمادي', 'دشنا', 'قوص', 'فرشوط', 'أبو تشت', 'قفط', 'نقادة', 'الوقف',
                    'الحميدات'
                ],
                'الأقصر': ['الأقصر', 'إسنا', 'أرمنت', 'الكرنك', 'الزينية', 'الطود', 'البياضية', 'القرنة',
                    'بلاط', 'شرق السكة'
                ],
                'أسوان': ['أسوان', 'كوم أمبو', 'إدفو', 'دراو', 'نصر النوبة', 'كلابشة', 'أبو سمبل', 'الشلال',
                    'السد العالي', 'جزيرة سهيل'
                ],
                'البحر الأحمر': ['الغردقة', 'رأس غارب', 'سفاجا', 'مرسى علم', 'الشلاتين', 'القصير', 'حلايب',
                    'الجونة', 'البرامية', 'أبو رماد'
                ],
                'مرسى مطروح': ['مرسى مطروح', 'الضبعة', 'الحمام', 'سيدي براني', 'النجيلة', 'السلوم', 'سيوة',
                    'العلمين', 'مرزق', 'أبو حمادة'
                ],
                'الإسماعيلية': ['الإسماعيلية', 'فايد', 'القنطرة غرب', 'القنطرة شرق', 'أبو صوير', 'التل الكبير',
                    'القصاصين', 'العاشر من رمضان', 'عين غصين', 'نفيشة'
                ],
                'بورسعيد': ['بورسعيد', 'بورفؤاد', 'الزهور', 'المناخ', 'الشرق', 'العرب', 'الضواحي', 'الجنوب',
                    'حي المناصرة', 'حوض الروض'
                ],
                'السويس': ['السويس', 'عتاقة', 'الجناين', 'الأربعين', 'فيصل', 'بورتوفيق', 'حي الكويت',
                    'حوض الدرس', 'العمدة', 'العمدة الصغير'
                ],
                'دمياط': ['دمياط', 'رأس البر', 'فارسكور', 'كفر سعد', 'كفر البطيخ', 'الزرقا', 'عزبة البرج',
                    'ميت أبو غالب', 'شرباص', 'الخياطة'
                ],
                'بني سويف': ['بني سويف', 'الواسطي', 'الفشن', 'إهناسيا', 'ببا', 'سمسطا', 'ناصر', 'بني سليمان',
                    'الحيبة', 'المرصفا'
                ],'الفيوم':['الفيوم','إبشواي','إطسا','سنورس','طامية',' يوسف الصديق'],
                
                
            };

            const citySelect = document.getElementById('city');
            const areaSelect = document.getElementById('area');
            Object.keys(governoratesWithAreas).forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
            citySelect.addEventListener('change', function() {
                const selectedCity = this.value;
                const areas = governoratesWithAreas[selectedCity];

                areaSelect.innerHTML = '<option value="" disabled selected>اختر المنطقة</option>';

                if (areas) {
                    areas.forEach(area => {
                        const option = document.createElement('option');
                        option.value = area;
                        option.textContent = area;
                        areaSelect.appendChild(option);
                    });
                    areaSelect.removeAttribute('disabled');
                } else {
                    areaSelect.setAttribute('disabled', 'disabled');
                }
            });
        });
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.querySelector('select[name="type"]');
            const categorySelect = document.getElementById('category_id');

            function handleCategoryOptions() {
                if (typeSelect.value === 'pharmacy' || typeSelect.value === 'hospital') {
                    categorySelect.value = '1'; // Set category_id to 1
                    categorySelect.setAttribute('disabled', 'disabled'); // Disable the select
                } else {
                    categorySelect.removeAttribute('disabled'); // Enable the select
                    categorySelect.value = ''; // Reset value if other types are selected
                }
            }

            // Run the function on page load to check the initial state
            handleCategoryOptions();

            // Add event listener to handle change in type
            typeSelect.addEventListener('change', handleCategoryOptions);
        });
    </script> --}}
@endpush
