<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель телефонного номера для организации.
 *
 * @property int $id
 * @property int $organization_id
 * @property string $phone_number
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
