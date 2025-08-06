<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Province;
use App\Models\Report;
use App\Models\City;
use App\Models\District;

class MapController extends Controller
{
    public function indexMap()
    {
        $reports = Report::with(['reporter', 'city', 'district', 'media', 'province'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        return Inertia::render(
            'Community/Map/MapPage',
            [
                'reports' => $reports,
                'provinces' => $provinces,
                'cities' => $cities,
                'districts' => $districts
            ]
        );
    }
}
