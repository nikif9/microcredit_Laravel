<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="Organization",
 *     title="Organization Model",
 *     description="Модель организации",
 *     type="object",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Идентификатор организации",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Название организации",
 *         example="ООО Рога и Копыта"
 *     ),
 *     @OA\Property(
 *         property="building_id",
 *         type="integer",
 *         description="Идентификатор здания, в котором располагается организация",
 *         example=1
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
 *     ),
 *
 *     @OA\Property(
 *         property="building",
 *         ref="#/components/schemas/Building",
 *         description="Объект, описывающий здание, где расположена организация"
 *     ),
 *
 *     @OA\Property(
 *         property="phone_numbers",
 *         type="array",
 *         description="Массив телефонных номеров организации",
 *         @OA\Items(ref="#/components/schemas/OrganizationPhoneNumber")
 *     ),
 *
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         description="Массив видов деятельности, связанных с организацией",
 *         @OA\Items(ref="#/components/schemas/Activity")
 *     )
 * )
 */

class Organization extends Model
{
    /**
     * Массив атрибутов, доступных для массового заполнения.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'building_id'];

    /**
     * Получить здание, к которому принадлежит организация.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Получить телефонные номера, связанные с организацией.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phoneNumbers()
    {
        return $this->hasMany(OrganizationPhoneNumber::class);
    }

    /**
     * Получить виды деятельности, связанные с организацией.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_organization');
    }
}
