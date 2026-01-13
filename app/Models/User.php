<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Owner\SecuritySystemTrait;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use SecuritySystemTrait;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function profile()
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function address()
    {
        return $this->morphOne(Addresse::class, 'addressable');
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function scopeWithUserProfile(Builder $query)
    {
        return $query->with(['profile']);
    }

    public function scopeSearchUserData(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'ILIKE', "%{$search}%")
                ->orWhereHas('profile', function ($q2) use ($search) {
                    $q2->where('full_name', 'ILIKE', "%{$search}%");
                })
                ->orWhereHas('contacts', function ($q2) use ($search) {
                    $q2->where('contact_value', 'ILIKE', "%{$search}%");
                });
        });
    }

    public function scopeFilterByUser(Builder $query, $userId)
    {
        return $query->when($userId, function ($query) use ($userId) {
            $query->where('id', $userId);
        });
    }

    public function scopeSearchData(Builder $query, $search = null, $startDate = null, $endDate = null, $staffId = null)
    {
        if (! empty($search)) {
            $query->searchUserData($search);
        }
        if (! empty($startDate) || ! empty($endDate)) {
            $query->filterByDateRange($startDate, $endDate);
        }
        if (! empty($userId)) {
            $query->filterByUser($userId);
        }

        return $query;
    }

    public function scopeFilterByDateRange(Builder $query, $startDate, $endDate)
    {
        if (! empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if (! empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }
}
