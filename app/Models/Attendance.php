<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function checkIn(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->timezone(config('app.timezone')),
        );
    }
    static function getLastCheckIn(int $userId)
    {
        return self::where('user_id', $userId)
            ->whereNull('check_out')
            ->first();
    }
}
