<?php

namespace App\Helpers;

trait HasGuidTrait
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->guid = guid();
        });
    }

    /**
     * Scope a query to only include Active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $guid
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindOrFailByGuid($query, $guid)
    {
        return $query->whereGuid($guid)->firstOrFail();
    }

    /**
     * Eloquent scope to look for a given UUID
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param String $guid The UUID to search for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUuid($query, $guid)
    {
        return $query->where('guid', $guid);
    }

    /**
     * Eloquent scope to look for multiple given GUIDs
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Array $guids The GUIDs to search for
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithGuids($query, Array $guids)
    {
        return $query->whereIn('guid', $guids);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'guid';
    }
}
