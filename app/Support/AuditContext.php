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

    protected static ?int $actorId = null;

    public static function actorId(): ?int
    {
        return self::$actorId;
    }

    public static function setActor(?int $id): void
    {
        self::$actorId = $id;
    }

    public static function withActor(?int $id, callable $callback)
    {
        $previous = self::$actorId;
        self::$actorId = $id;
        try {
            return $callback();
        } finally {
            self::$actorId = $previous;
        }
    }
}