<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Image;
use App\Traits\JodaResource;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;

class EntityController extends Controller
{
    use JodaResource;
    public $model = 'App\Models\User';
    public $route = "admin.entities";
    public $view = "admin.entities";
    public $rules = [];

    public function query($query)
    {
        if (\request()->status) {
            if (\request()->status == "good") {
                // Good: Entities with an expire_date that is in the future
                return $query->latest()
                    ->where('expire_date', '>', now()) // Expire date in the future
                    ->where('type', 'entity')
                    ->paginate(10);
            } elseif (\request()->status == "warning") {
                // Warning: Entities whose expire_date after one month
                return $query->latest()
                    ->where('expire_date', '=', now()->addMonth()->toDateString()) // Expire date one month from today
                    ->where('type', 'entity')
                    ->paginate(10);
            } elseif (\request()->status == "danger") {
                // Danger: Entities with an expire_date that has already passed
                return $query->latest()
                    ->where('expire_date', '<', now()) // Expire date in the past
                    ->where('type', 'entity')
                    ->paginate(10);
            }
        } else {
            // Default case: No specific status, return all entities of type 'entity'
            return $query->latest()
                ->where('type', 'entity')
                ->paginate(10);
        }
    }

    public function store(Request $request)
    {
        // $data = $this->validateStoreRequest();
        $data =   $request->validate([
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'entity');
                }),
            ],
            'uuid' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'entity'); // Change the type if needed
                }),
            ],
            'name' => 'required',
            'entity_no' => 'required',
            'release_date' => 'required',
            'expire_date' => 'required'
        ]);
        $data['type'] = "entity";
        $data['active'] = 1;
        $data['password'] = '$2y$12$Vs/TH.i74LnJaepIAE7JjeYBT6zOHjZfJqVPXDvC3rH.ghwOdpZOW';
        User::create($data);

        return redirect()->route('admin.entities.index')->with('success', "تم الاضافة بنجاح");
    }

    public function update(Request $request,  $user)
    {
        // $data = $this->validateUpdateRequest();

        $user = User::findOrFail($user);

        $data =   $request->validate([
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'entity');
                })->ignore($user->id), // Ignoring the current user's ID
            ],
            'uuid' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('type', 'entity'); // Change the type if needed
                })->ignore($user->id), // Ignore the current user's ID
            ],
            'name' => 'required',
            'entity_no' => 'required',
            'release_date' => 'required',
            'expire_date' => 'required'
        ]);
        $user->update($data);
        return redirect()->route('admin.entities.index')->with('success', 'تم التعديل بنجاح');
    }


    protected function beforeDestroy($Article) {}
}
