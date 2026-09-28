<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Auditable;
class Assignment extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'class_id',            // ← must be here
        'class_module_id',
        'title',
        'category',
        'instructions',
        'due_at',
        'points',
        'allow_late',
        'is_published',
    ];

    protected $casts = [
        'due_at'       => 'datetime',
        'points'       => 'decimal:2',
        'allow_late'   => 'boolean',
        'is_published' => 'boolean',
        'category'     => 'string',
    ];

    public function classroom()       { return $this->belongsTo(ClassRoom::class, 'class_id'); }
    public function module()      { return $this->belongsTo(ClassModule::class, 'class_module_id'); }
    public function submissions() { return $this->hasMany(AssignmentSubmission::class); }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeDueSoon(Builder $q, int $days = 7): Builder
    {
        return $q->whereBetween('due_at', [now(), now()->addDays($days)]);
    }

    public function scopeNotSubmittedBy(Builder $q, int $studentId): Builder
    {
        return $q->whereDoesntHave('submissions', function ($sub) use ($studentId) {
            $sub->where('student_id', $studentId)
                ->whereIn('status', ['submitted', 'late', 'graded']);
        });
    }

    public function submissionFor(int $studentId): ?AssignmentSubmission
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
}