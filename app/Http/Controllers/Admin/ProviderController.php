<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Image;
use App\Traits\JodaResource;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Provider;
use App\Models\User;

class ProviderController extends Controller
{
    use JodaResource;
    public $model = 'App\Models\Provider';
    public $route = "admin.providers";
    public $view = "admin.providers";
    public $rules = [
        'name' => 'required',
        'address' => 'nullable',
        'discount' => 'required',
        'category_id' => 'required',
        'phone' => 'nullable|unique:providers,phone',
        'latitude' => 'nullable|numeric',  // Add validation for latitude
        'longitude' => 'nullable|numeric',
        'type' => 'nullable',
        'city' => 'nullable',
                'area' => 'nullable',

    ];

    public function query($query)
    {

            return $query->latest()
                         ->paginate(10);

    }

    public function create()
    {

        $categories = Category::all();
        return view('admin.providers.create', compact('categories'));
    }

    public function edit( $provider)
    {
        $provider = Provider::findOrFail($provider);
        $categories = Category::all();
        return view('admin.providers.edit', compact('categories','provider'));
    }

    public function store(Request $request)
    {
        $data = $this->validateStoreRequest();
        Provider::create($data);

        return redirect()->route('admin.providers.index')->with('success', "تم الاضافة بنجاح");
    }

    public function update(Request $request,  $provider)
    {
        // $data = $this->validateUpdateRequest();
        $provider = Provider::findOrFail($provider);
        $data =  $request->validate([
            'name' => 'required',
            'address' => 'nullable',
            'discount' => 'required',
            'category_id' => 'required',
            'phone' => 'nullable|unique:providers,phone,' . $provider->id,
            'latitude' => 'nullable|numeric',  // Validation for latitude
            'longitude' => 'nullable|numeric',
            'city' => 'nullable',
            'area' => 'nullable',
            'type' => 'nullable',
        ]);
        $provider->update($data);
        return redirect()->route('admin.providers.index')->with('success', 'تم التعديل بنجاح');
    }


    protected function beforeDestroy($Article) {}
}
