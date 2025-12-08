<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Scopes\ActiveUserScope;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected static function booted()
    {
        // 2. Apply the scope globally upon booting the model.
        static::addGlobalScope(new ActiveUserScope);
    }
    
    // --- 3. CUSTOM DELETE/RESTORE METHODS (Mimics Soft Deletes) ---
    
    // Override the default delete() method to set is_deleted = true
    public function delete()
    {
        $this->is_deleted = true;
        return $this->save();
    }
    
    // Method to restore the user
    public function restore()
    {
        $this->is_deleted = false;
        return $this->save();
    }
    
    // Define a scope to pull records that ARE deleted (the opposite of the global scope)
    public function scopeArchived(Builder $query)
    {
        // We use withoutGlobalScope() to bypass the 'is_deleted = false' rule
        return $query->withoutGlobalScope(ActiveUserScope::class)->where('is_deleted', true);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
