<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Building",
 *     title="Building Model",
 *     description="Модель здания",
 *     type="object",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Идентификатор здания",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Адрес здания",
 *         example="г. Москва, ул. Ленина 1, офис 3"
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         type="string",
 *         description="Широта местоположения здания",
 *         example="55.7558000"
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         type="string",
 *         description="Долгота местоположения здания",
 *         example="37.6176000"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Дата и время создания записи",
 *         example="2025-02-06T22:22:17.000000Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Дата и время обновления записи",
 *         example="2025-02-06T22:22:17.000000Z"
 *     )
 * )
 */


class Building extends Model
{
    /**
     * Массив атрибутов, доступных для массового заполнения.
     *
     * @var string[]
     */
    protected $fillable = ['address', 'latitude', 'longitude'];

    /**
     * Получить все организации, связанные с данным зданием.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
}
