<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiProfile extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'budget_limit',
        'affiliation'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}