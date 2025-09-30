<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    //
    public function show(Request $request)
    {
        $city = $request->input('city', 'Lahore');
        $country = $request->input('country', 'Pakistan');

        // Geocoding API to get lat/lon
        $geoRes = Http::withOptions([
            'verify' => false,
        ])->get('https://geocoding-api.open-meteo.com/v1/search', [
            'name' => $city,
            'country' => $country,
            'count' => 1
        ]);
        if (!$geoRes->successful() || empty($geoRes['results'][0])) {
            return response()->json(['error' => 'Location not found.'], 404);
        }
        $lat = $geoRes['results'][0]['latitude'];
        $lon = $geoRes['results'][0]['longitude'];

        // Weather API
        $weatherRes = Http::withOptions([
            'verify' => false,
        ])->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $lat,
            'longitude' => $lon,
            'current_weather' => true,
        ]);
        if (!$weatherRes->successful()) {
            return response()->json(['error' => 'Weather data not available.'], 500);
        }
        return response()->json([
            'location' => $city . ', ' . $country,
            'weather' => $weatherRes['current_weather'] ?? null
        ]);
    }
}
