<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'category_id',
        'title_ar', 'title_en',
        'slug_ar',  'slug_en',
        'author', 'content_ar', 'content_en',
        'meta_title_ar', 'meta_title_en',
        'meta_description_ar', 'meta_description_en',
        'meta_keywords_ar', 'meta_keywords_en',
        'og_image',
        'image',
        'detail_image',   // ✅ الصورة الكبيرة / صورة التفاصيل
        'is_active', 'sort_order', 'published_at', 'tags',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags'         => 'array',
    ];

    // ── Routing ───────────────────────────────────────────────────────

    public function getRouteKey(): mixed
    {
        $slug = app()->getLocale() === 'ar' ? $this->slug_ar : $this->slug_en;
        return $slug ?: $this->getKey();
    }

    public function getRouteKeyName(): string
    {
        return 'slug_en';
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        return static::where('slug_en', $value)
            ->orWhere('slug_ar', $value)
            ->orWhere('id', is_numeric($value) ? $value : 0)
            ->first();
    }

    // ── Boot ──────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug_en)) {
                $blog->slug_en = static::uniqueSlugEn($blog->title_en ?: $blog->title_ar);
            }
            if (empty($blog->slug_ar)) {
                $blog->slug_ar = static::uniqueSlugAr($blog->title_ar ?: $blog->title_en);
            }
        });
    }

    // ── Slug helpers ──────────────────────────────────────────────────

    public static function uniqueSlugEn(string $name, ?int $excludeId = null): string
    {
        $base  = Str::slug($name) ?: 'post';
        $slug  = $base;
        $count = 1;
        while (
            static::where('slug_en', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }
        return $slug;
    }

    public static function uniqueSlugAr(string $name, ?int $excludeId = null): string
    {
        $base  = preg_replace('/[^\p{Arabic}\p{Latin}\d]+/u', '-', trim($name));
        $base  = mb_strtolower(trim($base, '-')) ?: 'post';
        $slug  = $base;
        $count = 1;
        while (
            static::where('slug_ar', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }
        return $slug;
    }

    // ── Accessors ─────────────────────────────────────────────────────

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category?->name ?? '';
    }

    public function getContentAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : $this->content_en;
    }

    public function getMetaTitleAttribute(): string
    {
        $locale = app()->getLocale();
        $field  = $locale === 'ar' ? $this->meta_title_ar : $this->meta_title_en;
        return $field ?: $this->title . ' | ZIZI ABUSALLA';
    }

    public function getMetaDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        $field  = $locale === 'ar' ? $this->meta_description_ar : $this->meta_description_en;
        if ($field) return $field;
        return Str::limit(strip_tags($this->content), 160);
    }

    public function getMetaKeywordsAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? ($this->meta_keywords_ar ?? '') : ($this->meta_keywords_en ?? '');
    }

    public function getOgImageUrlAttribute(): string
    {
        if ($this->og_image) {
            return \Illuminate\Support\Facades\Storage::url($this->og_image);
        }
        return \Illuminate\Support\Facades\Storage::url($this->image);
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // ── Relations ─────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }
}