<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use App\Models\Department;
use App\Models\Relationship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
         Category::create([
            'name' => 'جميع التخصصات',
        ]);
        Category::create([
            'name' => 'عيون',
        ]);
        Category::create([
            'name' => 'أنف وأذن وحنجرة',
        ]);
        Category::create([
            'name' => 'أعصاب وعمود فقري',
        ]);
        Category::create([
            'name' => 'مخ وأعصاب',
        ]);
        Category::create([
            'name' => 'قلب وقسطرة',
        ]);
        Category::create([
            'name' => 'أسنان',
        ]);
        Category::create([
            'name' => 'علاج طبيعي',
        ]);
        Category::create([
            'name' => 'جلدية وليزر',
        ]);
        Category::create([
            'name' => 'جراحة واوعية',
        ]);
        Category::create([
            'name' => 'مناظير جهاز هضمي',
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
