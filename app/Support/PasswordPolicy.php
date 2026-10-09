<?php

namespace App\Support;

use Illuminate\Validation\Rules\Password;

class PasswordPolicy
{
    public const MIN          = 12;
    public const LETTERS      = true;
    public const MIXED_CASE   = true;
    public const NUMBERS      = true;
    public const SYMBOLS      = true;

    public static function rule(): Password
    {
        $rule = Password::min(self::MIN)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols();

        if (app()->isProduction()) {
            $rule = $rule->uncompromised();
        }

        return $rule;
    }

    /** Frontend-friendly description of the active rule. */
    public static function toArray(): array
    {
        return [
            'min'           => self::MIN,
            'letters'       => self::LETTERS,
            'mixedCase'     => self::MIXED_CASE,
            'numbers'       => self::NUMBERS,
            'symbols'       => self::SYMBOLS,
            'uncompromised' => app()->isProduction(),
        ];
    }
}