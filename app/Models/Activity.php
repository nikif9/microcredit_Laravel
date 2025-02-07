<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 *
 * @OA\Schema(
 *     schema="Activity",
 *     title="Activity Model",
 *     description="Модель вида деятельности",
 *     type="object",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Идентификатор вида деятельности",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Название вида деятельности",
 *         example="Еда"
 *     ),
 *     @OA\Property(
 *         property="parent_id",
 *         type="integer",
 *         nullable=true,
 *         description="Ссылка на родительский вид деятельности",
 *         example=null
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
 *     @OA\Property(
 *         property="pivot",
 *         type="object",
 *         description="Служебная таблица связи (M2M) между Activity и Organization",
 *         @OA\Property(
 *             property="organization_id",
 *             type="integer",
 *             description="ID связанной организации",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="activity_id",
 *             type="integer",
 *             description="ID вида деятельности",
 *             example=1
 *         )
 *     )
 * )
 */
class Activity extends Model
{
    /**
     * Массив атрибутов, доступных для массового заполнения.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'parent_id'];

    /**
     * Получить родительский вид деятельности.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    /**
     * Получить дочерние виды деятельности.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    /**
     * Получить все организации, связанные с данным видом деятельности.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'activity_organization');
    }
}
