<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Получить список организаций, находящихся в конкретном здании.
     *
     * @param  int  $buildingId  Идентификатор здания
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByBuilding($buildingId)
    {
        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->where('building_id', $buildingId)
            ->get();

        return response()->json($organizations);
    }

    /**
     * Получить список организаций по виду деятельности с учётом вложенности.
     *
     * @param  int  $activityId  Идентификатор вида деятельности
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByActivity($activityId)
    {
        $activity = Activity::findOrFail($activityId);
        $activityIds = $this->getAllDescendantActivityIds($activity);
        $activityIds[] = $activity->id;

        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->whereHas('activities', function ($query) use ($activityIds) {
                $query->whereIn('activities.id', $activityIds);
            })->get();

        return response()->json($organizations);
    }

    /**
     * Рекурсивно получить идентификаторы всех потомков вида деятельности.
     *
     * @param  \App\Models\Activity  $activity  Модель вида деятельности
     * @return int[]
     */
    private function getAllDescendantActivityIds(Activity $activity)
    {
        $ids = [];
        foreach ($activity->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllDescendantActivityIds($child));
        }
        return $ids;
    }

    /**
     * Получить организации, находящиеся в заданном радиусе относительно указанной точки.
     * Параметры: lat, lng, radius (в километрах).
     *
     * @param  \Illuminate\Http\Request  $request  HTTP-запрос с параметрами lat, lng, radius
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByLocation(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = $request->input('radius');

        if (!$lat || !$lng || !$radius) {
            return response()->json(['error' => 'Не переданы необходимые параметры (lat, lng, radius)'], 400);
        }

        // Используем формулу гаверсинуса (Haversine) для расчёта расстояния
        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                        * cos(radians(longitude) - radians(?)) + sin(radians(?))
                        * sin(radians(latitude))))";

        // Получаем здания в заданном радиусе
        $buildings = \App\Models\Building::select('*')
            ->selectRaw("$haversine AS distance", [$lat, $lng, $lat])
            ->having("distance", "<", $radius)
            ->get();

        $buildingIds = $buildings->pluck('id')->toArray();

        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->whereIn('building_id', $buildingIds)
            ->get();

        return response()->json($organizations);
    }

    /**
     * Получение детальной информации об организации по её идентификатору.
     *
     * @param  int  $id  Идентификатор организации
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $organization = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->findOrFail($id);

        return response()->json($organization);
    }

    /**
     * Поиск организаций по названию вида деятельности с учетом вложенности.
     * Например, поиск по "Еда" должен вернуть организации с "Еда", "Мясная продукция", "Молочная продукция".
     *
     * @param  \Illuminate\Http\Request  $request  HTTP-запрос с параметром activity
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByActivity(Request $request)
    {
        $activityName = $request->input('activity');

        if (!$activityName) {
            return response()->json(['error' => 'Не передан параметр activity'], 400);
        }

        $activity = Activity::where('name', $activityName)->first();

        if (!$activity) {
            return response()->json([], 200);
        }

        $activityIds = $this->getAllDescendantActivityIds($activity);
        $activityIds[] = $activity->id;

        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->whereHas('activities', function ($query) use ($activityIds) {
                $query->whereIn('activities.id', $activityIds);
            })->get();

        return response()->json($organizations);
    }

    /**
     * Поиск организаций по названию.
     *
     * @param  \Illuminate\Http\Request  $request  HTTP-запрос с параметром name
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByName(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return response()->json(['error' => 'Не передан параметр name'], 400);
        }

        $organizations = Organization::with(['building', 'phoneNumbers', 'activities'])
            ->where('name', 'like', "%{$name}%")
            ->get();

        return response()->json($organizations);
    }
}
