<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
