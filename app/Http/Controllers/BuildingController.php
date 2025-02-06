<?php

namespace App\Http\Controllers;

use App\Models\Building;

class BuildingController extends Controller
{
    /**
     * Вывести список всех зданий.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $buildings = Building::all();
        return response()->json($buildings);
    }
}
