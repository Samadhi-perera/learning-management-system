<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'description',
        'meeting_url',
        'meeting_id',
        'passcode',
        'start_time',
        'duration_minutes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'duration_minutes' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_attendances')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    public function isLive(): bool
    {
        return $this->status === 'live';
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function hasAttended(int $userId): bool
    {
        return $this->attendances()->where('user_id', $userId)->exists();
    }

    public function getStudentAttendance(int $userId): ?MeetingAttendance
    {
        return $this->attendances()->where('user_id', $userId)->first();
    }
}
