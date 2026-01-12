<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Scopes\ActiveUserScope;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'tbl_user';
    protected $primaryKey = 'user_id';

    protected static function booted()
    {
        static::addGlobalScope(new ActiveUserScope);
    }

    public function delete()
    {
        $this->is_deleted = 1;
        return $this->save();
    }
    
    public function restore()
    {
        $this->is_deleted = 0;
        return $this->save();
    }
    
    public function scopeArchived(Builder $query)
    {
        return $query->withoutGlobalScope(ActiveUserScope::class)->where('is_deleted', 1);
    }

    /**
     * Maps to tbl_user columns
     */
    public $timestamps = false; 
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password_hash',
        'role',
        'status',
        'is_deleted'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'password_hash' => 'hashed',    
        ];
    }
}