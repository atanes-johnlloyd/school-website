<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Traits\Auditable;
class SystemSetting extends Model
{
    use Auditable;
    protected $fillable = ['key', 'value', 'group'];

    /**
     * Typed helpers — every call site uses one of these instead of raw get().
     */

    public static function passingGrade(): float
    {
        return (float) static::get('passing_grade', 75);
    }

    public static function maxClassSize(): int
    {
        return (int) static::get('max_class_size', 40);
    }

    public static function announcementDays(): int
    {
        return (int) static::get('announcement_days', 30);
    }

    /** Returns the max upload size in **kilobytes** (Laravel's `max:` rule unit). */
    public static function maxFileUploadKb(): int
    {
        return (int) static::get('max_file_upload_mb', 10) * 1024;
    }

    public static function contactEmailVisible(): bool
    {
        return in_array(
            (string) static::get('contact_email_visible', '1'),
            ['1', 'true', 'yes', 'on'],
            true
        );
    }

    /**
     * Get a setting value (cached for 5 min).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 300, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row?->value ?? $default;
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, ?string $group = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_scalar($value) ? (string) $value : json_encode($value), 'group' => $group]
        );
        Cache::forget("setting.{$key}");
    }

    /**
     * Forget cache for a key (used by seeders/tests).
     */
    public static function forget(string $key): void
    {
        Cache::forget("setting.{$key}");
    }
}