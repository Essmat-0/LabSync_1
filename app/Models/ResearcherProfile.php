<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearcherProfile extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'academicLevel',
        'pis_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isClear(): bool
    {
        return ($this->academicLevel === $this->user->assignClearance());
    }

    public function canAccess($equipment): bool
    {
        return $this->user->clearance_level >= $equipment->required_clearance;
    }
}