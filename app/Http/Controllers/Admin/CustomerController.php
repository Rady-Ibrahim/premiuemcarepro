<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Image;
use App\Models\Article;

use App\Traits\JodaResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    use JodaResource;
    public $model = 'App\Models\User';
    public $route = "admin.customers";
    public $view = "admin.customers";
    public $rules = [
        'name' => 'required',
        'image' => 'required',
        'customer_type' => 'required',
        'entity_id' => 'nullable',
        'release_date' => 'nullable',
        'phone' => 'required|unique:users,phone',
    ];

    public function query($query)
    {
        if (\request()->status) {
            if (\request()->status == "good") {
                // Good: Entities with an expire_date that is in the future
                return $query->latest()
                    ->where('expire_date', '>', now()) // Expire date in the future
                    ->where('type', 'customer')
                    ->paginate(10);
            } elseif (\request()->status == "warning") {
                // Warning: Entities whose expire_date is today
                return $query->latest()
                    ->where('expire_date', '=', now()->addMonth()->toDateString()) // Expire date one month from today
                    ->where('type', 'customer')
                    ->paginate(10);
            } elseif (\request()->status == "danger") {
                // Danger: Entities with an expire_date that has already passed
                return $query->latest()
                    ->where('expire_date', '<', now()) // Expire date in the past
                    ->where('type', 'customer')
                    ->paginate(10);
            }
        } else {
            // Default case: No specific status, return all entities of type 'customer'
            return $query->latest()
                ->where('type', 'customer')
                ->paginate(10);
        }
    }

    public function create()
    {

        $entities = User::where(['type' => 'entity', 'active' => 1])->get();
        return view('admin.customers.create', compact('entities'));
    }

    public function edit( $user)
    {
        $customer = User::findOrFail($user);
        $entities = User::where(['type' => 'entity', 'active' => 1])->get();
        return view('admin.customers.edit', compact('entities','customer'));
    }

    public function store(Request $request)
    {

        $data =   $request->validate([
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'customer');
                }),
            ],
            'uuid' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'customer'); // Change the type if needed
                }),
            ],
            'name' => 'required',
            'image' => 'required',
            'customer_type' => 'required',

            'entity_id' => 'nullable',
            'release_date' => 'nullable'
        ]);
        $data['type'] = "customer";
        $data['active'] = 1;
        $data['password'] = '$2y$12$Vs/TH.i74LnJaepIAE7JjeYBT6zOHjZfJqVPXDvC3rH.ghwOdpZOW';
        if ($photo = $request->file('image')) {
            $data['image'] = store_file($photo, 'customers');
        }
        if ($request->release_date) {
            // Parse the release_date from the request
            $releaseDate = Carbon::parse($request->release_date);

            // Add one year to the release_date
            $data['expire_date'] = $releaseDate->addYear()->toDateString();
        }
        User::create($data);

        return redirect()->route('admin.customers.index')->with('success', "تم الاضافة بنجاح");
    }

    public function update(Request $request,  $user)
    {
        // $data = $this->validateUpdateRequest();
        $user = User::findOrFail($user);
        $data =  $request->validate([
            'name' => 'required',
            'image' => 'nullable',
            'customer_type' => 'required',
            'uuid' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'customer'); // Change the type if needed
                })->ignore($user->id), // Ignore the current user's ID
            ],
            'entity_id' => 'nullable',
            'release_date' => 'nullable',
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'customer');
                })->ignore($user->id), // Ignoring the current user's ID
            ],
        ]);
        if ($photo = $request->file('image')) {
            delete_file($user->image);
            $data['image'] = store_file($photo, 'customers');
        }
        if($request->customer_type == "individual") {
        if ($request->release_date) {
            // Parse the release_date from the request
            $releaseDate = Carbon::parse($request->release_date);

            // Add one year to the release_date
            $data['expire_date'] = $releaseDate->addYear()->toDateString();
        }
        $data['entity_id'] = null;
    } else {
        $data['release_date'] = null;
        $data['expire_date'] = null;
    }
        $user->update($data);
        return redirect()->route('admin.customers.index')->with('success', 'تم التعديل بنجاح');
    }


    protected function beforeDestroy($customer)
    {
        if ($customer->image) {
            delete_file($customer->image);
        }
    }
}
