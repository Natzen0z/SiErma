<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAssessment extends Model
{
    protected $fillable = [
        'unit',
        'auditor_id',
        'period_year',
        'triwulan',
        'audit_date',
        'answers',
        'self_answers',
        'self_status',
        'status',
        'auditor_notes',
    ];

    protected $casts = [
        'answers' => 'array',
        'self_answers' => 'array',
        'audit_date' => 'date',
    ];

    protected $appends = ['total_score'];

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    /**
     * Calculate total score from auditor answers (5 questions × max 4 = 20, scaled to 100)
     */
    public function getTotalScoreAttribute(): int
    {
        $answers = $this->answers ?? [];
        if (empty($answers)) return 0;

        $sum = array_sum(array_map('intval', $answers));
        $maxPossible = count($answers) * 4;

        return $maxPossible > 0 ? (int) round(($sum / $maxPossible) * 100) : 0;
    }
}
