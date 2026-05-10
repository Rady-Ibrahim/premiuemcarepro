<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\Controller;
use App\Models\Provider;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = Provider::ordered();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('specializations')) {
            $query->where('specializations', 'like', '%' . $request->input('specializations') . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('is_insurance_accepted')) {
            $query->where('is_insurance_accepted', $request->input('is_insurance_accepted'));
        }

        if ($request->filled('is_emergency_available')) {
            $query->where('is_emergency_available', $request->input('is_emergency_available'));
        }

        if ($request->filled('area')) {
            $query->where('area', $request->input('area'));
        }

        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $distance = $request->input('distance', 1);

            $query->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                [$latitude, $longitude, $latitude, $distance]
            );
        }

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $medicalPlaces = $query->paginate($size, ['*'], 'page', $page);

        return response()->json($medicalPlaces);
    }

    public function show($id)
    {
        $medicalPlace = Provider::findOrFail($id);
        return response()->json($medicalPlace);
    }

    public function store(Request $request) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
