<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name_ar',
        'name_en',
        'slug_ar',
        'slug_en',
        'description_ar',
        'description_en',
        'video_ar',
        'video_en',
        'meta_title_ar',
        'meta_title_en',
        'meta_description_ar',
        'meta_description_en',
        'meta_keywords_ar',
        'meta_keywords_en',
        'og_image',
        'price',
        'old_price',
        'image',
        'sizes',
        'colors',
        'brand',
        'stock',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    // ── Routing ──────────────────────────────────────────────────────────────

    /**
     * Return the locale-aware slug for URL generation.
     * route('shop.show', $product) uses this automatically.
     */
    public function getRouteKey(): mixed
    {
        return app()->getLocale() === 'ar' ? $this->slug_ar : $this->slug_en;
    }

    public function getRouteKeyName(): string
    {
        return 'slug_en'; // fallback field; actual resolution is in resolveRouteBinding
    }

    /**
     * Resolve {product} in routes from either slug_ar or slug_en.
     */
    public function resolveRouteBinding($value, $field = null): ?static
    {
        return static::where('slug_en', $value)
            ->orWhere('slug_ar', $value)
            ->first();
    }

    // ── Boot ─────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug_en)) {
                $product->slug_en = static::uniqueSlugEn($product->name_en ?: $product->name_ar);
            }
            if (empty($product->slug_ar)) {
                $product->slug_ar = static::uniqueSlugAr($product->name_ar ?: $product->name_en);
            }
        });
    }

    // ── Slug helpers ─────────────────────────────────────────────────────────

    public static function uniqueSlugEn(string $name, ?int $excludeId = null): string
    {
        $base  = Str::slug($name) ?: Str::slug(transliterator_transliterate('Any-Latin; Latin-ASCII', $name));
        $base  = $base ?: 'product';
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
        // Keep Arabic + Latin + digits, collapse non-matching chars to hyphen
        $base = preg_replace('/[^\p{Arabic}\p{Latin}\d]+/u', '-', trim($name));
        $base = mb_strtolower(trim($base, '-')) ?: 'product';
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

    protected $casts = [
        'sizes'   => 'array',
        'colors'  => 'array',
    ];

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar ?? '' : $this->description_en ?? '';
    }

    public function getMetaTitleAttribute(): string
    {
        $locale = app()->getLocale();
        $field  = $locale === 'ar' ? $this->meta_title_ar : $this->meta_title_en;
        return $field ?: $this->name . ' | ZIZI ABUSALLA';
    }

    public function getMetaDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        $field  = $locale === 'ar' ? $this->meta_description_ar : $this->meta_description_en;
        if ($field) return $field;
        return Str::limit(strip_tags($this->description), 160);
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

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return round((($this->old_price - $this->price) / $this->old_price) * 100);
        }
        return null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function wishlistedBy()
{
    return $this->belongsToMany(User::class, 'wishlists');
}
}
