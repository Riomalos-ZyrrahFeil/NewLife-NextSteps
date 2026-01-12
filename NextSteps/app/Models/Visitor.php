<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    protected $table = 'tbl_visitor';
    protected $primaryKey = 'visitor_id';
    public $timestamps = false;
    protected $casts = [
      'first_visit_date' => 'date',
    ];

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'age',
        'gender',
        'contact_number',
        'first_visit_date',
        'first_visit_time',
        'location_id',
        'user_id'
    ];

    /**
     * Accessor for Full Name
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    /**
     * Relationship to Message Status
     */
    public function messageStatus()
    {
        return $this->hasOne(MessageStatus::class, 'visitor_id', 'visitor_id');
    }

    /**
     * Relationship to Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function stageStatuses(): HasMany
    {
        return $this->hasMany(MessageStatus::class, 'visitor_id', 'visitor_id');
    }
}