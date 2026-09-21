<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_LECTURER = 'lecturer';
    public const ROLE_STUDENT = 'student';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identifier',
        'phone',
        'avatar',
        'status',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isLecturer(): bool
    {
        return $this->hasRole(self::ROLE_LECTURER);
    }

    public function isStudent(): bool
    {
        return $this->hasRole(self::ROLE_STUDENT);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Check if user has a role (by slug or array of slugs)
     */
    public function hasRole(string|array $roles): bool
    {
        $roleList = is_array($roles) ? $roles : [$roles];

        // Check primary role column for fast resolution
        if (in_array($this->role, $roleList, true)) {
            return true;
        }

        // Check assigned pivot roles
        return $this->roles->contains(function ($role) use ($roleList) {
            return in_array($role->slug, $roleList, true);
        });
    }

    /**
     * Check if user has permission (Admin has all permissions)
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->hasRole(self::ROLE_ADMIN)) {
            return true;
        }

        // Check across all user's roles
        return $this->roles()->whereHas('permissions', function ($q) use ($permissionSlug) {
            $q->where('slug', $permissionSlug);
        })->exists();
    }

    /**
     * Assign a role to this user
     */
    public function assignRole(string|Role $role): void
    {
        $roleModel = is_string($role) ? Role::where('slug', $role)->firstOrFail() : $role;
        $this->roles()->syncWithoutDetaching([$roleModel->id]);

        // Keep primary role in sync
        $this->update(['role' => $roleModel->slug]);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Courses taught by this lecturer
     */
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Course enrollments (for students)
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
            ->withPivot(['status', 'final_grade', 'enrolled_at'])
            ->withTimestamps();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }
}
