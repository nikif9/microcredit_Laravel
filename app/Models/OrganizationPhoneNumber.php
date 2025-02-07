<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="OrganizationPhoneNumber",
 *     title="Organization Phone Number Model",
 *     description="Модель телефонного номера организации",
 *     type="object",
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Идентификатор телефонного номера",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="organization_id",
 *         type="integer",
 *         description="Идентификатор организации, к которой относится данный телефон",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="phone_number",
 *         type="string",
 *         description="Телефонный номер",
 *         example="2-222-222"
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

class OrganizationPhoneNumber extends Model
{
    /**
     * Массив атрибутов, доступных для массового заполнения.
     *
     * @var string[]
     */
    protected $fillable = ['organization_id', 'phone_number'];

    /**
     * Связь телефонного номера с организацией.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
