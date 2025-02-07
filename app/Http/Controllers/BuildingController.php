<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Building;

/**
 * @OA\Tag(
 *     name="Buildings",
 *     description="Операции, связанные со зданиями"
 * )
 *
 */
class BuildingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/buildings",
     *     summary="Получить список всех зданий",
     *     description="Возвращает список всех зданий из базы данных",
     *     operationId="getAllBuildings",
     *     tags={"Buildings"},
     *     security={{"api_key": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Список зданий успешно получен",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Building")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный запрос"
     *     )
     * )
     */
    public function index()
    {
        $buildings = Building::all();
        return response()->json($buildings);
    }
}
