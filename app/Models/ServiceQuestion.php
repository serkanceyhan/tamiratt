<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceQuestion extends Model
{
    protected $fillable = [
        'service_id',
        'question',
        'type',
        'options',
        'is_required',
        'sort_order',
        'parent_question_id',
        'show_condition',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'show_condition' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service this question belongs to
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the parent question (for dependent questions)
     */
    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(ServiceQuestion::class, 'parent_question_id');
    }

    /**
     * Get child questions (dependent on this question)
     */
    public function childQuestions(): HasMany
    {
        return $this->hasMany(ServiceQuestion::class, 'parent_question_id');
    }

    /**
     * Scope to get only active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only root questions (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_question_id');
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if this question should be shown based on condition
     */
    public function shouldShowFor(array $answers): bool
    {
        if (empty($this->show_condition)) {
            return true;
        }

        // Example condition: { "question_id": 1, "value": "option_a" }
        $questionId = $this->show_condition['question_id'] ?? null;
        $expectedValue = $this->show_condition['value'] ?? null;

        if (!$questionId || !$expectedValue) {
            return true;
        }

        $answerValue = $answers[$questionId] ?? null;
        
        if (is_array($expectedValue)) {
            return in_array($answerValue, $expectedValue);
        }

        return $answerValue === $expectedValue;
    }
}
