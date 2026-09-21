<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'title',
        'type',
        'file_path',
        'external_url',
        'content',
        'order',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }
}
