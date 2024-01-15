<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuestbookEntry extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'title',
        'content',
        'submitter_id',
    ];

    /**
     * @return HasOne
     */
    public function submitter(): HasOne
    {
        return $this->hasOne(Submitter::class, 'id', 'submitter_id');
    }

}
