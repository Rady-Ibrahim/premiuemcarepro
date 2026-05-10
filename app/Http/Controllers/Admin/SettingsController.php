<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function settings(Request $request)
    {
        // $data = $request->all();
        $data = $request->only([ 'phone']);
        setting($data)->save();
        return back()->with('success', "تم التحديث بنجاح");
    }
}
