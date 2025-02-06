<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
