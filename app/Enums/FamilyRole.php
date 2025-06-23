<?php

namespace App\Enums;

enum FamilyRole: string
{
    case Adult = 'adult';
    case Child = 'child';

    public function label(): string
    {
        return match($this) {
            self::Adult => 'adult',     // Display as "adult" in UI
            self::Child => 'child',     // Display as "child" in UI
        };
    }
    
    public static function options(): array
    {
        return [
            self::Adult->value => self::Adult->label(),
            self::Child->value => self::Child->label(),
        ];
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}