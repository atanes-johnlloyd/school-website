<?php

namespace App\Support;

class AuditContext
{
    protected static ?string $event = null;
    protected static array $extra = [];

    public static function set(string $event, array $extra = []): void
    {
        static::$event = $event;
        static::$extra = $extra;
    }

    public static function event(): ?string
    {
        return static::$event;
    }

    public static function extra(): array
    {
        return static::$extra;
    }

    public static function clear(): void
    {
        static::$event = null;
        static::$extra = [];
    }

    /**
     * Run a callback with the event context set, clearing it afterward.
     */
    public static function wrap(string $event, callable $fn, array $extra = [])
    {
        static::set($event, $extra);
        try {
            return $fn();
        } finally {
            static::clear();
        }
    }
}