<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="REST API для справочника Организаций, Зданий и Деятельностей",
 *      description="Документация Swagger для REST API приложения",
 * )
 *
 * @OA\Server(
 *      url="http://localhost",
 *      description="Локальный сервер"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="api_key",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * )
 *
 * @OA\Tag(
 *     name="Organizations",
 *     description="Операции, связанные с организациями"
 * )
 */

class OrganizationController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/organizations/by-building/{buildingId}",
     *     summary="Получить список организаций по идентификатору здания",
     *     description="Возвращает массив организаций, относящихся к указанному зданию (building_id), включая связанные данные (здание, телефоны, виды деятельности).",
     *     operationId="getOrganizationsByBuilding",
     *     tags={"Organizations"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         description="Идентификатор здания, по которому фильтруются организации",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Здание не найдено (или нет организаций)"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/organizations/by-activity/{activityId}",
     *     summary="Список организаций по виду деятельности",
     *     description="Возвращает массив организаций, имеющих указанный вид деятельности (activityId), включая связанные данные (здание, телефоны, виды деятельности).",
     *     operationId="getOrganizationsByActivity",
     *     tags={"Organizations"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="Идентификатор вида деятельности, по которому фильтруются организации",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Вид деятельности не найден или нет подходящих организаций"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/organizations/by-location",
     *     summary="Список организаций по координатам и радиусу",
     *     description="Возвращает массив организаций, находящихся в заданном радиусе от указанных координат (lat, lng).",
     *     operationId="getOrganizationsByLocation",
     *     tags={"Organizations"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         required=true,
     *         description="Широта (latitude), от которой ведётся поиск",
     *         @OA\Schema(
     *             type="number",
     *             format="float",
     *             example=55.7558
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         required=true,
     *         description="Долгота (longitude), от которой ведётся поиск",
     *         @OA\Schema(
     *             type="number",
     *             format="float",
     *             example=37.6176
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=true,
     *         description="Радиус поиска (в километрах)",
     *         @OA\Schema(
     *             type="number",
     *             format="float",
     *             example=5
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций в заданном радиусе",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Не переданы необходимые параметры (lat, lng, radius)"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Получить информацию об организации по ID",
     *     description="Возвращает данные об организации, включая информацию о здании, телефонах и видах деятельности",
     *     operationId="getOrganizationById",
     *     tags={"Organizations"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Идентификатор организации",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с данными организации",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/organizations/search/activity",
     *     summary="Поиск организаций по названию вида деятельности",
     *     description="Возвращает массив организаций, у которых есть указанный (или дочерний) вид деятельности. Если вид деятельности не найден, возвращает пустой массив. Если не передан параметр activity, возвращает ошибку 400.",
     *     operationId="searchOrganizationsByActivity",
     *     tags={"Organizations"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="activity",
     *         in="query",
     *         required=true,
     *         description="Название вида деятельности",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Не передан параметр activity"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/organizations/search/name",
     *     summary="Поиск организаций по названию",
     *     description="Возвращает массив организаций, в названии которых встречается указанный параметр name.",
     *     operationId="searchOrganizationsByName",
     *     tags={"Organizations"},
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Часть или полное название организации для поиска",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список найденных организаций",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Organization")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Не передан параметр name"
     *     )
     * )
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
