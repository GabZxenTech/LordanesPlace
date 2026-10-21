<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Faq extends Model
{
    protected $fillable = ['category', 'question', 'answer', 'chat_label', 'sort_order', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    // Category order on the FAQ page.
    public const CATEGORIES = ['Location & Hours', 'Packages & Pricing', 'Booking & Payment', 'Cancellation & Reschedule', 'Account'];

    // Published FAQs, cached for the FAQ page and the chat widget.
    public static function published()
    {
        return Cache::remember('faqs.published', 600, fn () => static::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get());
    }

    // Clear the cache whenever an FAQ changes.
    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('faqs.published'));
        static::deleted(fn () => Cache::forget('faqs.published'));
    }
}
