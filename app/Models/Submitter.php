<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submitter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'display_name',
        'real_name',
    ];

    /**
     * @return HasMany
     */
    public function entry(): HasMany
    {
        return $this->hasMany(GuestbookEntry::class, 'submitter_id', 'id');
    }

}
