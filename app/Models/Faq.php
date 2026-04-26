<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'category_ar', 'category_en',
        'question_ar', 'question_en',
        'answer_ar', 'answer_en',
        'is_active', 'sort_order',
    ];

    public function getCategoryAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->category_ar : $this->category_en;
    }

    public function getQuestionAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->question_ar : $this->question_en;
    }

    public function getAnswerAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->answer_ar : $this->answer_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
