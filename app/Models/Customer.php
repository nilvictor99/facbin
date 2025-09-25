<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingFullText;

class Customer extends Model
{
    protected $fillable = [
        'code',
    ];

    #[SearchUsingFullText(['full_name'])]
    public function toSearchableArray(): array
    {
        return [
            'full_name' => $this->profile->full_name,
        ];
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
}
