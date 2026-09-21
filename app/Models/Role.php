<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions->contains('slug', $permissionSlug);
    }

    public function givePermissionTo(Permission|string $permission): void
    {
        $perm = is_string($permission)
            ? Permission::where('slug', $permission)->firstOrFail()
            : $permission;

        $this->permissions()->syncWithoutDetaching([$perm->id]);
    }

    public function revokePermissionTo(Permission|string $permission): void
    {
        $perm = is_string($permission)
            ? Permission::where('slug', $permission)->first()
            : $permission;

        if ($perm) {
            $this->permissions()->detach($perm->id);
        }
    }
}
