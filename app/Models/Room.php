<?php

namespace App\Models;

use App\Enums\AccountStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'number',
        'label',
        'allow_multiple_occupants',
        'max_occupants',
    ];

    protected function casts(): array
    {
        return [
            'allow_multiple_occupants' => 'boolean',
        ];
    }

    public function residents(): HasMany
    {
        return $this->hasMany(User::class, 'room_number', 'number');
    }

    public function activeResidents(): HasMany
    {
        return $this->residents()
            ->whereIn('status', [AccountStatus::Approved, AccountStatus::Pending])
            ->orderBy('name');
    }

    public function cleaningSchedules(): HasMany
    {
        return $this->hasMany(CleaningSchedule::class);
    }

    public function displayName(): string
    {
        return $this->label ?: 'Room '.$this->number;
    }

    /** @return list<string> */
    public function residentNames(): array
    {
        if ($this->relationLoaded('activeResidents')) {
            return $this->activeResidents->pluck('name')->all();
        }

        return $this->activeResidents()->pluck('name')->all();
    }

    public function assigneeLabel(): string
    {
        $names = $this->residentNames();

        return $names !== [] ? implode(' & ', $names) : 'Awaiting resident assignment';
    }
}