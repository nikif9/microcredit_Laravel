<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
